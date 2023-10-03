<?php


namespace App\Rules\ContentManagement;


use App\Enums\ActionType;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ContentManagementCheckPartnerItems implements Rule
{
    private $errorMessage;

    public function passes($attribute, $value)
    {
        $actions = ActionType::ALL_VALUES;

        foreach($value as $item) {

            // Action
            if (!isset($item['action'])) {
                $this->errorMessage = "Tipo de acción es es requerido";
                return false;
            }

            if (!in_array($item['action'], $actions)) {
                $this->errorMessage = "Tipo de acción no es válido";
                return false;
            }

            // Id
            if ($item['action'] == ActionType::UPDATE) {
                if (!isset($item['id'])) {
                    $this->errorMessage = "Id es requerido";
                    return false;
                }
            } else if ($item['action'] == ActionType::DELETE) {
                if (!isset($item['id'])) {
                    $this->errorMessage = "Id es requerido";
                    return false;
                }
            }

            // Title
            if ($item['action'] == ActionType::CREATE || $item['action'] == ActionType::UPDATE) {
                if (!isset($item['title'])) {
                    $this->errorMessage = "Titulo es requerido";
                    return false;
                }
            }

            // Image
            if ($item['action'] == ActionType::CREATE) {
                if (!isset($item['image'])) {
                    $this->errorMessage = "Imagen para el beneficio es requerido";
                    return false;
                }

                $validator = Validator::make([
                    'image' => $item['image']
                ], [
                    'image' => 'image|mimes:jpg,jpeg,png,gif|max:3000',
                ]);

                if ($validator->fails()) {
                    $this->errorMessage = $validator->messages()->first();
                    return false;
                }
            } else if ($item['action'] == ActionType::UPDATE) {

                if (isset($item['image'])) {

                    $validator = Validator::make([
                        'image' => $item['image']
                    ], [
                        'image' => 'image|mimes:jpg,jpeg,png,gif|max:3000',
                    ]);

                    if ($validator->fails()) {
                        $this->errorMessage = $validator->messages()->first();
                        return false;
                    }
                }
            }
        }

        return true;
    }

    public function message()
    {
        return $this->errorMessage;
    }
}
