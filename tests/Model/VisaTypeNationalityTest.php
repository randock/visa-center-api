<?php

declare(strict_types=1);

namespace Tests\Randock\Unit\Domain\VisaType\Model;

use PHPUnit\Framework\TestCase;
use Randock\VisaCenterApi\Model\VisaTypeNationality;

class VisaTypeNationalityTest extends TestCase
{
    public const NATIONALITY = 'ES';
    public const AMOUNT = 100.0;
    public const CURRENCY = 'EUR';
    public const NUM_ENTRIES = 1;
    public const VALID_DAYS = 180;
    public const STAY_DAYS = 30;

    public function testGetters()
    {
        $visaTypeNationality = self::newVisaTypeNationality();

        self::assertNotNull($visaTypeNationality->getNationality());
        self::assertNotNull($visaTypeNationality->getPrice());
        self::assertSame(self::NUM_ENTRIES, $visaTypeNationality->getNumEntries());
        self::assertSame(self::VALID_DAYS, $visaTypeNationality->getValidDays());
        self::assertSame(self::STAY_DAYS, $visaTypeNationality->getStayDays());
        self::assertNotNull($visaTypeNationality->getId());
    }

    public static function newVisaTypeNationality()
    {
        $class = new \stdClass();

        $class->id = 1;
        $class->nationality = new \stdClass();
        $class->numEntries = self::NUM_ENTRIES;
        $class->validDays = self::VALID_DAYS;
        $class->stayDays = self::STAY_DAYS;

        $class->perVisitStayDays = false;
        $class->startValidDays = 'entry';
        $class->entryValidityDays = 0;
        $class->useBounded = false;
        $class->price = new \stdClass();
        $class->definedProcessingDays = null;

        return VisaTypeNationality::fromStdClass(
            $class
        );
    }
}
