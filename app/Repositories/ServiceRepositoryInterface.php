<?php

namespace App\Repositories;

use App\Models\Service;

interface ServiceRepositoryInterface
{
    /**
     * @param Service $service
     */
    public function __construct(Service $service);

    /**
     * @return mixed
     */
    public function getAll(): mixed;
}