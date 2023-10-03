<?php


namespace App\Services\Mail\Actions;


use App\Services\Mail\Dtos\ExceptionDto;

class BuildExceptionDto
{
    private $exception;

    public function __construct(\Exception $exception)
    {
        $this->exception = $exception;
    }

    public function build()
    {
        $dto = new ExceptionDto();
        $dto->setCode($this->exception->getCode());
        $dto->setLine($this->exception->getLine());
        $dto->setFile($this->exception->getFile());
        $dto->setMessage($this->exception->getMessage());
        $dto->setTraceAsString($this->exception->getTraceAsString());
        $dto->setSubject("Ha ocurrido un error procesando una solicitud");

        return $dto;
    }

}
