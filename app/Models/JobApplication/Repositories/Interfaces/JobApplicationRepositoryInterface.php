<?php


namespace App\Models\JobApplication\Repositories\Interfaces;


interface JobApplicationRepositoryInterface
{
    public function create($request, $file);
}
