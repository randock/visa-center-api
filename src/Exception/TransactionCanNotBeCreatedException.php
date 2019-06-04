<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Exception;

class TransactionCanNotBeCreatedException extends \Exception
{
    /**
     * TransactionCanNotBeCreatedException constructor.
     */
    public function __construct()
    {
        parent::__construct('randock.visa_center_api.exception.transaction.can_not_be_created');
    }
}
