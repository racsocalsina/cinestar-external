<?php


namespace App\Http\Requests\BackOffice\MovieGenders;


use App\Models\Movies\Repositories\Interfaces\MovieRepositoryInterface;
use App\SearchableRules\MovieSearchableRule;
use App\Services\Searchable\Searchable;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MovieGenderDeleteRequest extends FormRequest
{
    use ApiResponser;

    private $movieRepository;
    private $searchableService;

    public function __construct(
        MovieRepositoryInterface $movieRepository,
        Searchable $searchableService
    )
    {
        $this->movieRepository = $movieRepository;
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
        $query = $this->movieRepository->queryable();
        $this->searchableService->applyArray($query, new MovieSearchableRule(), [
            'movie_gender_id' => $this->route('movie_gender')->id
        ]);
        $exists = $query->count() > 0;

        if($exists)
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => __('app.movie_genders.delete_movies_related')], 422)
            );
    }

}
