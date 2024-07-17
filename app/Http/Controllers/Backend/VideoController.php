<?php

namespace App\Http\Controllers\Backend;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Services\VideoService;
use App\Models\MembershipPackage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Video\StoreRequest;
use App\Http\Requests\Video\UpdateRequest;
use App\Services\MembershipPackageService;

/**
 * video Controller class to handle request.
 */
class VideoController extends Controller
{
    /**
     * The Video service.
     *
     * @var VideoService
     */
    protected $service;

    /**
     * The MembershipPackage user service.
     *
     * @var MembershipPackageService
     */
    protected $membershipPackageService;

    /**
     * The video service instance.
     *
     * @var service
     */
    public function __construct(VideoService $service, MembershipPackageService $membershipPackageService)
    {
        $this->service = $service;
        $this->membershipPackageService = $membershipPackageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.video.index');
    }

    /**
     * Show the form for creating a video resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($clubId)
    {
        $clubId = getClubIdBySlug($clubId);
        $membershipPackages = $this->membershipPackageService->getMembershipPackageForCurrentClub($clubId);
        $videoStatus = config('fanslive.PUBLISH_STATUS');

        return view('backend.video.create', compact('membershipPackages', 'videoStatus'));
    }

    /**
     * Store a category created resource in storage.
     *
     * @param \App\Http\Requests\Category\StoreRequest $request
     * @param  $club
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $video = $this->service->create(
            $clubId,
            auth()->user(),
            $request->all()
        );

        if ($video) {
            flash('Video created successfully')->success();
        } else {
            flash('Video could not be created. Please try again.')->error();
        }

        return redirect()->route('backend.video.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param  $clubId
     * @param  $video
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $clubId, Video $video)
    {
        $clubId = getClubIdBySlug($clubId);
        $membershipPackages = $this->membershipPackageService->getMembershipPackageForCurrentClub($clubId);
        $videoStatus = config('fanslive.PUBLISH_STATUS');

        $membershipPackagesIds = $video->membershippackages->pluck('id')->toArray();

        return view('backend.video.edit', compact('membershipPackages', 'videoStatus', 'video', 'membershipPackagesIds'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Category\UpdateRequest $request
     * @param  $clubId
     * @param  $category
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $clubId, Video $video)
    {
        $videoToUpdate = $this->service->update(
            auth()->user(),
            $video,
            $request->all()
        );

        if ($videoToUpdate) {
            flash('Video updated successfully')->success();
        } else {
            flash('Video could not be updated. Please try again.')->error();
        }

        return redirect()->route('backend.video.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $clubId
     * @param  $video
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($clubId, Video $video)
    {
        $videoAssetDelete = $this->service->deleteAsset($video);
        if ($video->delete()) {
            flash('Video deleted successfully')->success();
        } else {
            flash('Video could not be deleted. Please try again.')->error();
        }

        return redirect()->route('backend.video.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Get Video list data.
     *
     * @param \Illuminate\Http\Request $request
     * @param  $clubId
     *
     * @return \Illuminate\Http\Response
     */
    public function getVideosData(Request $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $videos = $this->service->getData($clubId, $request->all());

        return $videos;
    }

    /**
     * unset class instance.
     */
    public function __destruct()
    {
        unset($this->service);
    }
}
