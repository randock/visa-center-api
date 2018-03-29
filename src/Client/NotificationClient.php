<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Randock\Utils\Http\Exception\HttpException;
use Randock\VisaCenterApi\CollectionApiResponse;
use Randock\VisaCenterApi\Exception\NotificationNotFoundException;

class NotificationClient extends AbstractClient
{
    /**
     * @param int   $page
     * @param int   $limit
     * @param bool  $fetchMore
     * @param array $queryParams
     *
     * @return CollectionApiResponse
     */
    public function getNotifications(int $page = 1, int $limit = 20, bool $fetchMore = false, $queryParams = []): CollectionApiResponse
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
                    Request::METHOD_GET,
                    '/api/notifications.json',
                    $options
                )
            ),
            $this,
            $fetchMore
        );

        return $response;
    }

    /**
     * @param array $data
     *
     * @throws \Exception
     */
    public function createNotification(array $data): void
    {
        try {
            $this->request(
                Request::METHOD_POST,
                '/api/notifications.json',
                [
                    $data,
                ]
            );
        } catch (HttpException $exception) {
            if (Response::HTTP_BAD_REQUEST === $exception->getStatusCode()) {
                $this->throwFormErrorsException($exception);
            }

            throw $exception;
        }
    }

    /**
     * @param int $notificationId
     *
     * @throws \Exception
     */
    public function notificationRead(int $notificationId): void
    {
        try {
            $this->request(
                Request::METHOD_PATCH,
                sprintf(
                    '/api/notifications/%d/read.json',
                    $notificationId
                )
            );
        } catch (HttpException $exception) {
            // custom exception if the visaType is not found
            if (Response::HTTP_NOT_FOUND === $exception->getStatusCode()) {
                throw new NotificationNotFoundException();
            }

            throw $exception;
        }
    }
}
