<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Symfony\Component\HttpFoundation\Request;
use Randock\Utils\Http\Exception\HttpException;

class QueueClient extends AbstractClient
{
    /**
     * @return array
     */
    public function getPassportQueue()
    {
        try {
            $response = $this->parseContentToArray(
                $this->request(
                    Request::METHOD_GET,
                    '/api/queues/passport.json'
                )
            );
        } catch (HttpException $exception) {
            throw $exception;
        }

        return $response;
    }

    /**
     * @return array
     */
    public function getPhotoQueue()
    {
        try {
            $response = $this->parseContentToArray(
                $this->request(
                    Request::METHOD_GET,
                    '/api/queues/photo.json'
                )
            );
        } catch (HttpException $exception) {
            throw $exception;
        }

        return $response;
    }

    /**
     * @param string $traveler
     * @param string $identifier
     */
    public function approvePassport(string $traveler, string $identifier)
    {
        try {
            $this->request(
                Request::METHOD_POST,
                sprintf(
                    '/api/queues/passport/%s.json',
                    $traveler
                ),
                [
                    'json' => [
                        'identifier' => $identifier,
                    ],
                ]
            );

            return;
        } catch (HttpException $exception) {
            throw $exception;
        }
    }

    /**
     * @param string $traveler
     * @param string $identifier
     */
    public function approvePhoto(string $traveler, string $identifier)
    {
        try {
            $this->request(
                Request::METHOD_POST,
                sprintf(
                    '/api/queues/photo/%s.json',
                    $traveler
                ),
                [
                    'json' => [
                        'identifier' => $identifier,
                    ],
                ]
            );

            return;
        } catch (HttpException $exception) {
            throw $exception;
        }
    }
}
