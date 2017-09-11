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
     * @param string $message
     */
    public function __construct(int $statusCode, string $body = null, string $message)
    {
        parent::__construct($statusCode, $body, $message);
    }
}
