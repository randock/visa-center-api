<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Exception;

use Randock\Utils\Http\Exception\HttpException;

class OrderContainsErrorsException extends HttpException
{
    /**
     * OrderContainsErrorsException constructor.
     * @param int $statusCode
     * @param string|null $body
     */
    public function __construct(int $statusCode, string $body = null)
    {
        parent::__construct($statusCode, $body);
    }
}
