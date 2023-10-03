<?php


namespace App\Http\Requests\BackOffice\MovieFormats;


use App\Models\Headquarters\Repositories\Interfaces\HeadquarterRepositoryInterface;
use App\SearchableRules\HeadquarterSearchableRule;
use App\Services\Searchable\Searchable;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MovieFormatDeleteRequest extends FormRequest
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
        if (!$validator->fails()) {
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
            'movie_format_id' => $this->route('movie_format')->id
        ]);
        $exists = $query->count() > 0;

        if ($exists)
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => __('app.movie_formats.delete_headquarters_related')], 422)
            );
    }

}
