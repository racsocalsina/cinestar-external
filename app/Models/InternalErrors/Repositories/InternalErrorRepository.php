<?php


namespace App\Models\InternalErrors\Repositories;


use App\Helpers\FunctionHelper;
use App\Helpers\Helper;
use App\Jobs\SendErrorEmail;
use App\Models\Headquarters\Headquarter;
use App\Models\InternalErrors\InternalError;
use App\Models\InternalErrors\Repositories\Interfaces\InternalErrorRepositoryInterface;
use App\SearchableRules\InternalErrorSearchableRule;
use App\Services\Mail\Dtos\ExceptionDto;
use App\Services\Searchable\Searchable;

class InternalErrorRepository implements InternalErrorRepositoryInterface
{
    private $searchableService;
    private $repository;

    public function __construct(Searchable $searchableService, InternalError $repository)
    {
        $this->searchableService = $searchableService;
        $this->repository = $repository;
    }

    public function create(array $body)
    {
        $headquarter = Headquarter::where('api_url', $body['url'])->get()->first();
        $body['headquarter_id'] = null;
        $body['headquarter_name'] = $body['url'];
        $exceptionDto = new ExceptionDto();
        if ($headquarter){
            $body['headquarter_id'] = $headquarter->id;
            $body['headquarter_name'] = $headquarter->name;
            $model = InternalError::create($body);
            $exceptionDto->setMessage("[ID: $model->id] " . $body['message']);
        }else{
            $exceptionDto->setMessage("[ID: NO REGISTRADO " . $body['message']);
        }

        if(FunctionHelper::getValueSystemConfigurationByKey('send_email_internal_errors'))
        {
            $subject = "Error internal en la sede {$body['headquarter_name']}";

            $exceptionDto->setCode($body['code']);
            $exceptionDto->setLine($body['line']);
            $exceptionDto->setFile($body['file']);
            $exceptionDto->setTraceAsString($body['trace']);
            $exceptionDto->setSubject($subject);
//            SendErrorEmail::dispatch($exceptionDto);
            FunctionHelper::sendErrorMail($exceptionDto);
        }
    }

    public function searchBO($request)
    {
        $query = $this->repository->query();
        $this->searchableService->applyArray($query, new InternalErrorSearchableRule(), $request);
        return $query->orderBy('created_at', 'desc')
            ->orderBy('headquarter_name')
            ->paginate(Helper::perPage($request));
    }
}
