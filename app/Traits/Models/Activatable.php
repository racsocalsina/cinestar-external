<?php


namespace App\Traits\Models;


trait Activatable
{
    public function setActive(bool $status)
    {
        $this->update(['status' => $status]);
    }

    public function toggleActive()
    {
        $this->update(['status' => !$this->isActive()]);
    }

    public function isActive(): bool
    {
        return $this->status == 1;
    }
}
