<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Randock\Utils\Uuid\UuidUtils;
use Randock\VisaCenterApi\Model\Order;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Randock\Utils\Http\Exception\HttpException;
use Randock\VisaCenterApi\CollectionApiResponse;
use Randock\VisaCenterApi\Exception\PdfNotFoundException;
use Randock\VisaCenterApi\Exception\OrderNotFoundException;
use Randock\VisaCenterApi\Exception\OrderContainsErrorsException;
use Randock\VisaCenterApi\Exception\OrderCommentContainsErrorException;
use Randock\VisaCenterApi\Exception\VisaCenterGetOrderFatalErrorException;
use Randock\VisaCenterApi\Exception\GovRegistrationContainsErrorsException;

class OrderClient extends AbstractClient
{
    /**
     * @param string $visaFormUri
     *
     * @return \stdClass
     */
    public function getOrderSchemaForm(string $visaFormUri): \stdClass
    {
        return $this->toStdClass(
            $this->request(
                Request::METHOD_GET,
                $visaFormUri
            )
        );
    }

    /**
     * @param int   $page
     * @param int   $limit
     * @param array $sort
     * @param array $filters
     * @param bool  $fetchMore
     * @param array $queryParams
     *
     * @return CollectionApiResponse
     */
    public function getOrders(int $page = 1, int $limit = 20, array $sort = [], array $filters = [], bool $fetchMore = false, $queryParams = []): CollectionApiResponse
    {
        // filters
        $filterParams = $filters['filterParams'] ?? [];
        $filterOperators = $filters['filterOperators'] ?? [];
        $filterValues = $filters['filterValues'] ?? [];

        $options = [
            'query' => [
                'page' => $page,
                'limit' => $limit,
                'orderParameter' => \array_keys($sort),
                'orderValue' => \array_values($sort),
                'filterParam' => $filterParams,
                'filterValue' => $filterValues,
                'filterOp' => $filterOperators,
            ],
        ];

        $options['query'] = \array_merge($options['query'], $queryParams);

        $response = new CollectionApiResponse(
            $this->toStdClass(
                $this->request(
                    Request::METHOD_GET,
                    '/api/orders.json',
                    $options
                )
            ),
            $this,
            $fetchMore
        );

        return $response;
    }

    /**
     * @param string $orderUuid
     *
     * @throws \Exception
     * @throws OrderNotFoundException
     *
     * @return mixed
     */
    public function getOrder(string $orderUuid)
    {
        try {
            $order = $this->toStdClass(
                $this->request(
                    Request::METHOD_GET,
                    \sprintf(
                        '/api/orders/%s.json',
                        $orderUuid
                    )
                )
            );

            if ($this->getTransform()) {
                $order = Order::fromStdClass($order);
            }

            return $order;
        } catch (HttpException $exception) {
            throw new OrderNotFoundException();
        }
    }

    /**
     * @param int $orderId
     *
     * @throws \Exception
     * @throws OrderNotFoundException
     *
     * @return mixed
     */
    public function getOrderByOrderId(int $orderId)
    {
        try {
            $order = $this->toStdClass(
                $this->request(
                    Request::METHOD_GET,
                    \sprintf(
                        '/api/orders/by-order-id/%s.json',
                        $orderId
                    )
                )
            );

            if ($this->getTransform()) {
                $order = Order::fromStdClass($order);
            }

            return $order;
        } catch (HttpException $exception) {
            throw new OrderNotFoundException();
        }
    }

    /**
     * @param string      $dateStart
     * @param string|null $dateFinish
     * @param array|null  $excludedStatuses
     * @param string|null $isoCode
     * @param string|null $dateGroup
     * @param string|null $company
     * @param string|null $domain
     * @param string|null $arrivalDate
     * @param string|null $arrivalDateLowerBoundary
     *
     * @return array
     */
    public function getOrdersStats(
        string $dateStart,
        string $dateFinish = null,
        array $excludedStatuses = null,
        string $isoCode = null,
        string $dateGroup = null,
        string $company = null,
        string $domain = null,
        string $arrivalDate = null,
        string $arrivalDateLowerBoundary = null
    ): array {
        try {
            $response = $this->request(
                Request::METHOD_GET,
                '/api/orders/stats.json',
                [
                    'query' => [
                        'dateStart' => $dateStart,
                        'dateFinish' => $dateFinish,
                        'excludedStatuses' => $excludedStatuses,
                        'isoCode' => $isoCode,
                        'dateGroup' => $dateGroup,
                        'company' => $company,
                        'domain' => $domain,
                        'arrivalDate' => $arrivalDate,
                        'arrivalDateLowerBoundary' => $arrivalDateLowerBoundary,
                    ],
                ]
            );

            return \json_decode($response->getBody()->getContents(), true);
        } catch (HttpException $exception) {
            throw $exception;
        }
    }

