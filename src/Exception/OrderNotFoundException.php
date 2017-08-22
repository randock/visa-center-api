<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Exception;

class OrderNotFoundException extends \Exception
{
    /**
     * OrderNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('randock.consumerui.exception.order.not_found');
    }
}
