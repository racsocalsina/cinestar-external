<?php


namespace App\Models\ContentManagements\Interfaces;


interface ContentManagementRepositoryInterface
{
    public function get($keyCode, $tradeName, $returnValue = false);
    public function updatePartner($request);
    public function updateCorporate($request);
    public function updateAbout($request);
    public function updateTerm($request);
    public function updatePopupBanner($request);
}