    /**
     * @param string $orderUuid
     * @param int    $page
     * @param int    $limit
     * @param array  $sort
     * @param bool   $fetchMore
     *
     * @throws OrderNotFoundException
     *
     * @return CollectionApiResponse
     */
    public function getOrderComments(string $orderUuid, int $page = 1, int $limit = 20, array $sort = [], bool $fetchMore = false): CollectionApiResponse
    {
        $options = [
            'query' => [
                'page' => $page,
                'limit' => $limit,
                'orderParameter' => \array_keys($sort),
                'orderValue' => \array_values($sort),
            ],
        ];

        try {
            return new CollectionApiResponse(
                $this->toStdClass(
                    $this->request(
                        Request::METHOD_GET,
                        \sprintf(
                            '/api/orders/%s/comments.json',
                            $orderUuid,
                            $options
                        )
                    )
                ),
                $this,
                $fetchMore
            );
        } catch (HttpException $exception) {
            throw new OrderNotFoundException();
        }
    }

    /**
     * @param string      $uuid
     * @param string      $type
     * @param string|null $message
     * @param string|null $longMessage
     *
     * @throws OrderCommentContainsErrorException
     * @throws OrderNotFoundException
     *
     * @return \stdClass
     */
    public function createOrderComment(string $uuid, string $type, string $message = null, string $longMessage = null): \stdClass
    {
        try {
            $response = $this->request(
                Request::METHOD_POST,
                \sprintf(
                    '/api/orders/%s/comments.json',
                    $uuid
                ),
                [
                    'json' => [
                        'type' => $type,
                        'message' => $message,
                        'longMessage' => $longMessage,
                    ],
                ]
            );

            return $this->requestLink($response->getHeader('Location')[0]);
        } catch (HttpException $exception) {
            if (404 === $exception->getStatusCode()) {
                throw new OrderNotFoundException();
            } elseif (400 === $exception->getStatusCode()) {
                throw new OrderCommentContainsErrorException(
                    \json_decode($exception->getBody())->errors
                );
            }
        }
    }

    /**
     * @param array $order
     *
     * @throws OrderContainsErrorsException
     * @throws VisaCenterGetOrderFatalErrorException
     *
     * @return string
     */
    public function createOrder(array $order): string
    {
        try {
            $response = $this->request(
                Request::METHOD_POST,
                '/api/orders.json',
                [
                    'json' => $order['order'],
                    'query' => [
                        'locale' => $order['locale'],
                        'visaType' => $order['visaTypeId'],
                        'domainName' => $order['domainName'],
                    ],
                    'timeout' => 60,
                ]
            );

            $orderUrlVisaCenter = \parse_url($response->getHeaders()['Location'][0]);

            return UuidUtils::getUuidFromString($orderUrlVisaCenter['path']);
        } catch (HttpException $exception) {
            if (400 === $exception->getStatusCode()) {
                throw new OrderContainsErrorsException((int) $exception->getStatusCode(), $exception->getBody(), $exception->getMessage());
            }
            if (500 === $exception->getStatusCode()) {
                throw new VisaCenterGetOrderFatalErrorException();
            }
            throw $exception;
        }
    }

    /**
     * @param array $order
     *
     * @throws OrderContainsErrorsException
     * @throws VisaCenterGetOrderFatalErrorException
     * @throws \Exception
     */
    public function updateOrder(array $order): void
    {
        try {
            $this->request(
                Request::METHOD_PATCH,
                \sprintf('/api/orders/%s.json', $order['orderUuid']),
                [
                    'json' => $order['order'],
                    'query' => [
                            'locale' => $order['locale'],
                        ],
                    'timeout' => 60,
                ]
            );
        } catch (HttpException $exception) {
            if (400 === $exception->getStatusCode()) {
                throw new OrderContainsErrorsException((int) $exception->getStatusCode(), $exception->getBody(), $exception->getMessage());
            }
            if (500 === $exception->getStatusCode()) {
                throw new VisaCenterGetOrderFatalErrorException();
            }
            throw $exception;
        }
    }

