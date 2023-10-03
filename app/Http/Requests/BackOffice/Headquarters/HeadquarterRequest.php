<?php


namespace App\Http\Requests\BackOffice\Headquarters;


use App\Enums\BusinessName;
use App\Enums\TradeName;
use App\Models\MovieFormats\Repositories\Interfaces\MovieFormatRepositoryInterface;
use App\SearchableRules\MovieTimeSearchableRule;
use App\Services\Searchable\Searchable;
use App\Traits\ApiResponser;
use App\Traits\Requests\HeadquarterImageRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class HeadquarterRequest extends FormRequest
{
    use ApiResponser, HeadquarterImageRequest;

    private $movieFormatRepository;
    private $searchableService;

    public function __construct(
        MovieFormatRepositoryInterface $movieFormatRepository,
        Searchable $searchableService
    )
    {
        $this->movieFormatRepository = $movieFormatRepository;
        $this->searchableService = $searchableService;
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'description'   => 'required',
            'address'       => 'required|string|max:150',
            'latitude'      => 'required',
            'longitude'     => 'required',
            'point_sale'    => 'required|max:5',
            'api_url'       => 'required',
            'user'          => 'required|max:50',
            'city_id'       => 'required|exists:cities,id',
            'movie_formats' => 'required|regex:/^[\d\s,]*$/',
            'business_name' => 'required|in:' . implode(',', BusinessName::ALL_VALUES),
            'trade_name'    => 'required|in:' . implode(',', TradeName::ALL_VALUES),
            'local_url'     => 'required',
        ];

        if ($this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH') {
            $rules['name'] = 'required|string|max:50|unique:headquarters,name,' . $this->route('headquarters')->id . ',id,deleted_at,NULL';
            $rules['password'] = 'sometimes';
        } else if ($this->getMethod() === 'POST') {
            $rules['name'] = 'required|string|max:50|unique:headquarters,name,NULL,id,deleted_at,NULL';
            $rules['password'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'local_url.required' => 'IP Privada',
        ];
    }

    public function withValidator($validator)
    {

        if (!$validator->fails()) {
            $this->checkMovieFormats();
            $this->checkImgs();
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }

    private function checkMovieFormats()
    {
        $arrayMovieFormatsEntered = explode(",", $this->movie_formats);
        $query = $this->movieFormatRepository->queryable();
        $this->searchableService->applyArray($query, new MovieTimeSearchableRule(), [
            'ids' => $this->movie_formats
        ]);
        $count = $query->count();

        if ($count != count($arrayMovieFormatsEntered))
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => __('app.headquarters.movie_formats_do_not_exist')], 422)
            );
    }

    private function checkImgs()
    {
        if ($this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH')
            return true;

        $files = $this->file('files');

        $pass = $this->checkImages(null, $files);

        if (is_array($pass)) {
            throw new HttpResponseException(
                $this->errorResponse($pass, 422)
            );
        }
    }
}
