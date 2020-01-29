<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Exception;

class ProvincesNotFoundException extends \Exception
{
    /**
     * ProvincesNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('randock.visa_center_api.exception.province.provinces_not_found');
    }
}
