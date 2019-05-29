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
     * @var string|null
     */
    private $alias;

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
     * @param string|null $visaForm
     * @param string|null $alias
     */
    private function __construct(
        int $id,
        array $visaTypeNationalities,
        string $type,
        string $purpose,
        Country $country,
        int $averageDeliveryTime,
        int $maxDeliveryTime,
        ?string $visaForm = null,
        ?string $alias = null
    ) {
        $this->id = $id;
        $this->visaTypeNationalities = $visaTypeNationalities;
        $this->type = $type;
        $this->purpose = $purpose;
        $this->country = $country;
        $this->averageDeliveryTime = $averageDeliveryTime;
        $this->maxDeliveryTime = $maxDeliveryTime;
        $this->visaForm = $visaForm;
        $this->alias = $alias;
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

        $visaType = new self(
            $data->id,
            $visaTypeNationalities,
            $data->type,
            $data->purpose,
            new Country(
                $data->country->isoCode
            ),
            $data->averageDeliveryTime,
            $data->maxDeliveryTime,
            $data->visaForm,
            $data->alias
        );

        return $visaType;
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
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }
}
