<?php

namespace App\Http\Resources\BackOffice\Banners;

use App\Helpers\FunctionHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'link'         => $this->link,
            'path'         => $this->path,
            'type'         => $this->type,
            'type_name'    => FunctionHelper::getBannerNameByType($this->type),
            'trade_name'   => $this->trade_name,
            'download_app' => $this->download_app == true,
            'page'         => $this->page,
            'page_text'    => $this->page ? __('others.pages.' . $this->page) : null
        ];
    }
}
