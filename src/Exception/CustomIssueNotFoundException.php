<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Exception;

class CustomIssueNotFoundException extends \Exception
{
    /**
     * CustomIssueNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('randock.visa_center_api.exception.custom_issue.not_found');
    }
}
