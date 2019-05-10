<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Model;

use Randock\VisaCenterApi\Model\ValueObject\OrderDetails;

/**
 * Class Order.
 */
class Order
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_HOLD = 'hold';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_SENT_TO_CUSTOMER = 'sent_to_customer';
    public const STATUS_SENT_ISSUES_TO_CUSTOMER = 'sent_issues_to_customer';
    public const STATUS_DELETED = 'deleted';
    public const STATUS_PROCESSED = 'processed';
    
    /**
     * @var string
     */
    private $uuid;
    /**
     * @var int
     */
    private $orderId;
    /**
     * @var \DateTime
     */
    private $arrivalDate;
    /**
     * @var \stdClass
     */
    private $address;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $phone;
    /**
     * @var VisaType
     */
    private $visaType;
    /**
     * @var string
     */
    private $locale;
    /**
     * @var \stdClass
     */
    private $domain;
    /**
     * @var array
     */
    private $travelers;
    /**
     * @var string
     */
    private $status;
    /**
     * @var \DateTime
     */
    private $createdAt;
    /**
     * @var \DateTime
     */
    private $updatedAt;
    /**
     * @var bool
     */
    private $validated;
    /**
     * @var string|null
     */
    private $govRegistrationId;
    /**
     * @var bool
     */
    private $govRegistrationCompleted;
    /**
     * @var array
     */
    private $transactions;
    /**
     * @var array
     */
    private $images;
    /**
     * @var array
     */
    private $emails;
    /**
     * @var array
     */
    private $pdfs;
    /**
     * @var array
     */
    private $documents;
    /**
     * @var array
     */
    private $invoices;
    /**
     * @var array
     */
    private $seleniumVideos;
    /**
     * @var array
     */
    private $issues;
    /**
     * @var OrderDetails|null
     */
    private $orderDetails;

    /**
     * Order constructor.
     *
     * @param string            $uuid
     * @param int               $orderId
     * @param \DateTime         $arrivalDate
     * @param \stdClass         $address
     * @param string            $email
     * @param string            $phone
     * @param VisaType          $visaType
     * @param string            $locale
     * @param \stdClass         $domain
     * @param array             $travelers
     * @param string            $status
     * @param \DateTime         $createdAt
     * @param \DateTime         $updatedAt
     * @param bool              $validated
     * @param string|null       $govRegistrationId
     * @param bool              $govRegistrationCompleted
     * @param array             $transactions
     * @param array             $images
     * @param array             $emails
     * @param array             $pdfs
     * @param array             $documents
     * @param array             $invoices
     * @param array             $seleniumVideos
     * @param array             $issues
     * @param OrderDetails|null $orderDetails
     */
    private function __construct(
        string $uuid,
        int $orderId,
        \DateTime $arrivalDate,
        \stdClass $address,
        string $email,
        string $phone,
        VisaType $visaType,
        string $locale,
        \stdClass $domain,
        array $travelers,
        string $status,
        \DateTime $createdAt,
        \DateTime $updatedAt,
        bool $validated = false,
        string $govRegistrationId = null,
        bool $govRegistrationCompleted = false,
        array $transactions = [],
        array $images = [],
        array $emails = [],
        array $pdfs = [],
        array $documents = [],
        array $invoices = [],
        array $seleniumVideos = [],
        array $issues = [],
        OrderDetails $orderDetails = null
    ) {
        $this->uuid = $uuid;
        $this->orderId = $orderId;
        $this->arrivalDate = $arrivalDate;
        $this->address = $address;
        $this->email = $email;
        $this->phone = $phone;
        $this->visaType = $visaType;
        $this->locale = $locale;
        $this->domain = $domain;
        $this->travelers = $travelers;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->validated = $validated;
        $this->govRegistrationId = $govRegistrationId;
        $this->govRegistrationCompleted = $govRegistrationCompleted;
        $this->transactions = $transactions;
        $this->images = $images;
        $this->emails = $emails;
        $this->pdfs = $pdfs;
        $this->documents = $documents;
        $this->invoices = $invoices;
        $this->seleniumVideos = $seleniumVideos;
        $this->issues = $issues;
        $this->orderDetails = $orderDetails;
    }

    /**
     * @param \stdClass $data
     *
     * @throws \Exception
     *
     * @return Order
     */
    public static function fromStdClass(\stdClass $data)
    {
        $travelers = [];

        foreach ($data->travelers as $traveler) {
            $travelers[] = Traveler::fromStdClass($traveler);
        }

        $order = new self(
            $data->uuid,
            $data->orderId,
            new \DateTime($data->arrivalDate),
            $data->address,
            $data->email,
            $data->phone,
            VisaType::fromStdClass($data->visaType),
            $data->locale,
            $data->domain,
            $travelers,
            $data->status,
            new \DateTime($data->createdAt),
            new \DateTime($data->updatedAt),
            $data->validated,
            $data->govRegistrationId ?? null,
            $data->govRegistrationCompleted ?? false,
            $data->transactions ?? [],
            $data->images ?? [],
            $data->emails ?? [],
            $data->pdfs ?? [],
            $data->documents ?? [],
            $data->invoices ?? [],
            $data->seleniumVideos ?? [],
            $data->issues ?? [],
            new OrderDetails($data->orderDetails ?? null)
        );

        return $order;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * @return \DateTime
     */
    public function getArrivalDate(): \DateTime
    {
        return $this->arrivalDate;
    }

    /**
     * @return \stdClass
     */
    public function getAddress(): \stdClass
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @return VisaType
     */
    public function getVisaType(): VisaType
    {
        return $this->visaType;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @return \stdClass
     */
    public function getDomain(): \stdClass
    {
        return $this->domain;
    }

    /**
     * @return array
     */
    public function getTravelers(): array
    {
        return $this->travelers;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return bool
     */
    public function isValidated(): bool
    {
        return $this->validated;
    }

    /**
     * @return string|null
     */
    public function getGovRegistrationId(): ?string
    {
        return $this->govRegistrationId;
    }

    /**
     * @return bool
     */
    public function isGovRegistrationCompleted(): bool
    {
        return $this->govRegistrationCompleted;
    }

    /**
     * @return array
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @return array
     */
    public function getEmails(): array
    {
        return $this->emails;
    }

    /**
     * @return array
     */
    public function getPdfs(): array
    {
        return $this->pdfs;
    }

    /**
     * @return array
     */
    public function getDocuments(): array
    {
        return $this->documents;
    }

    /**
     * @return array
     */
    public function getInvoices(): array
    {
        return $this->invoices;
    }

    /**
     * @return array
     */
    public function getSeleniumVideos(): array
    {
        return $this->seleniumVideos;
    }

    /**
     * @return array
     */
    public function getIssues(): array
    {
        return $this->issues;
    }

    /**
     * @return OrderDetails|null
     */
    public function getOrderDetails(): ?OrderDetails
    {
        return $this->orderDetails;
    }
}
