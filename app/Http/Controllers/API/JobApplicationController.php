<?php


namespace App\Http\Controllers\API;


use App\Http\Requests\JobApplication\JobApplicationRequest;
use App\Models\JobApplication\Repositories\Interfaces\JobApplicationRepositoryInterface;
use App\Traits\ApiResponser;

class JobApplicationController
{
    use ApiResponser;

    private $jobApplicationRepository;

    public function __construct(JobApplicationRepositoryInterface $jobApplicationRepository)
    {
        $this->jobApplicationRepository = $jobApplicationRepository;
    }

    public function store(JobApplicationRequest $request)
    {
        $this->jobApplicationRepository->create($request->validated(), $request->cv);
        return $this->success(['message' => __('app.sent_message')]);
    }

}
