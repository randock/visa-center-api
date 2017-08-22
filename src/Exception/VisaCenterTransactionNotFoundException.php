<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Exception;

class VisaCenterTransactionNotFoundException extends \Exception
{
    /**
     * VisaCenterTransactionNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('randock.visa_center_api.exception.transaction.not_found');
    }
}
