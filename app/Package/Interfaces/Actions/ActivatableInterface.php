<?php


namespace App\Package\Interfaces\Actions;


interface ActivatableInterface
{
    public function setActive(bool $status);
    public function toggleActive();
    public function isActive():bool;
}
