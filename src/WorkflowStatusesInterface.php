<?php

namespace Randock\VisaCenterApi;

/**
 * Interface WorkflowStatusesInterface
 */
interface WorkflowStatusesInterface
{
    /**
     *  Workflow Transition Hold.
     */
    public const HOLD = 'hold';

    /**
     *  Workflow Transition Process.
     */
    public const PROCESS = 'process';

    /**
     *  Workflow Transition
     */
    public const SEND = 'send';

    /**
     * Workflow Transition Refund.
     */
    public const REFUND = 'refund';

    /**
     * Workflow Transition Cancel.
     */
    public const CANCEL = 'cancel';
}
