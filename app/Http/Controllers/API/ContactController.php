<?php


namespace App\Http\Controllers\API;


use App\Http\Requests\Contact\ContactRequest;
use App\Models\Contact\Repositories\Interfaces\ContactRepositoryInterface;
use App\Traits\ApiResponser;

class ContactController
{
    use ApiResponser;

    private $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function store(ContactRequest $request)
    {
        $this->contactRepository->create($request->validated());
        return $this->success(['message' => __('app.sent_message')]);
    }

}
