<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Exception;

class FileCanNotBeSentException extends \Exception
{
    /**
     * FileCanNotBeSentException constructor.
     */
    public function __construct()
    {
        parent::__construct('randock.visa_center_api.exception.file_can_not_be_sent');
    }
}
