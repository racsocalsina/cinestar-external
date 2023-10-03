<?php


namespace App\Http\Controllers\API\ContentManagements;


use App\Enums\ContentManagementCodeKey;
use App\Helpers\Helper;
use App\Http\Resources\Awards\AwardInfoResource;
use App\Models\ChocoAwards\ChocoAward;
use App\Models\ContentManagements\Interfaces\ContentManagementRepositoryInterface;
use App\Models\TicketAwards\TicketAward;
use App\Traits\ApiResponser;

class ContentManagementController
{
    use ApiResponser;

    private ContentManagementRepositoryInterface $contentManagementRepository;

    public function __construct(ContentManagementRepositoryInterface $contentManagementRepository)
    {
        $this->contentManagementRepository = $contentManagementRepository;
    }

    public function showPartnerData()
    {
        // data fron content management
        $data = $this->contentManagementRepository->get(ContentManagementCodeKey::PARTNER, Helper::getTradeNameHeader(), true);

        // ticket awards
        $ticketAwards = AwardInfoResource::collection(TicketAward::orderBy('points')->get());
        $ticketAwards = ['ticket_awards' => $ticketAwards->all()];

        // choco awards
        $chocoAwards = AwardInfoResource::collection(ChocoAward::orderBy('points')->get());
        $chocoAwards = ['choco_awards' => $chocoAwards->all()];

        $return = array_merge($data, $ticketAwards, $chocoAwards);
        return $this->success($return);
    }

    public function showCorporateData()
    {
        $data = $this->contentManagementRepository->get(ContentManagementCodeKey::CORPORATE, Helper::getTradeNameHeader(), true);
        return $this->success($data);
    }

    public function showAboutData()
    {
        $data = $this->contentManagementRepository->get(ContentManagementCodeKey::ABOUT, Helper::getTradeNameHeader(), true);
        return $this->success($data);
    }

    public function showTermData()
    {
        $data = $this->contentManagementRepository->get(ContentManagementCodeKey::TERMS, Helper::getTradeNameHeader(), true);
        return $this->success($data);
    }

    public function showPopupBannerData()
    {
        $data = $this->contentManagementRepository->get(ContentManagementCodeKey::POPUP_BANNER, Helper::getTradeNameHeader(), true);
        return $this->success($data);
    }
}