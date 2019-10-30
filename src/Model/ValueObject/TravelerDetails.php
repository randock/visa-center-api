<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Model\ValueObject;

use Randock\ValueObject\DynamicObjectStorage;

/**
 * Class TravelerDetails.
 *
 * @method string getAllFirstNames()
 * @method string getLastName()
 * @method string getNationality()
 */
class TravelerDetails extends DynamicObjectStorage
{
    public const BUCKET = 'travelerDetails';

    /**
     * TravelerDetails constructor.
     *
     * @param \stdClass|null $data
     */
    public function __construct(\stdClass $data = null)
    {
        parent::__construct($data);
        $this->fixCommonErrors();
    }

    /**
     * Returns the name of the bucket for the CDN.
     *
     * @return string
     */
    public static function getBucket(): string
    {
        return self::BUCKET;
    }

    public function fixCommonErrors()
    {
        if (\in_array($this->getNationality(), ['DE', 'NL', 'BE'])) {
            if (null !== $this->getPassport() && null !== $this->getPassport()->getPassportNumber()) {
                $passportNumber = \strtoupper($this->getPassport()->getPassportNumber());
                $this->getPassport()->setPassportNumber(\str_replace('O', '0', $passportNumber));
            }
        }
    }
}
