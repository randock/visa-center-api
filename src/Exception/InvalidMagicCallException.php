<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Exception;

class InvalidMagicCallException extends \Exception
{
    /**
     * InvalidMagicCallException constructor.
     */
    public function __construct()
    {
        parent::__construct('randock.visa_center_api.invalid_magic_call_exception');
    }
}
