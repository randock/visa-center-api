<?php

declare(strict_types=1);

namespace Tests\Randock\VisaCenterApi\Model;

use PHPUnit\Framework\TestCase;
use Randock\VisaCenterApi\Model\Traveler;
use Randock\VisaCenterApi\Model\ValueObject\TravelerDetails;

class TravelerTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testGetters()
    {
        $traveler = self::createFromStdClass();

        $this->assertNotNull($traveler->getUuid());
        $this->assertNotNull($traveler->getApplicationId());
        $this->assertInstanceOf(TravelerDetails::class, $traveler->getTravelerDetails());
    }

    public static function getStdClassObject()
    {
        return json_decode('{
            "applicationId": 34611,
            "uuid": "c04d1228-08df-4abb-a631-ca519f865811",
            "lastname": "kemeling",
            "passport": {
                "documentType": "passport",
                "passportNumber": "NX1121221",
                "passportIssueDate": "2018-10-29",
                "passportExpiryDate": "2028-10-29"
            },
            "dateOfBirth": "1991-03-09",
            "nationality": "NL",
            "placeOfBirth": "zutphen",
            "allFirstNames": "ryan maaer",
            "applicationId": "NTY-2311"
}');
    }

    /**
     * @throws \Exception
     *
     * @return Traveler
     */
    public static function createFromStdClass(): Traveler
    {
        return Traveler::fromStdClass(
            self::getStdClassObject()
        );
    }
}
