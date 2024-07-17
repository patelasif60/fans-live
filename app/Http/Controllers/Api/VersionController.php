<?php

namespace App\Http\Controllers\Api;

/**
 * @group App version
 *
 * APIs for app version.
 */
class VersionController extends BaseController
{
    /**
     * Get an app version.
     */
    public function getAppVersion()
    {
        return response()->json([
            'android_app_version' => config('fanslive.APP_VERSION.android'),
            'ios_app_version'     => config('fanslive.APP_VERSION.ios'),
        ]);
    }
}
