<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Exception;

class VisaCenterTransactionCanNotBeUpdatedException extends \Exception
{
    /**
     * VisaCenterTransactionCanNotBeUpdatedException constructor.
     */
    public function __construct()
    {
        parent::__construct('randock.visa_center_api.exception.transaction.can_not_be_updated');
    }
}
