<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Randock\Utils\Uuid\UuidUtils;
use Randock\Utils\Http\Exception\HttpException;
use Randock\VisaCenterApi\CollectionApiResponse;
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
                'GET',
                $visaFormUri
            )
        );
    }

    /**
     * @param int   $page
     * @param int   $limit
     * @param bool  $fetchMore
     * @param array $queryParams
     *
     * @return CollectionApiResponse
     */
    public function getOrders(int $page = 1, int $limit = 20, array $sort = [], bool $fetchMore = false, $queryParams = []): CollectionApiResponse
    {
        $options = [
            'query' => [
                'page' => $page,
                'limit' => $limit,
                'orderParameter' => array_keys($sort),
                'orderValue' => array_values($sort),
            ],
        ];

        $options['query'] = array_merge($options['query'], $queryParams);

        $response = new CollectionApiResponse(
            $this->toStdClass(
                $this->request(
                    'GET',
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
                    'GET',
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
                        'GET',
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
     * @param string $uuid
     * @param string $longMessage
     *
     * @return string
     */
    public function createOrderComment(string $uuid, string $type, string $message = null, string $longMessage = null): \stdClass
    {
        try {
            $response = $this->request(
                'POST',
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
                'POST',
                '/api/orders.json',
                [
                    'json' => $order['order'],
                    'query' => [
                        'locale' => $order['locale'],
                        'visaType' => $order['visaTypeId'],
                    ],
                ]
            );

            $orderUrlVisaCenter = parse_url($response->getHeaders()['Location'][0]);

            return UuidUtils::getUuidFromString($orderUrlVisaCenter['path']);
        } catch (HttpException $exception) {
            if ($exception->getStatusCode() === 400) {
                throw new OrderContainsErrorsException($exception->getBody());
            }
            if ($exception->getStatusCode() === 500) {
                throw new VisaCenterGetOrderFatalErrorException();
            }
            throw $exception;
        }
    }

    /**
     * @param array $order
     * @return string
     * @throws OrderContainsErrorsException
     * @throws VisaCenterGetOrderFatalErrorException
     * @throws \Exception
     */
    public function updateOrder(array $order): string
    {
        try {
            $this->request('PATCH', sprintf('/api/orders/%s.json', $order['orderUuid']), ['json' => $order['order'], 'query' => ['locale' => $order['locale']]]);

            return $order['orderUuid'];
        } catch (HttpException $exception) {
            if ($exception->getStatusCode() === 400) {
                throw new OrderContainsErrorsException($exception->getBody());
            }
            if ($exception->getStatusCode() === 500) {
                throw new VisaCenterGetOrderFatalErrorException();
            }
            throw $exception;
        }
    }
}
