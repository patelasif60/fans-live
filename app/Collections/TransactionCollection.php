<?php
namespace App\Collections;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;

class TransactionCollection extends Collection
{
	/**
     * Get the total gross amount for the transaction group.
     *
     * @return float
     */
    public function getTotalGross()
    {
        return $this->sum('price');
    }

	/**
     * Get the total owed amount for the transaction group.
     *
     * @return float
     */
    public function getTotalOwed()
    {
        return $this->getTotalNet() - (float) get_site_setting('bank_fee');
    }
}