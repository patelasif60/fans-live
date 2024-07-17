<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClubInformationPage\StoreRequest;
use App\Http\Requests\ClubInformationPage\UpdateRequest;
use App\Services\ClubInformationPageService;
use App\Models\ClubInformationPage;
use JavaScript;

class ClubInformationPageController extends Controller
{
    /**
     * A Clubinformation service.
     *
     * @var clubInformationPageService
     */
    protected $clubInformationPageService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ClubInformationPageService $clubInformationPageService)
    {
        $this->middleware('auth');
        $this->clubInformationPageService = $clubInformationPageService;
    }

	/**
    * Display a listing of Club information page.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
		JavaScript::put([
			'dateTimeCmsFormat' => config('fanslive.DATE_TIME_CMS_FORMAT.js'),
		]);
    	return view('backend.clubinformationpages.index');
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $clubInformationPageStatus = config('fanslive.PUBLISH_STATUS');
        return view('backend.clubinformationpages.create', compact('clubInformationPageStatus'));
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param $club
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(StoreRequest $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $clubInformationPage = $this->clubInformationPageService->create(
            $clubId,
            auth()->user(),
            $request->all()
        );
        if ($clubInformationPage) {
            flash('Club information page created successfully')->success();
        } else {
            flash('Club information page could not be created. Please try again.')->error();
        }

        return redirect()->route('backend.clubinformationpages.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $request
     * @param int $clubId
     * @param int $clubInformationPage
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $clubId, ClubInformationPage $clubInformationPage)
    {
        $clubInfo = [];
        if ($clubInformationPage->clubInformationPageContent) {
            foreach ($clubInformationPage->clubInformationPageContent as $key => $value) {
                $clubInfo[$key]['id'] = $value->id;
                $clubInfo[$key]['title'] = $value->title;
                $clubInfo[$key]['description'] = $value->content;
            }
        }

        Javascript::put([
            'clubInfo' => $clubInfo,
        ]);

        $clubInformationPageStatus = config('fanslive.PUBLISH_STATUS');

        return view('backend.clubinformationpages.edit', compact('clubInformationPageStatus', 'clubInformationPage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $request
     * @param int                      $clubId
     * @param int                      $clubInformationPage
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $clubId, ClubInformationpage $clubInformationPage)
    {
        $clubInformationPageToUpdate = $this->clubInformationPageService->update(
            auth()->user(),
            $clubInformationPage,
            $request->all()
        );

        if ($clubInformationPageToUpdate) {
            flash('Clubinformation page updated successfully')->success();
        } else {
            flash('Clubinformation page could not be updated. Please try again.')->error();
        }

        return redirect()->route('backend.clubinformationpages.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $clubId
     * @param int $clubInformationPage
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($clubId, ClubInformationPage $clubInformationPage)
    {
        $clubInformationPhotoDelete = $this->clubInformationPageService->deleteIcon($clubInformationPage);

        if ($clubInformationPage->delete()) {
            flash('Club information page deleted successfully')->success();
        } else {
            flash('Club information page could not be deleted. Please try again.')->error();
        }

        return redirect()->route('backend.clubinformationpages.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Get CLub information list data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getClubInformationPageData(Request $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $clubInformationList = $this->clubInformationPageService->getClubInformationPageData(
            $clubId,
            $request->all()
        );

        return $clubInformationList;
    }

}
