<?php

declare(strict_types=1);

namespace Tests\Randock\VisaCenterApi\Model;

use PHPUnit\Framework\TestCase;
use Randock\VisaCenterApi\Model\Order;
use Randock\VisaCenterApi\Model\VisaType;

class OrderTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testGetters()
    {
        $order = self::createFromStdClass();

        $this->assertNotNull($order->getUuid());
        $this->assertNotNull($order->getOrderId());
        $this->assertNotNull($order->getArrivalDate());
        $this->assertNotNull($order->getAddress());
        $this->assertNotNull($order->getEmail());
        $this->assertNotNull($order->getPhone());
        $this->assertInstanceOf(VisaType::class, $order->getVisaType());
        $this->assertNotNull($order->getLocale());
        $this->assertNotNull($order->getDomain());
        $this->assertNotNull($order->getTravelers());
        $this->assertNotNull($order->getStatus());
        $this->assertNotNull($order->getCreatedAt());
        $this->assertNotNull($order->getUpdatedAt());
        $this->assertNotNull($order->isValidated());
        $this->assertNull($order->getGovRegistrationId());
        $this->assertNotNull($order->isGovRegistrationCompleted());
        $this->assertNotNull($order->getTransactions());
        $this->assertNotNull($order->getImages());
        $this->assertNotNull($order->getEmails());
        $this->assertNotNull($order->getPdfs());
        $this->assertNotNull($order->getDocuments());
        $this->assertNotNull($order->getInvoices());
        $this->assertNotNull($order->getSeleniumVideos());
        $this->assertNotNull($order->getIssues());
        $this->assertNotNull($order->getOrderDetails());
    }

    public static function getStdClassObject()
    {
        return \json_decode('{
    "orderDetails": {
        "urgent": {
            "urgentProcessing": true
        },
        "govRegistration": {
            "governmentRegistration": false
        }
    },
    "uuid": "3ae1b32a-71d2-47f2-8aa3-b2ff8efc63e6",
    "orderId": 34611,
    "travelers": [
        {
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
        }
    ],
    "arrivalDate": "2019-04-15",
    "email": "a.merl@mail.nl",
    "phone": "+31421348253",
    "address": {
        "uuid": "2822088a-9281-4348-904a-2f3b5e8388a0",
        "street": "de flaamert",
        "number": "1114",
        "city": "nifuw bergen",
        "postalCode": "5854NC",
        "province": "Limburg",
        "country": "NL"
    },
    "status": "completed",
    "locale": "nl_NL",
    "validated": true,
    "govRegistrationId": null,
    "domain": {
        "id": 1,
        "name": "e-visums.nl",
        "company": {
            "id": 1,
            "name": "E-visums",
            "callbackUri": "https://e-visums.nl/notification/order.json",
            "prefix": "NJT"
        },
        "email": "contact@e-visums.nl",
        "phone": "+31502100245",
        "vat": "NL855106803B01",
        "coc": "63133792",
        "address": "Siriusdreef 17<br />2132 WT Hoofddorp<br>Nederland",
        "postAddress": "Postbus 454<br>2130 AL Hoofddorp<br>Nederland"
    },
    "transactions": [
        {
            "uuid": "2690d421-ce5c-4060-a869-024a1815a97b",
            "price": {
                "amount": 34.9,
                "currency": {
                    "currencyCode": "EUR"
                }
            },
            "status": "paid",
            "type": "IDEAL",
            "createdAt": "2019-04-15T09:46:27+0000",
            "updatedAt": "2019-04-15T09:47:12+0000"
        }
    ],
    "visaType": {
        "id": 48,
        "visaTypeNationalities": [
            {
                "nationality": {
                    "id": 133,
                    "isoCode": "NL"
                },
                "id": 80,
                "numEntries": 0,
                "validDays": 180,
                "stayDays": 90,
                "perVisitStayDays": false,
                "startValidDays": "entry",
                "entryValidityDays": 0,
                "useBounded": true,
                "definedProcessingDays": 90,
                "updatedAt": "2019-02-25T09:03:46+00:00"
            },
            {
                "nationality": {
                    "id": 2,
                    "isoCode": "BE"
                },
                "id": 81,
                "numEntries": 0,
                "validDays": 180,
                "stayDays": 90,
                "perVisitStayDays": false,
                "startValidDays": "entry",
                "entryValidityDays": 0,
                "useBounded": true,
                "definedProcessingDays": 90,
                "updatedAt": "2019-02-25T09:03:46+00:00"
            }
        ],
        "type": "evisa",
        "purpose": "tourism",
        "country": {
            "id": 209,
            "isoCode": "TR"
        },
        "visaForm": "/schema/TR/tourism/order.json",
        "maxDeliveryTime": 24,
        "averageDeliveryTime": 4,
        "exempt": false,
        "regularCountry": null,
        "alias": "TR_TOURISM",
        "publicAlias": null,
        "updatedAt": "2018-07-12T12:27:14+00:00"
    },
    "emails": [],
    "pdfs": [
        {
            "id": 40166,
            "name": "PDF_c04d1228-08df-4abb-a631-ca519f865811.pdf",
            "traveler": {
                "uuid": "c04d1228-08df-4abb-a631-ca519f865811"
            },
            "order": {
                "uuid": "3ae1b32a-71d2-47f2-8aa3-b2ff8efc63e6"
            },
            "createdAt": "2019-04-15T09:59:38+00:00"
        }
    ],
    "documents": [],
    "invoices": [],
    "seleniumVideos": [
        {
            "id": 155396,
            "url": "http://s3-eu-central-1.amazonaws.com/kc30ea1a-6y9b-a38c-1e64-t2u8d227g8a9/180257a7-3cad-807d-d8ac-c1fb56b9b922/play.html?056bc40ecdfd45efae8bc7070aa0003c",
            "traveler": {
                "uuid": "c04d1228-08df-4abb-a631-ca519f865811",
                "lastname": "kemeling",
                "passport": {
                    "documentType": "passport",
                    "passportNumber": "NX44CKRB0",
                    "passportIssueDate": "2018-10-29",
                    "passportExpiryDate": "2028-10-29"
                },
                "dateOfBirth": "1991-03-09",
                "nationality": "NL",
                "placeOfBirth": "zutphen",
                "allFirstNames": "ryan johan",
                "applicationId": "NTY-S529"
            },
            "stepOrder": 1,
            "stepType": "process",
            "success": true,
            "createdAt": "2019-04-15T09:53:27+00:00",
            "deletedAt": null
        }
    ],
    "createdAt": "2019-04-15T09:45:42+00:00",
    "updatedAt": "2019-04-15T09:59:38+00:00"
}');
    }

    /**
     * @throws \Exception
     *
     * @return Order
     */
    public static function createFromStdClass(): Order
    {
        return Order::fromStdClass(
            self::getStdClassObject()
        );
    }
}
