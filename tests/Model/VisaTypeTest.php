<?php

declare(strict_types=1);

namespace Tests\Randock\VisaCenterApi\Model;

use PHPUnit\Framework\TestCase;
use Randock\VisaCenterApi\Model\VisaType;

class VisaTypeTest extends TestCase
{
    /**
     * @var array
     */
    public const NATIONALITIES = [
        [
            'isoCode' => 'NL',
            'amount' => 100.00,
            'currencyCode' => 'EUR',
            'numEntries' => 0,
            'validDays' => 180,
            'stayDayS' => 90,
        ],
        [
            'isoCode' => 'DE',
            'amount' => 100.00,
            'currencyCode' => 'EUR',
            'numEntries' => 0,
            'validDays' => 180,
            'stayDayS' => 90,
        ],
    ];

    /**
     * @var int
     */
    public const NUM_ENTRIES = 2;

    /**
     * @var int
     */
    public const VALID_DAYS = 90;

    /**
     * @var int
     */
    public const STAY_DAYS = 30;

    /**
     * @var string
     */
    public const TYPE = 'evisa';

    /**
     * @var string
     */
    public const PURPOSE = 'tourism';

    /**
     * @var float
     */
    public const AMOUNT = 25.00;

    /**
     * @var string
     */
    public const CURRENCY = 'EUR';

    /**
     * @var string
     */
    public const COUNTRY = 'NL';

    /**
     * @var float
     */
    public const RECEIVE_BY_MAIL_PRICE_AMOUNT = 3.00;

    /**
     * @var float
     */
    public const URGENT_PRICE_AMOUNT = 10.00;

    /**
     * @var string
     */
    public const VISA_FORM = '/visa-form';

    /**
     * @var int
     */
    public const AVERAGE_DELIVERY_TIME = 4;

    /**
     * @var int
     */
    public const MAX_DELIVERY_TIME = 72;

    /**
     * @var int
     */
    public const ID = 1;

    /**
     * @var string
     */
    public const ALIAS = 'IN';

    /**
     * Test all getters and setters for this object.
     */
    public function testGetters()
    {
        $visaType = self::createFromStdClass();

        $this->assertNotNull($visaType->getVisaTypeNationalities());

        $this->assertSame(self::AVERAGE_DELIVERY_TIME, $visaType->getAverageDeliveryTime());

        $this->assertNotNull($visaType->getCountry());

        $this->assertSame(self::MAX_DELIVERY_TIME, $visaType->getMaxDeliveryTime());

        $this->assertSame(self::PURPOSE, $visaType->getPurpose());

        $this->assertSame(self::VISA_FORM, $visaType->getVisaForm());

        $this->assertSame(self::TYPE, $visaType->getType());
    }

    public static function getStdClassObject()
    {
        return json_decode('[{
                "id": 1,
                "nationality": {"isoCode": "NL"},
                "price": {
                    "amount": 1150.00,
                    "currency": {
                        "currencyCode": "USD"
                    }
                },
                "numEntries": 2,
                "validDays": 180,
                "stayDays": 60,
                "perVisitStayDays": true,
                "startValidDays": "entry",
                "useBounded": false,
                "entryValidityDays": 30,
                "definedProcessingDays": 5
            }, {
                "id": 2,
                "nationality": {"isoCode": "GB"},
                "price": {
                    "amount": 1150.00,
                    "currency": {
                        "currencyCode": "USD"
                    }
                },
                "numEntries": 2,
                "validDays": 180,
                "stayDays": 60,
                "perVisitStayDays": true,
                "startValidDays": "entry",
                "useBounded": false,
                "entryValidityDays": 30,
                "definedProcessingDays": 5
            }]');
    }

    /**
     * Test the fromStdClass method.
     */
    public function testFromStdClass()
    {
        $class = new \stdClass();

        $class->visaTypeNationalities = self::getStdClassObject();

        $class->id = self::ID;
        $class->type = self::TYPE;
        $class->purpose = self::PURPOSE;
        $class->country = new \stdClass();
        $class->country->isoCode = self::COUNTRY;
        $class->receiveByMailPriceAmount = self::RECEIVE_BY_MAIL_PRICE_AMOUNT;
        $class->urgentPriceAmount = self::URGENT_PRICE_AMOUNT;
        $class->visaForm = self::VISA_FORM;
        $class->averageDeliveryTime = self::AVERAGE_DELIVERY_TIME;
        $class->maxDeliveryTime = self::MAX_DELIVERY_TIME;
        $class->alias = self::ALIAS;

        // construct the visaType visa static method
        $visaType = self::createFromStdClass();

        // check for correct construction
        $this->assertDefaultValues($visaType);
        $this->assertSame(self::ID, $visaType->getId());
    }

    /**
     * @return VisaType
     */
    public static function createFromStdClass(): VisaType
    {
        $class = new \stdClass();

        $class->visaTypeNationalities = self::getStdClassObject();
        $class->id = self::ID;
        $class->type = self::TYPE;
        $class->purpose = self::PURPOSE;
        $class->country = new \stdClass();
        $class->country->isoCode = self::COUNTRY;
        $class->receiveByMailPriceAmount = self::RECEIVE_BY_MAIL_PRICE_AMOUNT;
        $class->urgentPriceAmount = self::URGENT_PRICE_AMOUNT;
        $class->visaForm = self::VISA_FORM;
        $class->averageDeliveryTime = self::AVERAGE_DELIVERY_TIME;
        $class->maxDeliveryTime = self::MAX_DELIVERY_TIME;
        $class->alias = self::ALIAS;

        return VisaType::fromStdClass(
            $class
        );
    }

    /**
     * Check if the visaType contains the values (default) we expect.
     *
     * @param VisaType $visaType
     */
    private function assertDefaultValues(VisaType $visaType)
    {
        $this->assertNotNull($visaType->getVisaTypeNationalities());
        $this->assertSame(self::TYPE, $visaType->getType());
        $this->assertSame(self::PURPOSE, $visaType->getPurpose());
        $this->assertNotNull($visaType->getCountry());
        $this->assertSame(self::VISA_FORM, $visaType->getVisaForm());
        $this->assertSame(self::AVERAGE_DELIVERY_TIME, $visaType->getAverageDeliveryTime());
        $this->assertSame(self::MAX_DELIVERY_TIME, $visaType->getMaxDeliveryTime());
        $this->assertSame(self::ALIAS, $visaType->getAlias());
    }
}
