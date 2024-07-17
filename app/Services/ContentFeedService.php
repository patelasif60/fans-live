<?php

namespace App\Services;

use Alaouy\Youtube\Facades\Youtube;
use App\Repositories\ContentFeedRepository;
use Exception;
use Twitter;
use Vinkla\Instagram\Instagram;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
/**
 * User class to handle operator interactions.
 */
class ContentFeedService
{
    /**
     * The content feed repository instance.
     *
     * @var ContentFeedRepository
     */
    private $contentFeedRepository;

    /**
     * Create a new service instance.
     *
     * @param ContentFeedRepository $contentFeedRepository
     */
    public function __construct(ContentFeedRepository $contentFeedRepository)
    {
        $this->contentFeedRepository = $contentFeedRepository;
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->contentFeedRepository);
    }

    /**
     * Handle logic to create a content feed.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        if ($data['type'] == 'Facebook') {
            $chkData = new \Facebook\Facebook([
                'app_id'     => $data['api_app_id'],
                'app_secret' => $data['api_secret_key'],
            ]);
            $fbData = $this->checkStatus($chkData, 'facebook', $data['api_token']);
            if ($fbData == false) {
                return false;
            }
            $data['screen_name'] = $fbData->getDecodedBody()['name'];
        }
        if ($data['type'] == 'Twitter') {
            $chkData = Twitter::reconfig([
                'consumer_key'     => $data['api_key'],
                'consumer_secret'  => $data['api_secret_key'],
                'token'            => $data['api_token'],
                'secret'           => $data['api_token_secret_key'],
            ]);
            $twitterData = $this->checkStatus($chkData, 'twitter');
            if ($twitterData == false) {
                return false;
            }
            $data['screen_name'] = $twitterData->screen_name;
        }
        if ($data['type'] == 'Youtube') {
            $chkData = Youtube::setApiKey($data['api_key']);
            $youtubeData = $this->checkStatus($chkData, 'youtube', $data['api_channel_id']);
            if ($youtubeData == false) {
                return false;
            }
            $data['screen_name'] = $youtubeData->snippet->title;
        }
        if ($data['type'] == 'Instagram') {
             $path = config('fanslive.FEED_URL.instagram');
            $parameters = ['fields' =>'permalink,thumbnail_url,media_type,id,username,media,caption,media_url,timestamp','access_token' =>$data['api_token']];
            $response = Http::get($path,$parameters);
            if($response->status()!=200){
                return false;
            }
            $instagram = $response->json();
            $instagramData = $instagramData['data'];
            foreach ($instagramData as $instagramDataVal) {
                $data['screen_name'] = $instagramDataVal['username'];
                break;
            }
        }

        $contentFeed = $this->contentFeedRepository->create($clubId, $user, $data);

        return $contentFeed;
    }

    /**
     * Handle logic to update a given content feed.
     *
     * @param $user
     * @param $contentFeed
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $contentFeed, $data)
    {
        if (isset($data['rss_url'])) {
            if ($contentFeed->rss_url != $data['rss_url']) {
                $data['last_inserted_data'] = null;
            } else {
                $data['last_inserted_data'] = $contentFeed->last_inserted_data;
            }
        }
        if ($data['type'] == 'Facebook') {
            $chkData = new \Facebook\Facebook([
                'app_id'     => $data['api_app_id'],
                'app_secret' => $data['api_secret_key'],
            ]);
            $fbData = $this->checkStatus($chkData, 'facebook', $data['api_token']);
            if ($fbData == false) {
                return false;
            }
            $data['screen_name'] = $fbData->getDecodedBody()['name'];
            if ($contentFeed->api_app_id != $data['api_app_id']) {
                $data['last_inserted_data'] = null;
            } else {
                $data['last_inserted_data'] = $contentFeed->last_inserted_data;
            }
        }
        if ($data['type'] == 'Twitter') {
            $chkData = Twitter::reconfig([
                'consumer_key'     => $data['api_key'],
                'consumer_secret'  => $data['api_secret_key'],
                'token'            => $data['api_token'],
                'secret'           => $data['api_token_secret_key'],
            ]);
            $twitterData = $this->checkStatus($chkData, 'twitter');
            if ($twitterData == false) {
                return false;
            }
            $data['screen_name'] = $twitterData->screen_name;
            if ($contentFeed->screen_name != $data['screen_name']) {
                $data['last_inserted_data'] = null;
            } else {
                $data['last_inserted_data'] = $contentFeed->last_inserted_data;
            }
        }
        if ($data['type'] == 'Youtube') {
            $chkData = Youtube::setApiKey($data['api_key']);
            $youtubeData = $this->checkStatus($chkData, 'youtube', $data['api_channel_id']);
            if ($youtubeData == false) {
                return false;
            }
            $data['screen_name'] = $youtubeData->snippet->title;
            if ($contentFeed->api_channel_id != $data['api_channel_id']) {
                $data['last_inserted_data'] = null;
            } else {
                $data['last_inserted_data'] = $contentFeed->last_inserted_data;
            }
        }
        if ($data['type'] == 'Instagram') {
            $path = config('fanslive.FEED_URL.instagram');
            $parameters = ['fields' =>'permalink,thumbnail_url,media_type,id,username,media,caption,media_url,timestamp','access_token' =>$data['api_token']];
            $response = Http::get($path,$parameters);
            if($response->status()!=200){
                return false;
            }
            $newUser = $data['api_app_id'];
            $oldUser = $contentFeed->api_app_id;
            if ($newUser != $oldUser) {
                $data['last_inserted_data'] = null;
            } else {
                $data['last_inserted_data'] = $contentFeed->last_inserted_data;
            }
        }
        $contentFeedToUpdate = $this->contentFeedRepository->update($user, $contentFeed, $data);

        return $contentFeedToUpdate;
    }

    /**
     * Get Content Feed data.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $contentFeed = $this->contentFeedRepository->getData($clubId, $data);

        return $contentFeed;
    }

    public function checkStatus($data, $type, $option = null)
    {
        try {
            if ($type == 'twitter') {
                $chkData = $data->getSettings();
            }
            if ($type == 'youtube') {
                $chkData = $data->getChannelById($option);
            }
            if ($type == 'facebook') {
                $chkData = $data->get('/me', $option);
            }
            if ($type == 'instagram') {
                $chkData = $data->get();
            }
        } catch (Exception $exception) {
            return false;
        }

        return $chkData;
    }
}
