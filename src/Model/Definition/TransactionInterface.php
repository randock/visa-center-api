<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Model\Definition;

use Randock\ValueObject\Money\Money;

interface TransactionInterface
{
    /**
     * Payment visaCenter transaction status.
     *
     * @var string
     */
    public const STATUS_PAID = 'paid';
    public const STATUS_PENDING = 'pending';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CHARGEDBACK = 'chargedback';

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
     * @return TransactionInterface
     */
    public function setStatus(string $status): self;

    /**
     * @return Money
     */
    public function getPrice(): Money;

    /**
     * @return string|null
     */
    public function getType(): ?string;
}
