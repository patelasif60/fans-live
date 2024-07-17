<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Repositories\TravelOfferRepository;
use App\Repositories\PushNotificationRepository;
use App\Repositories\ConsumerRepository;
use App\Repositories\UserRepository;

class PushNotificationService
{
    /**
     * The user repository instance.
     *
     * @var pushnotificationRepository
     */
    private $pushnotificationRepository;

    /**
     * The user repository instance.
     *
     * @var categoryRepository
     */
    private $categoryRepository;

    /**
     * The user repository instance.
     *
     * @var travelOfferRepository
     */
    private $travelOfferRepository;

    /**
     * The consumer repository instance.
     *
     * @var consumerRepository
     */
    private $consumerRepository;

    /**
     * The user repository instance.
     *
     * @var userRepository
     */
    private $userRepository;

    /**
     * Create a new service instance.
     *
     * @param PushNotificationRepository $pushnotificationRepository
     */
    public function __construct(PushNotificationRepository $pushnotificationRepository, CategoryRepository $categoryRepository, TravelOfferRepository $travelOfferRepository, ConsumerRepository $consumerRepository, UserRepository $userRepository)
    {
        $this->pushnotificationRepository = $pushnotificationRepository;
        $this->categoryRepository = $categoryRepository;
        $this->travelOfferRepository = $travelOfferRepository;
        $this->consumerRepository = $consumerRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Handle logic to create a data.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        $pushNotification = $this->pushnotificationRepository->create($clubId, $user, $data);
        return $pushNotification;
    }

    /**
     * Handle logic to get swipe action items a data.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getSwipeActionItems($clubId, $data)
    {
        $items = [];
        if ($data['swipe_action_category'] == 'merchandise_category') {
            $items = $this->categoryRepository->getSwipeActionItems($clubId, 'merchandise');
        }
        if ($data['swipe_action_category'] == 'food_and_drink_category') {
            $items = $this->categoryRepository->getSwipeActionItems($clubId, 'food_and_drink');
        }
        if ($data['swipe_action_category'] == 'travel_offer') {
            $items = $this->travelOfferRepository->getSwipeActionItems($clubId);
        }

        return $items;
    }    

    /**
     * Handle logic to update a given data.
     *
     * @param $user
     * @param $pushnotifications
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $pushnotifications, $data)
    {
        $pushNotificationsUpdate = $this->pushnotificationRepository->update($user, $pushnotifications, $data);
        return $pushNotificationsUpdate;
    }

    /**
     * Get Push Notification data.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $pushNotification = $this->pushnotificationRepository->getData($clubId, $data);
        return $pushNotification;
    }

    /**
     * Get device token array
     *
     * @param $consumerIds
     * @param $membershipPackageIds
     *
     * @return mixed
     */
    public function getDeviceTokensArr($consumerIds = [], $membershipPackageIds = [])
    {
        return $this->consumerRepository->getDeviceTokensArr($consumerIds, $membershipPackageIds);
    }

    /**
     * Handle logic to create a push notification history.
     *
     * @param $data
     *
     * @return mixed
     */
    public function createHistory($data)
    {
        $pushNotificationHistory = $this->pushnotificationRepository->createHistory($data);;
        return $pushNotificationHistory;
    }

    /**
     * Handle logic to create a push notification history consumer status.
     *
     * @param $pushNotificationHistoryId
     * @param $consumerWithDeviceTokens
     * @param $failedTokens
     *
     * @return mixed
     */
    public function createHistoryConsumerStatus($pushNotificationHistoryId, $consumerWithDeviceTokens, $failedTokens)
    {
        if ($consumerWithDeviceTokens) {
            foreach ($consumerWithDeviceTokens as $key => $value) {
                if (!empty($value) && !in_array($value, $failedTokens)) {
                    $this->pushnotificationRepository->createHistoryConsumerStatus($pushNotificationHistoryId, $key, TRUE);
                } else {
                    $this->pushnotificationRepository->createHistoryConsumerStatus($pushNotificationHistoryId, $key, FALSE);
                }
            }
        }
        return TRUE;
    }

    /**
     * Handle logic to remove device tokens.
     *
     * @param $data
     *
     * @return mixed
     */
    public function removeDeviceToken($data)
    {
        return $this->userRepository->removeDeviceToken($data);
    }

    /**
     * Handle logic to modify device tokens.
     *
     * @param $data
     *
     * @return mixed
     */
    public function modifyDeviceToken($data)
    {   
        if ($data) {
            foreach ($data as $key => $value) {
                $this->userRepository->modifyDeviceToken($key, $value);
            }
            return TRUE;
        }
        return FALSE;
    }
}