    /**
     * @param string $uuid
     * @param string $status
     * @param bool   $debug
     *
     * @throws OrderContainsErrorsException
     */
    public function changeOrderStatus(string $uuid, string $status, bool $debug = false): void
    {
        try {
            $this->request(
                Request::METHOD_POST,
                \sprintf('/api/orders/%s/status.json', $uuid),
                [
                    'json' => [
                        'status' => $status,
                        'debug' => $debug ? '1' : '0',
                    ],
                ]
            );
        } catch (HttpException $e) {
            throw new OrderContainsErrorsException((int) $e->getStatusCode(), $e->getBody(), $e->getMessage());
        }
    }

    /**
     * @param string $uuid
     */
    public function forceErrorStatus(string $uuid): void
    {
        try {
            $this->request(
                Request::METHOD_POST,
                \sprintf('/api/orders/%s/force/status/error.json', $uuid)
            );
        } catch (HttpException $e) {
            throw new OrderContainsErrorsException((int) $e->getStatusCode(), $e->getBody(), $e->getMessage());
        }
    }

    /**
     * @param int $id
     *
     * @return ResponseInterface
     */
    public function getEmail(int $id): ResponseInterface
    {
        return $this->request(
            Request::METHOD_GET,
            \sprintf('/api/emails/%d', $id),
            [
                'stream' => true,
            ]
        );
    }

    /**
     * @param int $id
     */
    public function republishLostEmail(int $id): void
    {
        $this->request(
            Request::METHOD_POST,
            \sprintf('/api/emails/%d/send-lost', $id)
        );
    }

    /**
     * @param int   $id
     * @param mixed $file
     *
     * @return ResponseInterface
     */
    public function getPdf(int $id, $file): ResponseInterface
    {
        return $this->request(
            Request::METHOD_GET,
            \sprintf('/api/pdfs/%d', $id),
            [
                'sink' => $file,
            ]
        );
    }

    /**
     * @param int $id
     *
     * @throws PdfNotFoundException
     */
    public function deletePdf(int $id): void
    {
        try {
            $this->request(
                Request::METHOD_DELETE,
                \sprintf('/api/pdfs/%d', $id)
            );
        } catch (HttpException $exception) {
            if (404 === $exception->getStatusCode()) {
                throw new PdfNotFoundException($exception->getMessage());
            }
            throw $exception;
        }
    }

    /**
     * @param string $orderId
     *
     * @return \stdClass
     */
    public function getOrderDocuments(string $orderId): \stdClass
    {
        try {
            $response = $this->toStdClass($this->request(
                Request::METHOD_GET,
                \sprintf('/api/orders/%s/documents.json', $orderId)
            ));
        } catch (HttpException $exception) {
            throw $exception;
        }

        return $response;
    }

    /**
     * @param string $orderUuid
     *
     * @return \stdClass
     */
    public function getAllDocuments(string $orderUuid): \stdClass
    {
        try {
            $response = $this->toStdClass($this->request(
                Request::METHOD_GET,
                \sprintf('/api/orders/%s/all/documents.json', $orderUuid)
            ));
        } catch (HttpException $exception) {
            throw $exception;
        }

        return $response;
    }

    /**
     * @param string $orderUuid
     * @param string $schemaPath
     *
     * @throws OrderNotFoundException
     *
     * @return \stdClass
     */
    public function getReusableDataOrder(string $orderUuid, string $schemaPath): \stdClass
    {
        try {
            return $this->toStdClass(
                $this->request(
                    Request::METHOD_GET,
                    \sprintf(
                        '/api/orders/%s/reusable-data.json',
                        $orderUuid
                    ),
                    [
                        'query' => [
                            'schemaPath' => $schemaPath,
                        ],
                    ]
                )
            );
        } catch (HttpException $exception) {
            throw new OrderNotFoundException();
        }
    }

    /**
     * @param string $orderUuid
     * @param array  $data
     * @param string $locale
     */
    public function createGovRegistration(string $orderUuid, array $data, string $locale): void
    {
        try {
            $this->request(
                Request::METHOD_POST,
                \sprintf(
                    '/api/orders/%s/gov-registration.json',
                    $orderUuid
                ),
                [
                    'json' => $data,
                    'query' => [
                        'locale' => $locale,
                    ],
                ]
            );
        } catch (HttpException $exception) {
            throw $exception;
        }
    }

