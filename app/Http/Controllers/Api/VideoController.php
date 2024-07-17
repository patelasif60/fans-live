<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use App\Models\Consumer;
use App\Models\ConsumerMembershipPackage;
use App\Models\VideoMembershipPackage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Video\GetVideoRequest;
use App\Http\Resources\Video\Video as VideoResource;
use Illuminate\Http\Request;
use JWTAuth;

/**
 * @group Video
 *
 * APIs for Video.
 */
class VideoController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{

	}

	/**
	 * Get Videos
	 * Get Videos.
	 *
	 * @bodyParam club_id int required An id of a club. Example: 1
	 *
	 *
	 * @return mixed
	 */
	public function getVideos(GetVideoRequest $request)
	{
		$user = JWTAuth::user();
		$consumer = Consumer::where('user_id', $user->id)->first();
		// $consumerMembershipPackage = $consumer->getActiveMembershipPackage();

		// $videos = Video::whereHas('membershippackages', function($query) use($consumerMembershipPackage) {
		// 					$query->where('membership_package_id', config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID'));
		// 					if($consumerMembershipPackage) {
		// 						$query->orWhere('membership_package_id', $consumerMembershipPackage->id);
		// 					}
		// 				})
		// 				->where('status', 'Published')
		// 				->where('club_id', $consumer->club->id)
		// 				->orderByDesc('publication_date')->get();
		$videos = Video::where('status', 'Published')
						->where('club_id', $consumer->club->id)
						->orderByDesc('publication_date')->get();
		return VideoResource::collection($videos);
	}
}
