<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\News\GetNewsDetailsRequest;
use App\Http\Requests\Api\News\GetNewsRequest;
use App\Http\Resources\News\News as NewsResource;
use App\Models\News;
use App\Services\NewsService;

/**
 * @group News
 *
 * APIs for News.
 */
class NewsController extends BaseController
{
    /**
     * Create a news service variable.
     *
     * @return void
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(NewsService $service)
    {
        $this->service = $service;
    }

    /**
     * Get news
     * Get all published news of a club.
     *
     * @bodyParam club_id int required An id of a club. Example: 1
     *
     * @return \Illuminate\Http\Response
     */
    public function getNews(GetNewsRequest $request)
    {
        $news = News::where('club_id', $request['club_id'])->where('status', 'Published')->where('publication_date', '<', now())->get();

        return NewsResource::collection($news);
    }

    /**
     * Get news details
     * Get news details.
     *
     * @bodyParam id int required An id of a news. Example: 1
     *
     * @return \Illuminate\Http\Response
     */
    public function getNewsDetails(GetNewsDetailsRequest $request)
    {
        $news = News::where('id', $request['id'])->first();

        return new NewsResource($news);
    }
}
