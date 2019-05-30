<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Model;

use Randock\ValueObject\Money\Money;
use Randock\ValueObject\Money\Currency;
use Randock\ValueObject\Country\Nationality;

class VisaTypeNationality
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var Nationality
     */
    private $nationality;
    /**
     * @var int
     */
    private $numEntries;
    /**
     * @var int
     */
    private $validDays;
    /**
     * @var int
     */
    private $stayDays;
    /**
     * @var bool
     */
    private $perVisitStayDays;
    /**
     * @var string
     */
    private $startValidDays;
    /**
     * @var int
     */
    private $entryValidityDays;
    /**
     * @var bool
     */
    private $useBounded;
    /**
     * @var Money|null
     */
    private $price;
    /**
     * @var int|null
     */
    private $definedProcessingDays;

    /**
     * VisaTypeNationality constructor.
     *
     * @param string      $id
     * @param Nationality $nationality
     * @param int         $numEntries
     * @param int         $validDays
     * @param int         $stayDays
     * @param bool        $perVisitStayDays
     * @param string      $startValidDays
     * @param int         $entryValidityDays
     * @param bool        $useBounded
     * @param Money|null  $price
     * @param int|null    $definedProcessingDays
     */
    private function __construct(
        int $id,
        Nationality $nationality,
        int $numEntries,
        int $validDays,
        int $stayDays,
        bool $perVisitStayDays,
        string $startValidDays,
        int $entryValidityDays,
        bool $useBounded,
        Money $price = null,
        int $definedProcessingDays = null
    ) {
        $this->id = $id;
        $this->nationality = $nationality;
        $this->numEntries = $numEntries;
        $this->validDays = $validDays;
        $this->stayDays = $stayDays;
        $this->perVisitStayDays = $perVisitStayDays;
        $this->startValidDays = $startValidDays;
        $this->entryValidityDays = $entryValidityDays;
        $this->useBounded = $useBounded;
        $this->price = $price;
        $this->definedProcessingDays = $definedProcessingDays;
    }

    /**
     * @param \stdClass $data
     *
     * @return VisaTypeNationality
     */
    public static function fromStdClass(\stdClass $data)
    {
        $visaTypeNationality = new self(
            $data->id,
            new Nationality(
                $data->nationality->isoCode
            ),
            $data->numEntries,
            $data->validDays,
            $data->stayDays,
            $data->perVisitStayDays,
            $data->startValidDays,
            $data->entryValidityDays,
            $data->useBounded,
            isset($data->price) ? new Money($data->price->amount, new Currency($data->price->currency->currencyCode)) : null,
            $data->definedProcessingDays ?? null
        );

        return $visaTypeNationality;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Nationality
     */
    public function getNationality(): Nationality
    {
        return $this->nationality;
    }

    /**
     * @return int
     */
    public function getNumEntries(): int
    {
        return $this->numEntries;
    }

    /**
     * @return int
     */
    public function getValidDays(): int
    {
        return $this->validDays;
    }

    /**
     * @return int
     */
    public function getStayDays(): int
    {
        return $this->stayDays;
    }

    /**
     * @return bool
     */
    public function isPerVisitStayDays(): bool
    {
        return $this->perVisitStayDays;
    }

    /**
     * @return string
     */
    public function getStartValidDays(): string
    {
        return $this->startValidDays;
    }

    /**
     * @return int
     */
    public function getEntryValidityDays(): int
    {
        return $this->entryValidityDays;
    }

    /**
     * @return bool
     */
    public function isUseBounded(): bool
    {
        return $this->useBounded;
    }

    /**
     * @return Money|null
     */
    public function getPrice(): ?Money
    {
        return $this->price;
    }

    /**
     * @return int|null
     */
    public function getDefinedProcessingDays(): ?int
    {
        return $this->definedProcessingDays;
    }
}
