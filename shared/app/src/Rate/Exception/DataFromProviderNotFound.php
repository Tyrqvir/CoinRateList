<?php

declare(strict_types=1);

namespace App\Rate\Exception;

class DataFromProviderNotFound extends \Exception
{
    protected $message = 'Data from provider not found';
}