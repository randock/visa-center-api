<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Exception;

class NotificationNotFoundException extends \Exception
{
    /**
     * NotificationNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('randock.visa_center_api.exception.notification.not_found');
    }
}
