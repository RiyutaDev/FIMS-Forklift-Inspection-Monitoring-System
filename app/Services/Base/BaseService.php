<?php

namespace App\Services\Base;

abstract class BaseService
{
    protected string $module = 'Service';

    protected function getModule(): string
    {
        return $this->module;
    }
}
