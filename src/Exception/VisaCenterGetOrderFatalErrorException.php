<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Exception;

class VisaCenterGetOrderFatalErrorException extends \Exception
{
    /**
     * VisaCenterGetOrderFatalErrorException constructor.
     */
    public function __construct()
    {
        parent::__construct('randock.consumerui.exception.visa_center_get_oder_fatal_error');
    }
}
