<?php

namespace App\Services;

use Alaouy\Youtube\Facades\Youtube;
use App\Mail\facebookToken;
use App\Models\Club;
use App\Models\ContentFeed;
use App\Repositories\FeedItemRepository;
use App\Vendor\Vinkla\Instagram\src\CustomInstagram;
use Carbon\Carbon;
use Exception;
use Mail;
use Twitter;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
/**
 * User class to handle operator interactions.
 */
class FeedItemService
{
    /**
     * The feed item repository instance.
     *
     * @var feedItemRepository
     */
    private $feedItemRepository;

    /**
     * Create a new service instance.
     *
     * @param FeedItemRepository $feedItemRepository
     */
    public function __construct(FeedItemRepository $feedItemRepository)
    {
        $this->feedItemRepository = $feedItemRepository;
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->feedItemRepository);
    }

    /**
     * Get Feed Item data.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $feedItem = $this->feedItemRepository->getData($clubId, $data);

        return $feedItem;
    }

    /**
     * Send mail on failure of getting feeds.
     *
     * @param $Id
     * @param $error
     * @param $type
     * @param $club_slug
     *
     * @return
     */
    public function sendMail($id, $club_slug, $type, $error = null)
    {
         Mail::to(config('mail.mail_config.support_team'))->send(new facebookToken($id, $club_slug, $type, $error));
    }

    /**
     * get feeds.
     *
     * @return
     */
    public function getUpdateFeeds()
    {
        $contentFeeds = ContentFeed::all();
        $data = [];
        foreach ($contentFeeds as $key=>$val) {
            $clubSlug = club::where('id', $val->club_id)->select('slug')->first();
            if ($val->type == 'Facebook') {
                $data[$key] = $this->getFacebookData($val, $clubSlug);
            }
            if ($val->type == 'Instagram') {
                $data[$key] = $this->getInstagramData($val, $clubSlug);
            }
            if ($val->type == 'Twitter') {
                $data[$key] = $this->getTwitterData($val, $clubSlug);
            }
            if ($val->type == 'RSS') {
                $data[$key] = $this->getRssData($val, $clubSlug);
            }
            if ($val->type == 'Youtube') {
                $data[$key] = $this->getYoutubeData($val, $clubSlug);
            }
            $data[$key]['content_feed_id'] = $val->id;
            $data[$key]['club_id'] = $val->club_id;
            $data[$key]['status'] = $val->is_automatically_publish_items == 1 ? 'Published' : 'Hidden';
            $data[$key]['lastInserDataCompare']= $val->last_inserted_data;
            
        }
        //print_r($data);die;
        foreach ($data as $values) {            
            foreach ($values as $key => $value) {       
                if (is_numeric($key)) {
                    $media = $text = $title = $youtubeId = $feedUrl = $fbflag = null;
                    if ($values['type'] == 'Twitter') {
                        $text = $value['text'];
                        $screenName = $value['user']['screen_name'];
                        $twittId = $value['id'];
                        $feedUrl = str_replace('{screenName}', $screenName, config('fanslive.FEED_URL.twitter'));
                        $feedUrl = str_replace('{twittId}', $twittId, $feedUrl);
                        $postedOn = Carbon::parse($value['created_at']);
                        if (isset($value['extended_entities'])) {
                            foreach ($value['extended_entities']['media'] as $mediaKey=>$mediaVal) {
                                if ($mediaVal['type'] == 'photo') {
                                    $media[$mediaKey]['type'] = 'image';
                                    $media[$mediaKey]['url'] = $mediaVal['media_url'];
                                } elseif ($mediaVal['type'] == 'video') {
                                    $media[$mediaKey]['type'] = 'video';
                                    foreach ($mediaVal['video_info']['variants'] as $videoDetail) {
                                        if (isset($videoDetail['bitrate']) && $videoDetail['bitrate'] == 832000) {
                                            $media[$mediaKey]['url'] = $videoDetail['url'];
                                        }
                                    }
                                }
                                $media[$mediaKey]['thumbnail'] = $mediaVal['media_url'];
                            }
                            $media = json_encode($media);
                        }
                        if (!isset($last_inserted_data)) {
                            $last_inserted_data = $value['id'];
                        }
                    }
                    if ($values['type'] == 'Facebook') {
                        if ($value['type'] == 'photo' || $value['type'] == 'status') {
                            $text = isset($value['message']) ? $value['message'] : null;
                            if (isset($value['attachments'])) {
                                foreach ($value['attachments'] as $attechmentKey=>$attechment) {
                                    if (isset($attechment['subattachments'])) {
                                        foreach ($attechment['subattachments'] as $subAttechmentKey=>$subAttechment) {
                                            if ($subAttechment['type'] == 'photo') {
                                                $media[$subAttechmentKey]['url'] = $subAttechment['media']['image']['src'];
                                                $media[$subAttechmentKey]['type'] = 'image';
                                                $media[$subAttechmentKey]['thumbnail'] = null;
                                            }
                                        }
                                    } else {
                                        if (isset($attechment['media'])) {
                                            if ($attechment['type'] == 'photo') {
                                                $media[$attechmentKey]['url'] = $attechment['media']['image']['src'];
                                                $media[$attechmentKey]['type'] = 'image';
                                                $media[$attechmentKey]['thumbnail'] = null;
                                            }
                                        }
                                    }
                                }
                            }
                            $feedUrl = $value['permalink_url'];
                            if ($media) {
                                $media = json_encode($media);
                            }
                            $last_inserted_data = $values['last_inserted_data'];
                            $postedOn = isset($value['created_time']) ? $value['created_time']->format('Y-m-d H:i:s') : null;
                        } else {
                            $fbflag = 1;
                        }
                    }
                    if ($values['type'] == 'Instagram') {
                        $postedOn = Carbon::parse($value['timestamp']); 
                        if($postedOn <= $values['lastInserDataCompare'])
                        {
                            break;
                        }
                        
                        $flag = 0;
                        if ($value['media_type'] == 'IMAGE') {
                            $media[$flag]['url'] = $value['media_url'];
                            $media[$flag]['type'] = 'image';
                            $media[$flag]['thumbnail'] =$value['media_url'];
                        }
                        elseif ($value['media_type'] == 'VIDEO') {
                            $media[$flag]['url'] =$value['media_url'];
                            $media[$flag]['type'] = 'video';
                            $media[$flag]['thumbnail'] = $value['thumbnail_url'];
                        }
                        else if($value['media_type']=='CAROUSEL_ALBUM')
                        {
                            $media[$flag]['url'] = $value['media_url'];
                            $media[$flag]['type'] = 'carousel album';
                            $media[$flag]['thumbnail'] = $value['media_url'];
                        }

                        $media = json_encode($media);
                        $feedUrl = $value['permalink'];
                        if(isset($value['caption']))
                        {
                            $text = $value['caption'] == null ? null : $value['caption']; 
                        }   
                        if (!isset($last_inserted_data)) {
                            $last_inserted_data = $postedOn;
                        }
                        
                    }
                    if ($values['type'] == 'RSS') {
                        $text = $value->get_description();
                        $postedOn = Carbon::parse($value->get_date());
                        $title = $value->get_title();
                        $last_inserted_data = $values['last_inserted_data'];
                    }
                    if ($values['type'] == 'Youtube') {
                        $text = $value->snippet->description;
                        $postedOn = Carbon::parse($value->snippet->publishedAt);
                        $title = $value->snippet->title;
                        $last_inserted_data = $values['last_inserted_data'];
                        $youtubeId = $value->id->videoId;
                        $feedUrl = config('fanslive.FEED_URL.youtube').$value->id->videoId;
                    }

                    $dbFields = [
                        'club_id'         => $values['club_id'],
                        'content_feed_id' => $values['content_feed_id'],
                        'text'            => $text,
                        'media'           => $media,
                        'status'          => $values['status'],
                        'title'           => $title,
                        'publication_date'=> $postedOn,
                        'youtube_id'      => $youtubeId,
                        'feed_url'        => $feedUrl,
                    ];
                    if ($fbflag == null) {
                        $this->feedItemRepository->create($dbFields);
                    }
                }
            }
            if (isset($last_inserted_data)) {
                 ContentFeed::where('id', $values['content_feed_id'])->update(['last_inserted_data' => $last_inserted_data]);
                unset($last_inserted_data);
            }
            ContentFeed::where('id', $values['content_feed_id'])->update(['last_imported' => Carbon::now()->format('Y-m-d H:i:s')]);
        }
        // $feedItem = $this->feedItemRepository->create(auth()->user(),$data);
    }

    /**
     * convert into array.
     *
     * @return
     */
    public function getDetailFb($edges, $count = 0)
    {
        $data = $edges->asArray();

        return $data;
    }

    /**
     * get youtube data.
     *
     * @return
     */
    public function getYoutubeData($val, $clubSlug)
    {
        $data = [];

        try {
            Youtube::setApiKey($val->api_key);
            if (Youtube::listChannelVideos($val->api_channel_id) == false) {
                throw new Exception('channel id is wrong');
            }
            if ($val->last_inserted_data) {
                if (count(Youtube::listChannelVideos($val->api_channel_id)) - $val->last_inserted_data > 0) {
                    $data = Youtube::listChannelVideos($val->api_channel_id, count(Youtube::listChannelVideos($val->api_channel_id)) - $val->last_inserted_data);
                }
            } else {
                $data = Youtube::listChannelVideos($val->api_channel_id);
            }
            $data['last_inserted_data'] = count(Youtube::listChannelVideos($val->api_channel_id));
            $data['type'] = $val->type;

            return $data;
        } catch (Exception $exception) {
            $this->sendMail($val->id, $clubSlug->slug, 'Youtube', $exception->getMessage());
        }
    }

    /**
     * get RSS feeds.
     *
     * @return
     */
    public function getRssData($val, $clubSlug)
    {
        $data = [];
        $feed = \Feeds::make($val->rss_url, true);

        try {
            if ($feed->error != null) {
                throw new Exception('Url not found');
            }
            if ($val->last_inserted_data) {
                if (count($feed->get_items()) - $val->last_inserted_data > 0) {
                    $data = $feed->get_items(0, count($feed->get_items(0)) - $val->last_inserted_data);
                }
            } else {
                $data = $feed->get_items(0);
            }
            $data['last_inserted_data'] = count($feed->get_items());
            $data['type'] = $val->type;

            return $data;
        } catch (Exception $exception) {
            $this->sendMail($val->id, $clubSlug->slug, 'RSS', $exception->getMessage());
        }
    }

    /**
     * get tweets.
     *
     * @return
     */
    public function getTwitterData($val, $clubSlug)
    {
        $data = [];

        try {
            Twitter::reconfig([
                'consumer_key'     => $val->api_key,
                'consumer_secret'  => $val->api_secret_key,
                'token'            => $val->api_token,
                'secret'           => $val->api_token_secret_key,
            ]);
            $val->last_inserted_data ? $data = Twitter::getUserTimeline(['since_id'=> $val->last_inserted_data, 'format' => 'array']) : $data = Twitter::getUserTimeline(['format' => 'array']);
            $data['type'] = $val->type;

            return $data;
        } catch (Exception $exception) {
            $this->sendMail($val->id, $clubSlug->slug, 'Twitter', $exception->getMessage());
        }
    }

    /**
     * get instagram post.
     *
     * @return
     */
    public function getInstagramData($val, $clubSlug)
    {
        $data = [];

        try {
            $path = config('fanslive.FEED_URL.instagram');
            $countInstaFeed = $val->last_inserted_data;
            $parameters = ['fields' =>'permalink,thumbnail_url,media_type,id,username,media,caption,media_url,timestamp','access_token' => $val->api_token];

            $instagram = $this->getInstafeeds($path,$countInstaFeed,$parameters);
            $data = $instagram['data'];
            $data['last_inserted_data'] = $val->last_inserted_data;
            $data['type'] = $val->type;

            return $data;
        } catch (Exception $exception) {
            $this->sendMail($val->id, $clubSlug->slug, 'instagram');
        }
    }

    public function getInstafeeds($path,$countInstaFeed,$parameters)
    {
        $response = Http::get($path,$parameters);
        return $response->json();
    }

    /**
     * get facebook data.
     *
     * @return
     */
    public function getFacebookData($val, $clubSlug)
    {
        $data = [];
        $i = $p = 0;
        $fb = new \Facebook\Facebook([
            'app_id'     => $val->api_app_id,
            'app_secret' => $val->api_secret_key,
        ]);

        try {
            $response = $val->last_inserted_data ? $fb->sendRequest('GET', '/me/feed?limit=50&since='.$val->last_inserted_data.'', ['fields' => 'message,type,attachments,created_time,permalink_url'], $val->api_token, 'eTag', 'v2.10') : $fb->sendRequest('GET', '/me/feed?limit=50', ['fields' => 'message,type,attachments,created_time,permalink_url'], $val->api_token, 'eTag', 'v2.10');
            if (count($response->getDecodedBody()['data']) > 0) {
                $sinceId = explode('&', $response->getDecodedBody()['paging']['previous']);
                $sinceId = explode('=', $sinceId['2']);
                $graphEdge[0] = $response->getGraphEdge();
                $data = $this->getDetailFb($graphEdge[0]);
                do {
                    $p++;
                    $graphEdge[$p] = $fb->next($graphEdge[$i]);
                    if ($graphEdge[$p] == null) {
                        break;
                    } else {
                        $data = array_merge($data, $this->getDetailFb($graphEdge[$p], count($data)));
                        $i++;
                    }
                } while ($fb->next($graphEdge[$p]) != null);

                $data['last_inserted_data'] = $sinceId['1'];
            }
            $data['type'] = $val->type;

            return $data;
        } catch (Exception $exception) {
            $this->sendMail($val->id, $clubSlug->slug, 'facebook');
        }
    }

    /**
     * Handle logic to update a given feed.
     *
     * @param $user
     * @param $contentFeed
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $feedItem, $data)
    {
        $feedItemToUpdate = $this->feedItemRepository->update($user, $feedItem, $data);

        return $feedItemToUpdate;
    }
}
