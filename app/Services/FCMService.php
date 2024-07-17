<?php

namespace App\Services;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

/**
 * FCM service to handle operator firebase notification.
 */
class FCMService
{
	/**
	 * Send notification to device
	 *
	 * @param $tokens
	 * @param $title
	 * @param $body
	 * @param $params
	 * @return successfull receive devices notification number
	 */
	public function send($tokens = [], $title=NULL, $body=NULL, $params = [])
	{
		$optionBuilder = new OptionsBuilder();
		$optionBuilder->setTimeToLive(60*20);
		$optionBuilder->setPriority('high');
        $optionBuilder->setContentAvailable(true);
		$notificationBuilder = new PayloadNotificationBuilder(env('APP_NAME'));
		$notificationBuilder->setBody($body)
							->setTitle($title)
						    ->setSound('default');
		$dataBuilder = new PayloadDataBuilder();
		$dataBuilder->addData($params);
		$option = $optionBuilder->build();
		$notification = $notificationBuilder->build();
		$data = $dataBuilder->build();
		$downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
		return [
			'number_of_success' 	 => $downstreamResponse->numberSuccess(),
			'number_of_failure' 	 => $downstreamResponse->numberFailure(),
			'number_of_modification' => $downstreamResponse->numberModification(),
			'tokens_to_delete' 		 => $downstreamResponse->tokensToDelete(),
			'tokens_to_modify' 		 => $downstreamResponse->tokensToModify(),
			'tokens_to_retry' 		 => $downstreamResponse->tokensToRetry(),
			'tokens_with_errors'     => $downstreamResponse->tokensWithError(),
		];
	}
}