    /**
     * @param string $orderUuid
     * @param array  $data
     * @param string $locale
     *
     * @throws OrderNotFoundException
     */
    public function validateGovRegistration(string $orderUuid, array $data, string $locale): void
    {
        try {
            $this->request(
                Request::METHOD_POST,
                \sprintf(
                    '/api/orders/%s/validate-gov-registration.json',
                    $orderUuid
                ),
                [
                    'json' => $data,
                    'query' => [
                        'locale' => $locale,
                    ],
                ]
            );
        } catch (HttpException $exception) {
            if (404 === $exception->getStatusCode()) {
                throw new OrderNotFoundException();
            } elseif (400 === $exception->getStatusCode()) {
                throw new GovRegistrationContainsErrorsException((int) $exception->getStatusCode(), $exception->getBody(), $exception->getMessage());
            }
        }
    }

    /**
     * @param string      $dateStart
     * @param string|null $dateFinish
     * @param array|null  $excludedStatuses
     * @param string|null $isoCode
     * @param string|null $dateGroup
     * @param string|null $company
     * @param string|null $domain
     *
     * @return int
     */
    public function getTotalVisasSold(
        string $dateStart,
        string $dateFinish = null,
        array $excludedStatuses = null,
        string $isoCode = null,
        string $dateGroup = null,
        string $company = null,
        string $domain = null
    ): int {
        try {
            $response = $this->request(
                Request::METHOD_GET,
                '/api/orders/stats/total/visas/sold.json',
                [
                    'query' => [
                        'dateStart' => $dateStart,
                        'dateFinish' => $dateFinish,
                        'excludedStatuses' => $excludedStatuses,
                        'isoCode' => $isoCode,
                        'dateGroup' => $dateGroup,
                        'company' => $company,
                        'domain' => $domain,
                    ],
                ]
            );

            return (int) $response->getBody()->getContents();
        } catch (HttpException $exception) {
            throw $exception;
        }
    }

    /**
     * @param array $orderUuidList
     *
     * @return array
     */
    public function getOrdersStatuses(array $orderUuidList): array
    {
        $response =
            $this->request(
                Request::METHOD_POST,
                '/api/orders/statuses.json',
                [
                    'json' => ['uuids' => $orderUuidList],
                    'timeout' => 120,
                ]
            );
        $ordersStatuses = \json_decode($response->getBody()->getContents());

        $mappedOrdersStatuses = [];
        foreach ($ordersStatuses as $orderStatus) {
            $mappedOrdersStatuses[$orderStatus->uuid->uuid] = $orderStatus->status;
        }

        return $mappedOrdersStatuses;
    }

    /**
     * @param array $orderUuidList
     *
     * @return array
     */
    public function getOrdersByUuids(array $orderUuidList): array
    {
        $response =
            $this->request(
                Request::METHOD_POST,
                '/api/orders/by-uuids.json',
                [
                    'json' => ['uuids' => $orderUuidList],
                    'timeout' => 120,
                ]
            );
        $orders = \json_decode($response->getBody()->getContents());

        $mappedOrders = [];
        foreach ($orders as $order) {
            $mappedOrders[$order->uuid] = $order;
        }

        return $mappedOrders;
    }

    /**
     * @param string $orderUuid
     *
     * @throws OrderNotFoundException
     *
     * @return \stdClass
     */
    public function getProcessingDate(string $orderUuid): \stdClass
    {
        try {
            return $this->toStdClass(
                $this->request(
                    Request::METHOD_GET,
                    \sprintf(
                        '/api/orders/%s/processing-date.json',
                        $orderUuid
                    )
                )
            );
        } catch (HttpException $exception) {
            throw new OrderNotFoundException();
        }
    }

    /**
     * @param array $orderIds
     * @param int   $visaType
     *
     * @throws OrderNotFoundException
     *
     * @return array
     */
    public function getReusableData(array $orderIds, int $visaType): array
    {
        try {
            $response = $this->request(
                Request::METHOD_POST,
                '/api/orders/reusable-data.json',
                [
                    'query' => [
                        'visaType' => $visaType,
                    ],
                    'json' => [
                        'orderIds' => $orderIds,
                    ],
                ]
            );

            $contents = \json_decode($response->getBody()->getContents());
            $mappedData = [];

            foreach ($contents as $data) {
                $mappedData[] = $data;
            }

            return $mappedData;
        } catch (HttpException $exception) {
            throw new OrderNotFoundException();
        }
    }

    public function getOrdersWithNotHandledDocumentIssues()
    {
        $response = $this->request(
            Request::METHOD_GET,
            '/api/orders/document-issues'
        );

        return \json_decode($response->getBody()->getContents());
    }
}
