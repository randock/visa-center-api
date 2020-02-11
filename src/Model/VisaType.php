<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Model;

use Randock\ValueObject\Country\Country;

class VisaType
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var array
     */
    private $visaTypeNationalities;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $purpose;

    /**
     * @var Country
     */
    private $country;

    /**
     * @var string|null
     */
    private $visaForm;

    /**
     * @var int
     */
    private $averageDeliveryTime;

    /**
     * @var int
     */
    private $maxDeliveryTime;

    /**
     * @var bool
     */
    private $exempt;

    /**
     * @var string|null
     */
    private $alias;

    /**
     * @var string|null
     */
    private $regularCountry;

    /**
     * VisaType constructor.
     *
     * @param int         $id
     * @param array       $visaTypeNationalities
     * @param string      $type
     * @param string      $purpose
     * @param Country     $country
     * @param int         $averageDeliveryTime
     * @param int         $maxDeliveryTime
     * @param bool        $exempt
     * @param string|null $visaForm
     * @param string|null $alias
     * @param string|null $regularCountry
     */
    private function __construct(
        int $id,
        array $visaTypeNationalities,
        string $type,
        string $purpose,
        Country $country,
        int $averageDeliveryTime,
        int $maxDeliveryTime,
        bool $exempt,
        ?string $visaForm = null,
        ?string $alias = null,
        ?string $regularCountry = null
    ) {
        $this->id = $id;
        $this->visaTypeNationalities = $visaTypeNationalities;
        $this->type = $type;
        $this->purpose = $purpose;
        $this->country = $country;
        $this->averageDeliveryTime = $averageDeliveryTime;
        $this->maxDeliveryTime = $maxDeliveryTime;
        $this->exempt = $exempt;
        $this->visaForm = $visaForm;
        $this->alias = $alias;
        $this->regularCountry = $regularCountry;
    }

    /**
     * @param \stdClass $data
     *
     * @return VisaType
     */
    public static function fromStdClass(\stdClass $data)
    {
        $visaTypeNationalities = [];
        $data->visaTypeNationalities = $data->visaTypeNationalities ?? [];
        foreach ($data->visaTypeNationalities as $nationality) {
            $visaTypeNationalities[] = VisaTypeNationality::fromStdClass($nationality);
        }

        return new self(
            $data->id,
            $visaTypeNationalities,
            $data->type,
            $data->purpose,
            new Country(
                $data->country->isoCode
            ),
            $data->averageDeliveryTime,
            $data->maxDeliveryTime,
            $data->exempt,
            $data->visaForm,
            $data->alias,
            $data->regularCountry
        );
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getPurpose(): string
    {
        return $this->purpose;
    }

    /**
     * @return VisaTypeNationality[]
     */
    public function getVisaTypeNationalities(): array
    {
        return $this->visaTypeNationalities;
    }

    /**
     * @return Country
     */
    public function getCountry(): Country
    {
        return $this->country;
    }

    /**
     * @return string|null
     */
    public function getVisaForm(): ?string
    {
        return $this->visaForm;
    }

    /**
     * @return int
     */
    public function getAverageDeliveryTime(): int
    {
        return $this->averageDeliveryTime;
    }

    /**
     * @return int
     */
    public function getMaxDeliveryTime(): int
    {
        return $this->maxDeliveryTime;
    }

    /**
     * @return bool
     */
    public function isExempt(): bool
    {
        return $this->exempt;
    }

    /**
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * @return string|null
     */
    public function getRegularCountry(): ?string
    {
        return $this->regularCountry;
    }
}
