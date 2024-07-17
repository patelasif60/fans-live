<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ContentFeed;
use App\Models\FeedItem;
use App\Services\FeedItemService;
use Illuminate\Http\Request;
use JavaScript;

class FeedItemController extends Controller
{
    /**
     * A Feed Item service.
     *
     * @var feedItemService
     */
    protected $feedItemService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FeedItemService $feedItemService)
    {
        $this->middleware('auth');
        $this->feedItemService = $feedItemService;
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->feedItemService);
    }

    /**
     * Display a listing of feed items.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        JavaScript::put([
            'dateTimeCmsFormat' => config('fanslive.DATE_TIME_CMS_FORMAT.js'),
        ]);

        $contentFeeds = ContentFeed::all();

        return view('backend.feeditems.index', compact('contentFeeds'));
    }

    /**
     * Show feed item detail.
     *
     * @param $clubId
     * @param FeedItem $feedItem
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($clubId, FeedItem $feedItem)
    {
        return view('backend.feeditems.detail', compact('feedItem'));
    }

    /**
     * update feed item detail.
     *
     * @param $clubId
     * @param FeedItem $feedItem
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Request $request, $clubId, $feedItem)
    {
        $feedItemToUpdate = $this->feedItemService->update(
            auth()->user(),
            $feedItem,
            $request->all()
        );
        if ($feedItemToUpdate) {
            flash('Feed item updated successfully')->success();
        } else {
            flash('Feed item could not be updated. Please try again.')->error();
        }

        return redirect()->route('backend.feeditem.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Get feed item list data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFeedItemData(Request $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $feedItemList = $this->feedItemService->getData(
            $clubId,
            $request->all()
        );

        return $feedItemList;
    }

    /**
     * get feed item detail.
     *
     * @param $clubId
     * @param FeedItem $feedItem
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFeedItems()
    {
        $this->feedItemService->getUpdateFeeds();
    }
}
