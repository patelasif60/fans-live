<?php

namespace App\Services;

use App\Models\MembershipPackage;
use App\Repositories\MembershipPackageRepository;
use App\Services\ACIWorldWide\Client;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Storage;
use JWTAuth;
use App\Services\PaymentIQ\FrontService;
use Image;
use QrCode;

/**
 * User class to handle operator interactions.
 */
class MembershipPackageService
{
    /**
     * The pricing band repository instance.
     *
     * @var membershipPackageRepository
     */
    private $membershipPackageRepository;

    /**
     * @var predefined icon path
     */
    protected $iconPath;

    /**
     * Create a ACIWorldWide client API variable.
     *
     * @return void
     */
    protected $apiclient;

    /**
     * Create a new service instance.
     *
     * @param MembershipPackageRepository $membershipPackageRepository
     */

    /**
     * Create a QRcode path  variable.
     *
     * @return void
     */
    protected $consumerMembershipPackageQrcodePath;

    public function __construct(Client $apiclient, MembershipPackageRepository $membershipPackageRepository,FrontService $frontService)
    {
        $this->apiclient = $apiclient;
        $this->membershipPackageRepository = $membershipPackageRepository;
        $this->iconPath = config('fanslive.IMAGEPATH.membership_package_icon');
        $this->frontService = $frontService;
        $this->consumerMembershipPackageQrcodePath = config('fanslive.IMAGEPATH.consumer_membership_package_qrcode');
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->membershipPackageRepository);
        unset($this->iconPath);
        unset($this->apiclient);
    }

    /**
     * Handle logic to create a membership package.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        if (isset($data['icon'])) {
            $icon = uploadImageToS3($data['icon'], $this->iconPath);
            $data['icon'] = $icon['url'];
            $data['icon_file_name'] = $icon['file_name'];
        } else {
            $data['icon'] = null;
            $data['icon_file_name'] = null;
        }

        $membershipPackage = $this->membershipPackageRepository->create($clubId, $user, $data);

        return $membershipPackage;
    }

    /**
     * Handle logic to update a given membership package.
     *
     * @param $user
     * @param $membershipPackage
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $membershipPackage, $data)
    {
        if (isset($data['icon'])) {
            $existingIcon = $this->iconPath.$membershipPackage->icon_file_name;
            $disk = Storage::disk('s3');
            $disk->delete($existingIcon);

            $icon = uploadImageToS3($data['icon'], $this->iconPath);
            $data['icon'] = $icon['url'];
            $data['icon_file_name'] = $icon['file_name'];
        } else {
            $data['icon'] = $membershipPackage->icon;
            $data['icon_file_name'] = $membershipPackage->icon_file_name;
        }
        $membershipPackageToUpdate = $this->membershipPackageRepository->update($user, $membershipPackage, $data);

        return $membershipPackageToUpdate;
    }

    /**
     * Get membership package user data.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $membershipPackageData = $this->membershipPackageRepository->getData($clubId, $data);

        return $membershipPackageData;
    }

    /**
     * Handle logic to delete a given icon file.
     *
     * @param $membershipPackage
     *
     * @return mixed
     */
    public function deleteIcon($membershipPackage)
    {
        $disk = Storage::disk('s3');
        $icon = $this->iconPath.$membershipPackage->icon_file_name;

        return $disk->delete($icon);
    }

    /**
     * Prepare checkout
     * Prepare checkout for membership package purchase.
     *
     * @param $consumerCard
     * @param $membershipPackage
     *
     * @return mixed
     * @return \Illuminate\Http\Response
     */
    public function prepareCheckoutForMembershipPackagePurchase($consumerCard, $membershipPackage, $currency)
    {
        $errorFlag = false;
        return ['response' => ['id' => 1], 'errorFlag' => $errorFlag];

        $params = [
            'amount'              => $membershipPackage->price,
            'currency'            => $currency,
            'shopperResultUrl'    => 'app://'.config('app.domain').'/url',
            'notificationUrl'     => config('app.url').'/api/registration_notification',
            'paymentType'         => 'DB',
            'registrations[0].id' => $consumerCard->token,
        ];

        try {
            $response = $this->apiclient->createCheckout($params);
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            $errorFlag = true;
            $response = response()->json(['error' => 'Bad request'], 400);
        }

        return ['response' => $response, 'errorFlag' => $errorFlag];
    }

    /**
     * Handle logic to create a membership package purchase details.
     *
     * @param $consumerCard
     * @param $membershipPackage
     * @param $consumer
     *
     * @return mixed
     */
    public function createConsumerMembershipPackagePurchase($membershipPackage, $consumer, $data)
    {
        $transactionData['club_id'] = $consumer->club_id;
        $transactionData['membership_package_id'] = $membershipPackage->id;
        $transactionData['consumer_id'] = $consumer->id;
        $transactionData['duration'] = $membershipPackage->membership_duration;
        $transactionData['vat_rate'] = $membershipPackage->vat_rate;
        $transactionData['price'] = $membershipPackage->price;
        $transactionData['currency'] = $consumer->club->currency;
        $transactionData['transaction_reference_id'] = $data['txId'];
        $transactionData['card_details'] = $data['cardDetails'];
        $transactionData['payment_status'] = 'Unpaid';
        $transactionData['custom_parameters'] = json_encode($data['custom_parameters']);
        $consumerMembershipPackage = $this->membershipPackageRepository->createConsumerMembershipPackagePurchase($transactionData);

        return $consumerMembershipPackage;
    }

    /**
     * Membership package payment
     * Check payment status and response of membership package.
     *
     * @param $consumerCard
     * @param $membershipPackage
     *
     * @return mixed
     * @return \Illuminate\Http\Response
     */
    public function membershipPackagePurchasePayment($checkoutId)
    {
        $errorFlag = false;
        return ['response' => ['id' => 1], 'errorFlag' => $errorFlag];

        try {
            $response = $this->apiclient->getPaymentStatus($checkoutId);
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            $errorFlag = true;
            $response = response()->json(['error' => 'Bad request'], 400);
        }

        return ['response' => $response, 'errorFlag' => $errorFlag];
    }

    /**
     * Handle logic to update a membership package purchase details.
     *
     * @param $consumerCard
     * @param $membershipPackage
     * @param $consumerId
     *
     * @return mixed
     */
    public function updateConsumerMembershipPackagePurchase($data,$membershipPackage,$consumer=null)
    {
        $transactionData['status'] = $data['transaction_summary']['data']['status'];
        $transactionData['psp_reference_id'] = $data['transaction_summary']['data']['payload']['pspReferenceId'];
        $transactionData['payment_method'] = isset($data['transaction_summary']['data']['payload']['method']) ? $data['transaction_summary']['data']['payload']['method'] : null;

        $transactionData['status_code'] = isset($data['transaction_summary']['data']['payload']['status_code']) ? $data['transaction_summary']['data']['payload']['status_code'] : null;

        $transactionData['psp'] = $data['transaction_summary']['data']['payload']['psp'];

        $transactionData['psp_account'] = isset($data['transaction_summary']['data']['payload']['psp_account'] ) ? $data['transaction_summary']['data']['payload']['psp_account'] : null;
        $transactionData['transaction_timestamp'] =Carbon::now()->format('Y-m-d H:i:s');
        $transactionData['is_active'] = $data['transaction_summary']['data']['status'] === 'successful' ? 1 : 0;
        $membershipPackageFlag = $this->membershipPackageRepository->getMembershipTransactionData($data['transaction_summary']['data']['payload']['txRefId']);
        if($membershipPackageFlag)
        {
            $consumerMembershipPackage = $this->membershipPackageRepository->updateConsumerMembershipPackagePurchase($transactionData, $membershipPackageFlag);
        }
        else
        {
            $$consumerMembershipPackage = $this->createConsumerMembershipPackagePurchase($membershipPackage, $consumer, $data);
        }
        $image =   (string) Image::make(QrCode::format('png')->size(300)->generate(json_encode(['consumer_membership_package_id' => $consumerMembershipPackage->id, 'consumer_id' => $consumer->id])))->encode('data-url');
        $qrcodeImage = uploadQRCodeToS3($image, $this->consumerMembershipPackageQrcodePath,$consumerMembershipPackage->id);


         return $consumerMembershipPackage;
    }

    /**
     * Handle logic to update a receipt number of consumer membership package.
     *
     * @param $consumer
     * @param $consumerMembershipPackage
     *
     * @return mixed
     */
    public function updateReceiptNumberOfConsumerMembershipPackage($consumer, $consumerMembershipPackage)
    {
        $consumerMembershipPackage->receipt_number = '#MP'.sprintf('%04s', $consumer->club_id).sprintf('%04s', $consumerMembershipPackage->id);
        $consumerMembershipPackage->save();
    }

    /**
     * Handle logic to deactive all consumers membership packages.
     *
     * @param $clubId
     * @param $consumerId
     *
     * @return mixed
     */
    public function deactiveAllConsumersMembershipPackages($clubId, $consumerId)
    {
        return $this->membershipPackageRepository->deactiveAllConsumersMembershipPackages($clubId, $consumerId);
    }

    /**
     * Handle logic to active consumer membership packages.
     *
     * @param $clubId
     * @param $consumerId
     *
     * @return mixed
     */
    public function activateConsumerMembershipPackage($consumerMembershipPackageId)
    {
        return $this->membershipPackageRepository->activateConsumerMembershipPackage($consumerMembershipPackageId);
    }

    public function getMembershipPackageForCurrentClub($club)
    {
        return $this->membershipPackageRepository->getMembershipPackageForCurrentClub($club);
    }

    /**
     * Handle logic to get membership packages.
     *
     * @param $consumerId
     * @param $type
     *
     * @return mixed
     */
    public function getMembershipPackages($consumerId)
    {
        return $this->membershipPackageRepository->getMembershipPackages($consumerId);
    }

    /**
     * Handle logic to get membership package.
     *
     * @param $clubId
     *
     * @return mixed
     */
    public function getMembershipPackageList($clubId)
    {
        return $this->membershipPackageRepository->getMembershipPackageList($clubId);
    }
     /**
     * Get user payment account.
     *
     *
     */
    public function getUserPaymentAccounts($user)
    {
        $params = [
            'sessionId' => 123456,
        ];
        return $this->frontService->getUserPaymentAccounts($user->id, $params);
    }
     /**
     * Delete user payment account.
     *
     *
     */
    public function deleteUserPaymentAccounts($user, $data)
    {
        $params = [
            'sessionId' => 1234567,
        ];
        return $this->frontService->deleteUserPaymentAccounts($user->id, $data['account_id'], $params);
    }
    /**
    * validate membership package payment
    */
    public function validateMembershipPackagePayment($data)
    {
        $membershipPackage = $this->membershipPackageObj($data['membership_package_id']);
        $price = $membershipPackage->price + (($membershipPackage->price * $membershipPackage->vat_rate) / 100);
        if ($price != $data['final_amount']) {
            return response()->json([
                'message' => 'Something went wrong!'
            ], 400);
        }

        return response()->json([
        	'message' => 'Cart is validated successfully.',
        ]);
    }
    /**
    * authorised membership package payment
    */
    public function authoriseMembershipPackagePayment($data)
    {
        return [
                'status' =>'success',
                'code'=>200,
            ];
    }
    /**
    **/
    public function membershipPackageObj($membershipPackageId)
    {
        return MembershipPackage::find($membershipPackageId);
    }
    /**
     * Handle logic upload QR code of booked events.
     *
     * @param $eventTransactonId
     * @param $data
     *
     * @return mixed
     */
    public function uploadQRcode()
    {
        $membershipTransaction = $this->membershipPackageRepository->getAllMembershipTransactionData();
        foreach($membershipTransaction as $consumerMembershipPackage)
        {
            $image =   (string) Image::make(QrCode::format('png')->size(300)->generate(json_encode(['consumer_membership_package_id' => $consumerMembershipPackage->id, 'consumer_id' => $consumerMembershipPackage->consumer_id])))->encode('data-url');
            $qrcodeImage = uploadQRCodeToS3($image, $this->consumerMembershipPackageQrcodePath,$consumerMembershipPackage->id);
        }
    }
}
