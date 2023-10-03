<?php


namespace App\Http\Requests\BackOffice\Cities;


use App\Models\Headquarters\Repositories\Interfaces\HeadquarterRepositoryInterface;
use App\SearchableRules\HeadquarterSearchableRule;
use App\Services\Searchable\Searchable;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CityDeleteRequest extends FormRequest
{
    use ApiResponser;

    private $headquarterRepository;
    private $searchableService;

    public function __construct(
        HeadquarterRepositoryInterface $headquarterRepository,
        Searchable $searchableService
    )
    {
        $this->headquarterRepository = $headquarterRepository;
        $this->searchableService = $searchableService;
    }

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
        if(!$validator->fails())
        {
            $this->checkRelationships();
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }

    private function checkRelationships()
    {
        $query = $this->headquarterRepository->queryable();
        $this->searchableService->applyArray($query, new HeadquarterSearchableRule(), [
            'city_id' => $this->route('city')->id
        ]);
        $exists = $query->count() > 0;

        if($exists)
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => __('app.cities.delete_headquarters_related')], 422)
            );
    }

}
