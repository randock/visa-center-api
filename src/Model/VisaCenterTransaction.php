<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Model;

use Randock\ValueObject\Money\Money;
use Randock\ValueObject\Money\Currency;
use Randock\VisaCenterApi\Model\Definition\VisaCenterTransactionInterface;

class VisaCenterTransaction implements VisaCenterTransactionInterface
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * @var Money
     */
    private $price;

    /**
     * @var string
     */
    private $status;

    /**
     * @var null|string
     */
    private $type;

    /**
     * VisaCenterTransaction constructor.
     *
     * @param string      $uuid
     * @param Money       $price
     * @param string      $status
     * @param null|string $type
     */
    public function __construct(string $uuid, Money $price, string $status, ?string $type)
    {
        $this->uuid = $uuid;
        $this->price = $price;
        $this->type = $type;
        $this->status = $status;
    }

    /**
     * @param \stdClass|null $data
     *
     * @return VisaCenterTransactionInterface
     */
    public static function fromStdClass(\stdClass $data = null): VisaCenterTransactionInterface
    {
        return new self(
            $data->uuid,
            new Money($data->price->amount, new Currency($data->price->currency->currencyCode)),
            $data->status,
            $data->type ?? null
        );
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return VisaCenterTransactionInterface
     */
    public function setStatus(string $status): VisaCenterTransactionInterface
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Money
     */
    public function getPrice(): Money
    {
        return $this->price;
    }

    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }
}