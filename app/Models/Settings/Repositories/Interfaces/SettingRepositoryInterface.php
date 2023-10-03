<?php


namespace App\Models\Settings\Repositories\Interfaces;


interface SettingRepositoryInterface
{
    public function billboardDates();
    public function getCommunitySystemVars($headquarterId);
    public function billboardDatesNextReleases($id_movie);
    public function paymentGatewayResponse();
    public function getSystemConfiguration();
    public function saveSystemConfiguration($body);
    public function getErpSystemVars();
    public function sync($body);
}
