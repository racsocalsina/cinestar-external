<?php


namespace App\Rules;


use App\Helpers\FunctionHelper;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CheckMultiplesEmails implements Rule
{
    private $errorMessage;

    public function passes($attribute, $value)
    {
        $emails = explode(',', FunctionHelper::removeWhiteSpaces($value));

        $rules = [
            'email' => 'required|email',
        ];

        foreach ($emails as $email) {
            $data = [
                'email' => $email
            ];
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $this->errorMessage = "Email {$email} no válido";
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return $this->errorMessage;
    }
}
