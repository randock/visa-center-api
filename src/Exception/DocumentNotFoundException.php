<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Exception;

class DocumentNotFoundException extends \Exception
{
    /**
     * DocumentNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('randock.visa_center_api.exception.document.not_found');
    }
}
