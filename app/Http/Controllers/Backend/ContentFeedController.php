<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contentfeed\StoreRequest;
use App\Http\Requests\Contentfeed\UpdateRequest;
use App\Models\ContentFeed;
use App\Services\ContentFeedService;
use Illuminate\Http\Request;
use JavaScript;

class ContentFeedController extends Controller
{
    /**
     * A Content Feed service.
     *
     * @var contentFeed
     */
    protected $contentFeedService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ContentFeedService $contentFeedService)
    {
        //$this->middleware('auth');
        $this->contentFeedService = $contentFeedService;
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->contentFeedService);
    }

    /**
     * Display a listing of content feeds.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		JavaScript::put([
			'dateTimeCmsFormat' => config('fanslive.DATE_TIME_CMS_FORMAT.js'),
		]);
        return view('backend.contentfeeds.index');
    }

    /**
     * Show the form for creating a new content feed.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $feedTypes = config('fanslive.FEED_TYPES');

        return view('backend.contentfeeds.create', compact('feedTypes'));
    }

    /**
     * Store a newly created content feed.
     *
     * @param $club
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $contentFeed = $this->contentFeedService->create(
            $clubId,
            auth()->user(),
            $request->all()
        );
        if ($contentFeed) {
            flash('Content feed created successfully')->success();
        } else {
            $error = flash('Something went wrong with your keys or id.')->error();

            return  redirect()->back()->withInput()->withErrors($error);
        }

        return redirect()->route('backend.contentfeed.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($clubId, ContentFeed $contentFeed)
    {
        $feedTypes = config('fanslive.FEED_TYPES');

        return view('backend.contentfeeds.edit', compact('feedTypes', 'contentFeed'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $clubId, ContentFeed $contentFeed)
    {
        $contentFeedToUpdate = $this->contentFeedService->update(
            auth()->user(),
            $contentFeed,
            $request->all()
        );

        if ($contentFeedToUpdate) {
            flash('Content feed updated successfully')->success();
        } else {
            $error = flash('Something went wrong with your keys or id.')->error();

            return  redirect()->back()->withInput()->withErrors($error);
        }

        return redirect()->route('backend.contentfeed.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Remove the specified content feed.
     *
     * @param int $contentFeed
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($clubId, ContentFeed $contentFeed)
    {
        if ($contentFeed->delete()) {
            flash('Content feed deleted successfully')->success();
        } else {
            flash('Content feed could not be deleted. Please try again.')->error();
        }

        return redirect()->route('backend.contentfeed.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Get content feed list data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getContentFeedData(Request $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $contentFeedList = $this->contentFeedService->getData(
            $clubId,
            $request->all()
        );

        return $contentFeedList;
    }
}
