<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Timezonelist;
/**
 * @group Project Configuration
 *
 * APIs for project configurations.
 */
class ProjectConfigurationController extends Controller
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
	 * Get a project configurations.
	 */
	public function getProjectConfigurations()
	{
		return response()->json([
			'consumer_android_app_version' => config('fanslive.APP_VERSION.consumer.android'),
			'consumer_ios_app_version' => config('fanslive.APP_VERSION.consumer.ios'),
			'staff_android_app_version' => config('fanslive.APP_VERSION.staff.android'),
			'staff_ios_app_version' => config('fanslive.APP_VERSION.staff.ios'),
			'enable_testfairy_consumer_android' => config('fanslive.TESTFAIRY.consumer.android.enable_testfairy') == 1 ? true : false,
			'enable_testfairy_video_capture_consumer_android' => config('fanslive.TESTFAIRY.consumer.android.enable_testfairy_video') == 1 ? true : false,
			'enable_testfairy_feedback_consumer_android' => config('fanslive.TESTFAIRY.consumer.android.enable_testfairy_feedback') == 1 ? true : false,
			'enable_testfairy_consumer_ios' => config('fanslive.TESTFAIRY.consumer.ios.enable_testfairy') == 1 ? true : false,
			'enable_testfairy_video_capture_consumer_ios' => config('fanslive.TESTFAIRY.consumer.ios.enable_testfairy_video') == 1 ? true : false,
			'enable_testfairy_feedback_consumer_ios' => config('fanslive.TESTFAIRY.consumer.ios.enable_testfairy_feedback') == 1 ? true : false,
			'enable_testfairy_staff_android' => config('fanslive.TESTFAIRY.staff.android.enable_testfairy') == 1 ? true : false,
			'enable_testfairy_video_capture_staff_android' => config('fanslive.TESTFAIRY.staff.android.enable_testfairy_video') == 1 ? true : false,
			'enable_testfairy_feedback_staff_android' => config('fanslive.TESTFAIRY.staff.android.enable_testfairy_feedback') == 1 ? true : false,
			'enable_testfairy_staff_ios' => config('fanslive.TESTFAIRY.staff.ios.enable_testfairy') == 1 ? true : false,
			'enable_testfairy_video_capture_staff_ios' => config('fanslive.TESTFAIRY.staff.ios.enable_testfairy_video') == 1 ? true : false,
			'enable_testfairy_feedback_staff_ios' => config('fanslive.TESTFAIRY.staff.ios.enable_testfairy_feedback') == 1 ? true : false,
			'team_request_to_email' => config('fanslive.TEAM_REQUEST_TO_EMAIL'),
			'league_request_to_email' => config('fanslive.LEAGUE_REQUEST_TO_EMAIL')
		]);
	}

	/**
	 * Get Time Zone
	 */
	public function getTimeZones()
	{
		$timezones = Timezonelist::toArray();
		$allTimezones = [];
		foreach($timezones as $timezoneCategory=>$timezones) {
			$allTimezones[$timezoneCategory] = [];
			foreach($timezones as $key=>$location) {
				$allTimezones[$timezoneCategory][] = [
					'key' => $key,
					'value' => $location
				];
			}
		}
		return  response()->json([
			'data' => $allTimezones,
		]);
	}
}
