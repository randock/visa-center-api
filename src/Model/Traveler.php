<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Model;

use Randock\VisaCenterApi\Model\ValueObject\TravelerDetails;

/**
 * Class Traveler.
 */
class Traveler
{
    /**
     * @var string
     */
    private $uuid;
    /**
     * @var TravelerDetails
     */
    private $travelerDetails;
    /**
     * @var string|null
     */
    private $applicationId;

    /**
     * Traveler constructor.
     *
     * @param string          $uuid
     * @param TravelerDetails $travelerDetails
     * @param string|null     $applicationId
     */
    private function __construct(
        string $uuid,
        TravelerDetails $travelerDetails,
        string $applicationId = null
    ) {
        $this->uuid = $uuid;
        $this->travelerDetails = $travelerDetails;
        $this->applicationId = $applicationId;
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        /** @var callable $callable */
        $callable = [$this->getTravelerDetails(), $name];

        return \call_user_func_array($callable, $arguments);
    }

    /**
     * @param \stdClass $data
     *
     * @return Traveler
     */
    public static function fromStdClass(\stdClass $data)
    {
        return new self(
            $data->uuid,
            new TravelerDetails($data),
            $data->applicationId
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
     * @return TravelerDetails
     */
    public function getTravelerDetails(): TravelerDetails
    {
        return $this->travelerDetails;
    }

    /**
     * @return string|null
     */
    public function getApplicationId(): ?string
    {
        return $this->applicationId;
    }
}
