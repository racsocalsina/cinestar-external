<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class UbigeoCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'provinces' => $this->provinces->transform(static function ($province) {
                return [
                    'id'        => $province->id,
                    'name'      => $province->name,
                    'districts' => $province->districts->transform(static function ($district) {
                        return [
                            'id'   => $district->id,
                            'name' => $district->name,
                        ];
                    })
                ];
            })
        ];
    }

}
