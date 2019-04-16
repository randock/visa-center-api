<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Model;

class VisaTypeNationality
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var \stdClass
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
     * @var \stdClass|null
     */
    private $price;
    /**
     * @var int|null
     */
    private $definedProcessingDays;

    /**
     * VisaTypeNationality constructor.
     *
     * @param string         $id
     * @param \stdClass      $nationality
     * @param int            $numEntries
     * @param int            $validDays
     * @param int            $stayDays
     * @param bool           $perVisitStayDays
     * @param string         $startValidDays
     * @param int            $entryValidityDays
     * @param bool           $useBounded
     * @param \stdClass|null $price
     * @param int|null       $definedProcessingDays
     */
    private function __construct(
        int $id,
        \stdClass $nationality,
        int $numEntries,
        int $validDays,
        int $stayDays,
        bool $perVisitStayDays,
        string $startValidDays,
        int $entryValidityDays,
        bool $useBounded,
        \stdClass $price = null,
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
            $data->nationality,
            $data->numEntries,
            $data->validDays,
            $data->stayDays,
            $data->perVisitStayDays,
            $data->startValidDays,
            $data->entryValidityDays,
            $data->useBounded,
            $data->price ?? null,
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
     * @return \stdClass
     */
    public function getNationality(): \stdClass
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
     * @return \stdClass|null
     */
    public function getPrice(): ?\stdClass
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
