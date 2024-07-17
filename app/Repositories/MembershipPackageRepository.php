<?php

namespace App\Repositories;

use App\Models\ConsumerMembershipPackage;
use App\Models\MembershipPackage;
use DB;

/**
 * Repository class for membership package model.
 */
class MembershipPackageRepository extends BaseRepository
{
    /**
     * Handle logic to create a new membership package.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        $membershipPackage = MembershipPackage::create([
            'club_id'                     => $clubId,
            'title'                       => $data['title'],
            'benefits'                    => $data['benefits'],
            'membership_duration'         => $data['membership_duration'],
            'rewards_percentage_override' => $data['rewards_percentage_override'],
            'price'                       => $data['price'],
            'vat_rate'                    => $data['vat_rate'],
            'icon'                        => $data['icon'],
            'icon_file_name'              => $data['icon_file_name'],
            'status'                      => $data['status'],
            'created_by'                  => $user->id,
            'updated_by'                  => $user->id,
        ]);

        return $membershipPackage;
    }

    /**
     * Handle logic to update a membership package.
     *
     * @param $user
     * @param $membershipPackage
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $membershipPackage, $data)
    {
        $membershipPackage->fill([
            'title'                       => $data['title'],
            'benefits'                    => $data['benefits'],
            'membership_duration'         => $data['membership_duration'],
            'rewards_percentage_override' => $data['rewards_percentage_override'],
            'price'                       => $data['price'],
            'vat_rate'                    => $data['vat_rate'],
            'icon'                        => $data['icon'],
            'icon_file_name'              => $data['icon_file_name'],
            'status'                      => $data['status'],
            'created_by'                  => $user->id,
            'updated_by'                  => $user->id,
        ]);
        $membershipPackage->save();

        return $membershipPackage;
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
        $membershipPackageData = DB::table('membership_packages')->where('club_id', $clubId);

        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'membership_packages.id';
            $sorttype = 'desc';
        }
        $membershipPackageData = $membershipPackageData->orderBy($sortby, $sorttype);

        $membershipPackageListData = [];

        if (!array_key_exists('pagination', $data)) {
            $membershipPackageData = $membershipPackageData->paginate($data['pagination_length']);
            $membershipPackageListData = $membershipPackageData;
        } else {
            $membershipPackageListData['total'] = $membershipPackageData->count();
            $membershipPackageListData['data'] = $membershipPackageData->get();
        }

        $response = $membershipPackageListData;

        return $response;
    }

    /**
     * Handle logic to create a new consumer membership package purchase.
     *
     * @param $data
     * @param $consumer
     */
    public function createConsumerMembershipPackagePurchase($data)
    {
        $consumerMembershipPackage = ConsumerMembershipPackage::create([
            'club_id'               => $data['club_id'],
            'membership_package_id' => $data['membership_package_id'],
            'consumer_id'           => $data['consumer_id'],
            'duration'              => $data['duration'],
            'vat_rate'              => $data['vat_rate'],
            'price'                 => $data['price'],
            'currency'              => $data['currency'],
            'transaction_reference_id' => $data['transaction_reference_id'],
            'card_details'			=> json_encode($data['card_details']),
            'payment_status'		=> $data['payment_status'],
            'custom_parameters'		=> $data['custom_parameters'],
        ]);
        return $consumerMembershipPackage;
    }

    /**
     * Handle logic to create a new membership package.
     *
     * @param $data
     * @param $consumer
     */
    public function updateConsumerMembershipPackagePurchase($data, $consumerMembershipPackage)
    {
        $consumerMembershipPackage->status = $data['status'];
        $consumerMembershipPackage->psp_reference_id = $data['psp_reference_id'];
        $consumerMembershipPackage->payment_method = $data['payment_method'];
        $consumerMembershipPackage->psp = $data['psp'];
        $consumerMembershipPackage->status_code = $data['status_code'];
        $consumerMembershipPackage->psp_account = $data['psp_account'];
        $consumerMembershipPackage->transaction_timestamp = $data['transaction_timestamp'];
        $consumerMembershipPackage->is_active = $data['is_active'];
        $consumerMembershipPackage->save();
        return $consumerMembershipPackage;
    }

    /**
     * Handle logic to deactive all consumers membership packages.
     *
     * @param $clubId
     * @param $consumerId
     */
    public function deactiveAllConsumersMembershipPackages($clubId, $consumerId)
    {
        return ConsumerMembershipPackage::where('club_id', $clubId)->where('consumer_id', $consumerId)->update([
            'is_active' => 0,
        ]);
    }

    /**
     * Handle logic to active consumer membership packages.
     *
     * @param $clubId
     * @param $consumerId
     */
    public function activateConsumerMembershipPackage($consumerMembershipPackageId)
    {
        return ConsumerMembershipPackage::where('id', $consumerMembershipPackageId)->update([
            'is_active' => 1,
        ]);
    }

    public function getMembershipPackageForCurrentClub($club)
    {
        return MembershipPackage::where('club_id',$club)->orWhereNull('club_id')->get();
    }

    /**
     * Get membership packages.
     *
     * @param $consumerId
     * @param $type
     *
     * @return mixed
     */
    public function getMembershipPackages($consumerId)
    {
        return ConsumerMembershipPackage::where('consumer_id', $consumerId)->orderBy('id', 'desc')->get();
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
        $membershipPackageList = MembershipPackage::where('club_id', $clubId)->orWhere('club_id', NULL)->get()->pluck('title', 'id');

        return $membershipPackageList;
    }
    /**
     * Handle logic to get membership transaction data.
     * @param transactionReferenceId
     *
     * @return mixed
     */
    public function getMembershipTransactionData($transactionReferenceId)
    {
        return ConsumerMembershipPackage::where('transaction_reference_id',$transactionReferenceId)->get()->first();
    }
    /**
    * Get all Data
    */
    public function getAllMembershipTransactionData()
    {
        return ConsumerMembershipPackage::all();
    }
}
