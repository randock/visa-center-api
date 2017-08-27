<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Randock\Utils\Uuid\UuidUtils;
use Randock\VisaCenterApi\CollectionApiResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
    public function getOrders(int $page = 1, int $limit = 20, bool $fetchMore = false, $queryParams = []): CollectionApiResponse
    {
        $options = [
            'query' => [
                    'page' => $page,
                    'limit' => $limit,
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
        } catch (HttpException $e) {
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
                    'orderParameter' => key($sort),
                    'orderValue' => current($sort),
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
        } catch (HttpException $e) {
            throw new OrderNotFoundException();
        }
    }

    /**
     * @param string $uuid
     * @param string $longMessage
     *
     * @return string
     */
    public function createOrderComment(string $uuid, string $type, string $message = null, string $longMessage = null): string
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
                        'longMessage' => $longMessage
                    ],
                ]
            );

            return $this->requestLink($response->getHeader('Location')[0]);
        } catch (HttpException $e) {
            if ($e->getStatusCode() === 404) {
                throw new OrderNotFoundException();
            } elseif ($e->getStatusCode() === 400) {
                throw new OrderCommentContainsErrorException(
                    $e->getMessage()
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
        } catch (HttpException $e) {
            if ($e->getStatusCode() === 400) {
                throw new OrderContainsErrorsException($e->getMessage());
            }
            if ($e->getStatusCode() === 500) {
                throw new VisaCenterGetOrderFatalErrorException();
            }
            throw $e;
        }
    }
}
