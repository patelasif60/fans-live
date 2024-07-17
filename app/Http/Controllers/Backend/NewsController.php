<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\News\UpdateRequest;
use App\Models\News;
use App\Services\NewsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use JavaScript;
/**
 * News Controller class to handle request.
 */
class NewsController extends Controller
{
    /**
     * The news service instance.
     *
     * @var service
     */
    public function __construct(NewsService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		return view('backend.news.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $newsStatus = config('fanslive.PUBLISH_STATUS');

        return view('backend.news.create', compact('newsStatus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $club
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $club)
    {
        $clubId = getClubIdBySlug($club);

        $news = $this->service->create(
            $clubId,
            auth()->user(),
            $request->all()
        );

        if ($news) {
            flash('News created successfully')->success();
        } else {
            flash('News could not be created. Please try again.')->error();
        }

        return redirect()->route('backend.news.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $clubId
     * @param  $news
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $clubId, News $news)
    {
        $newsStatus = config('fanslive.PUBLISH_STATUS');

        return view('backend.news.edit', compact('newsStatus', 'news'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  $clubId
     * @param  $news
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $clubId, News $news)
    {
        $newsToUpdate = $this->service->update(
            auth()->user(),
            $news,
            $request->all()
        );

        if ($newsToUpdate) {
            flash('News updated successfully')->success();
        } else {
            flash('News could not be updated. Please try again.')->error();
        }

        return redirect()->route('backend.news.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $clubId
     *  @param  $news
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($clubId, News $news)
    {
        $newsLogoToDelete = $this->service->deleteLogo($news);
        if ($news->delete()) {
            flash('News deleted successfully')->success();
        } else {
            flash('News could not be deleted. Please try again.')->error();
        }

        return redirect()->route('backend.news.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Get News list data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getNewsData(Request $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $newsList = $this->service->getData(
            $clubId,
            $request->all()
        );

        return $newsList;
    }

    /**
     * unset class instance.
     */
    public function __destruct()
    {
        unset($this->service);
    }
}
