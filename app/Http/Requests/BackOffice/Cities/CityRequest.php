<?php

namespace App\Http\Requests\BackOffice\Cities;


use App\Enums\TradeName;
use App\Models\Cities\City;
use App\Models\Permissions\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CityRequest extends FormRequest
{
    use ApiResponser;

    private $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'trade_name' => 'required|in:' . implode(',', TradeName::ALL_VALUES),
            'name'       => 'required|string|max:30|alpha_spaces'
        ];
    }

    public function withValidator($validator)
    {
        if(!$validator->fails())
        {
            $this->checkUniqueNameByTradeName();
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }

    private function checkUniqueNameByTradeName()
    {
        $data = null;

        if ($this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH') {

            $data = City::where('name', trim($this->name))
                ->where('trade_name', $this->trade_name)
                ->where('id', '<>' ,$this->route('city')->id)
                ->first();

        } else if ($this->getMethod() === 'POST') {

            $data = City::where('name', trim($this->name))
                ->where('trade_name', $this->trade_name)
                ->first();
        }

        if($data != null)
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => __('app.cities.unique_name_by_trade_name', ['trade_name' => TradeName::getNameByTrade($this->trade_name)])], 422)
            );


    }
}

