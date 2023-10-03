<?php


namespace App\Http\Requests\Consumer\MovieTimeTariff;


use Illuminate\Foundation\Http\FormRequest;

class SyncMovieTimeTariffeRequest extends FormRequest
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
        return [
            'action' => 'required',
            'url' => 'required',
        ];
    }
}
