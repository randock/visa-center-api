<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Randock\Utils\Uuid\UuidUtils;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Randock\Utils\Http\Exception\HttpException;
use Randock\VisaCenterApi\CollectionApiResponse;
use Randock\VisaCenterApi\Exception\PdfNotFoundException;
use Randock\VisaCenterApi\Exception\OrderNotFoundException;
use Randock\VisaCenterApi\Exception\OrderContainsErrorsException;
use Randock\VisaCenterApi\Exception\OrderCommentContainsErrorException;
use Randock\VisaCenterApi\Exception\VisaCenterGetOrderFatalErrorException;

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
                'orderParameter' => array_keys($sort),
                'orderValue' => array_values($sort),
                'filterParam' => $filterParams,
                'filterValue' => $filterValues,
                'filterOp' => $filterOperators,
            ],
        ];

        $options['query'] = array_merge($options['query'], $queryParams);

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
     * @throws OrderNotFoundException
     *
     * @return \stdClass
     */
    public function getOrder(string $orderUuid): \stdClass
    {
        try {
            return $this->toStdClass(
                $this->request(
                    Request::METHOD_GET,
                    sprintf(
                        '/api/orders/%s.json',
                        $orderUuid
                    )
                )
            );
        } catch (HttpException $exception) {
            throw new OrderNotFoundException();
        }
    }

    /**
     * @param string $dateStart
     * @param string|null $dateFinish
     * @param array|null $excludedStatuses
     * @param string|null $isoCode
     * @return \stdClass
     */
    public function getOrdersStats(string $dateStart, string $dateFinish = null, array $excludedStatuses = null, string $isoCode = null): \stdClass
    {
        try {
            return $this->toStdClass(
                $this->request(
                    Request::METHOD_GET,
                    '/api/orders/stats.json',
                    [
                        "query"=> [
                            "dateStart" => $dateStart,
                            "dateFinish" => $dateFinish,
                            "excludedStatuses" => $excludedStatuses,
                            "isoCode" => $isoCode
                        ]
                    ]
                )
            );
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
                'orderParameter' => array_keys($sort),
                'orderValue' => array_values($sort),
            ],
        ];

        try {
            return new CollectionApiResponse(
                $this->toStdClass(
                    $this->request(
                        Request::METHOD_GET,
                        sprintf(
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
                sprintf(
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
            if ($exception->getStatusCode() === 404) {
                throw new OrderNotFoundException();
            } elseif ($exception->getStatusCode() === 400) {
                throw new OrderCommentContainsErrorException(
                    json_decode($exception->getBody())->errors
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
                ]
            );

            $orderUrlVisaCenter = parse_url($response->getHeaders()['Location'][0]);

            return UuidUtils::getUuidFromString($orderUrlVisaCenter['path']);
        } catch (HttpException $exception) {
            if ($exception->getStatusCode() === 400) {
                throw new OrderContainsErrorsException((int) $exception->getStatusCode(), $exception->getBody(), $exception->getMessage());
            }
            if ($exception->getStatusCode() === 500) {
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
            $this->request(Request::METHOD_PATCH, sprintf('/api/orders/%s.json', $order['orderUuid']), ['json' => $order['order'], 'query' => ['locale' => $order['locale']]]);
        } catch (HttpException $exception) {
            if ($exception->getStatusCode() === 400) {
                throw new OrderContainsErrorsException((int) $exception->getStatusCode(), $exception->getBody(), $exception->getMessage());
            }
            if ($exception->getStatusCode() === 500) {
                throw new VisaCenterGetOrderFatalErrorException();
            }
            throw $exception;
        }
    }

    /**
     * @param string $uuid
     * @param string $status
     */
    public function changeOrderStatus(string $uuid, string $status): void
    {
        try {
            $this->request(
                Request::METHOD_POST,
                sprintf('/api/orders/%s/status.json', $uuid),
                [
                    'json' => ['status' => $status],
                ]
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
            sprintf('/api/emails/%d', $id),
            [
                'stream' => true,
            ]
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
            sprintf('/api/pdfs/%d', $id),
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
                sprintf('/api/pdfs/%d', $id)
            );
        }catch (HttpException $exception){
            if ($exception->getStatusCode() === 404) {
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
                sprintf('/api/orders/%s/documents.json', $orderId)
            ));
        } catch (HttpException $exception) {
            throw $exception;
        }

        return $response;
    }
}
