<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Model\Definition;

use Randock\ValueObject\Money\Money;

interface VisaCenterTransactionInterface
{
    /**
     * Payment visaCenter transaction status.
     *
     * @var string
     */
    public const STATUS_PAID = 'paid';
    public const STATUS_PENDING = 'pending';
    public const STATUS_FAILED = 'failed';

    /**
     * @return string
     */
    public function getUuid(): string;

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @param string $status
     *
     * @return VisaCenterTransactionInterface
     */
    public function setStatus(string $status): VisaCenterTransactionInterface;

    /**
     * @return Money
     */
    public function getPrice(): Money;

    /**
     * @return null|string
     */
    public function getType(): ?string;
}
