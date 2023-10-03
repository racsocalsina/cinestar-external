<?php


namespace App\Http\Requests\Consumer\TicketPromotion;


use App\Enums\GlobalEnum;
use Illuminate\Foundation\Http\FormRequest;

class TicketPromotionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $required_rule = 'required_unless:action,=,' . GlobalEnum::ACTION_SYNC_DELETE;
        return [
            'action'                  => 'required',
            'url'                     => 'required',
            'data.code'         => 'required',
            'data.name'               => $required_rule,
            'data.product_code'       => 'nullable',
            'data.tickets_number'       => $required_rule,
            'data.is_block_3d'        => $required_rule,
            'data.is_block_1s'        => $required_rule,
            'data.start_date'         => $required_rule,
            'data.end_date'    => $required_rule,
            'data.is_block_sunday'    => $required_rule,
            'data.is_block_monday'    => $required_rule,
            'data.is_block_tuesday'   => $required_rule,
            'data.is_block_wednesday' => $required_rule,
            'data.is_block_thursday'  => $required_rule,
            'data.is_block_friday'    => $required_rule,
            'data.is_block_saturday'  => $required_rule,
        ];
    }
}
