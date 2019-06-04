<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Exception;

class TransactionCanNotBeUpdatedException extends \Exception
{
    /**
     * TransactionCanNotBeUpdatedException constructor.
     */
    public function __construct()
    {
        parent::__construct('randock.visa_center_api.exception.transaction.can_not_be_updated');
    }
}
