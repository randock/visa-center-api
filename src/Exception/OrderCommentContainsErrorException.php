<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Exception;

class OrderCommentContainsErrorException extends \Exception
{
    /**
     * @var string[]
     */
    private $errors;

    /**
     * OrderCommentContainsErrorException constructor.
     */
    public function __construct(array $errors)
    {
        parent::__construct(
            'randock.visa_center_api.exception.order_comment_contains_error'
        );
        $this->errors = $errors;
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
