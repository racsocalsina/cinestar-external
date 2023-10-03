<?php


namespace App\Http\Requests\Consumer\MovieTime;


use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieTimeRequest extends FormRequest
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
            'funkey' => 'required|exists:movie_times,remote_funkey',
            'graph' => 'required',
            'planner_meta' => 'required',
        ];
    }
}
