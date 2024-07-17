<?php

namespace App\Repositories;

use DB;

class ConsumerMembershipPackageRepository extends BaseRepository
{
	/**
     * Get consumer membership package query.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getConsumerMembershipPackageQueryForTransactions()
    {
        $consumerMembershipPackageQuery = DB::table('consumer_membership_package')->select(
            'consumer_membership_package.id as id',
            'clubs.name as club',
            'clubs.time_zone as club_time_zone',
            'consumer_membership_package.consumer_id as consumer_id',
            'consumer_membership_package.club_id as club_id',
//            'consumer_membership_package.payment_type as payment_type',
            'consumer_membership_package.payment_brand as payment_brand',
            'consumer_membership_package.price as price',
            'consumer_membership_package.fee as fee',
            'consumer_membership_package.currency as currency',
            'consumer_membership_package.status as status',
            'consumer_membership_package.payment_status as payment_status',
            'consumer_membership_package.transaction_timestamp as transaction_timestamp',
            'users.email as email',
            DB::raw('"membership" as transaction_type'),
            DB::raw('ROUND(consumer_membership_package.price*(consumer_membership_package.fee/100),2) as fee_amount'),
            DB::raw('CONCAT(users.first_name," ", users.last_name) as name')
        )
        ->leftJoin('consumers', 'consumers.id', '=', 'consumer_membership_package.consumer_id')
        ->leftJoin('users', 'users.id', '=', 'consumers.user_id')
        ->leftJoin('clubs', 'clubs.id', '=', 'consumer_membership_package.club_id')
        ;
        return $consumerMembershipPackageQuery;
    }

    /**
     * Get consument membership package transactions payment brand.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getPaymentCardType()
    {
        $paymentBrands = DB::table('consumer_membership_package')->select('payment_brand')->groupBy('payment_brand')->get();
        return $paymentBrands;
    }
}
