<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Exception;

class OrderContainsErrorsException extends \Exception
{
    /**
     * OrderContainsErrorsException constructor.
     *
     * @param string $errors
     */
    public function __construct(string $errors)
    {
        parent::__construct($errors);
    }
}
