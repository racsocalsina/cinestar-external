<?php

namespace App\Observers;

use App\Models\Customers\Customer;
use App\Models\UsersPartners\Repositories\Interfaces\UserPartnerRepositoryInterface;

class CustomerObserver
{
    private UserPartnerRepositoryInterface $userPartnerRepository;

    public function __construct(UserPartnerRepositoryInterface $userPartnerRepository)
    {
        $this->userPartnerRepository = $userPartnerRepository;
    }

    /**
     * Handle the Customer "created" event.
     *
     * @param \App\Models\Customer $customer
     * @return void
     */
    public function created(Customer $customer)
    {
        $this->userPartnerRepository->createFromExternal($customer);
    }

    /**
     * Handle the Customer "updated" event.
     *
     * @param \App\Models\Customer $customer
     * @return void
     */
    public function updated(Customer $customer)
    {

    }

    /**
     * Handle the Customer "deleted" event.
     *
     * @param \App\Models\Customer $customer
     * @return void
     */
    public function deleted(Customer $customer)
    {
        //
    }

    /**
     * Handle the Customer "restored" event.
     *
     * @param \App\Models\Customer $customer
     * @return void
     */
    public function restored(Customer $customer)
    {
        //
    }

    /**
     * Handle the Customer "force deleted" event.
     *
     * @param \App\Models\Customer $customer
     * @return void
     */
    public function forceDeleted(Customer $customer)
    {
        //
    }
}
