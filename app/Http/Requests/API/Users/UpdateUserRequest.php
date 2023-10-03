<?php


namespace App\Http\Requests\API\Users;


use App\Helpers\Helper;
use App\Models\Customers\Customer;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    use ApiResponser;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function withValidator($validator)
    {
        if (!$validator->fails()) {
            $this->checkData();
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }

    private function checkData()
    {
        $types_documents = config('constants.types_documents');
        $request = $this;

        //-----------------------------------------------------------------
        //VALIDAR QUE TODOS LOS CAMPOS HAYAN SIDO INGRESADOS
        //-----------------------------------------------------------------
        if(!isset($request->email)){
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => "Falta ingresar el correo"], 422)
            );
        } else {
            if(!$this->isValidEmail($request->email))
                throw new HttpResponseException(
                    $this->errorResponse(['status' => 422, 'message' => "El correo no es válido"], 422)
                );
        }

        if(!isset($request->name)){
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => "Falta ingresar el nombre"], 422)
            );
        }
        if(!isset($request->lastname)){
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => "Falta ingresar el apellido"], 422)
            );
        }
        if(!isset($request->document_type)){
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => "Falta ingresar el tipo de documento"], 422)
            );
        }
        if(!isset($request->document_number)){
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => "Falta ingresar el número de documento"], 422)
            );
        }
        if(!isset($request->cellphone)){
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => "Falta ingresar el número de telefono"], 422)
            );
        }

        $user = Auth::user();

        //-----------------------------------------------------------------
        //VALIDAR QUE EL CORREO Y EL NÚMERO DE DOCUMENTO NO ESTEN REPETIDOS
        //-----------------------------------------------------------------
        $customer = Customer::where('document_number', $request->document_number)
            ->where('user_id', '!=', $user->id )
            ->where('trade_name', Helper::getTradeNameHeader())
            ->first();
        $customerEmail = Customer::where('email', $request->email)
            ->where('user_id', '!=', $user->id )
            ->where('trade_name', Helper::getTradeNameHeader())
            ->first();
        $customerCellphone = Customer::where('cellphone', $request->cellphone)
            ->where('user_id', '!=', $user->id )
            ->where('trade_name', Helper::getTradeNameHeader())
            ->first();
        if(isset($customerEmail->id)){
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => "El correo ingresado ya se encuentra registrado"], 422)
            );
        }
        if(isset($customer->id)){
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => "El DNI ingresado ya se encuentra registrado"], 422)
            );
        }
        if(isset($customerCellphone->id)){
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => "El número de telefono ingresado ya se encuentra registrado"], 422)
            );
        }

        //-----------------------------------------------------------------
        //VALIDAR EL TIPO DE DOCUMENTO SELECCIONADO
        //-----------------------------------------------------------------
        if(!in_array($request->document_type, $types_documents)){
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => "El tipo de documento seleccionado no es valido"], 422)
            );
        }
        if(!Helper::validateDocumentForType($request->document_number, $request->document_type)){
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => "El número de documento ingresado es incorrecto"], 422)
            );
        }

        //-----------------------------------------------------------------
        //VALIDAR EL NÚMERO DE TELEFONO
        //-----------------------------------------------------------------
        if(strlen($request->cellphone) !== 9){
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => "El número de telefono ingresado es incorrecto"], 422)
            );
        }
    }

    public function isValidEmail($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
