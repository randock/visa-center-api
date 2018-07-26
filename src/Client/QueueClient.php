<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Symfony\Component\HttpFoundation\Request;
use Randock\Utils\Http\Exception\HttpException;

class QueueClient extends AbstractClient
{
    /**
     * @param bool $revision
     * @return array
     */
    public function getPassportQueue(bool $revision = false)
    {
        try {
            $response = $this->parseContentToArray(
                $this->request(
                    Request::METHOD_GET,
                    '/api/queues/passport.json',
                    [
                        "query" => [
                            "revision" => $revision
                        ]
                    ]
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
     * @param array $revisionChanges
     */
    public function approvePassport(string $traveler, string $identifier, array $revisionChanges = [])
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
                        'revisionChanges' => $revisionChanges
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
     * @param string $passport
     */
    public function validatePassport(string $traveler, string $identifier, string $passport)
    {
        try {
            $this->request(
                Request::METHOD_POST,
                sprintf(
                    '/api/queues/passport/validate/%s.json',
                    $traveler
                ),
                [
                    'json' => [
                        'identifier' => $identifier,
                        'passport' => $passport,
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
     * @param string $photo
     */
    public function cropPhoto(string $traveler, string $identifier, string $photo)
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
                        'photo' => $photo,
                    ],
                ]
            );

            return;
        } catch (HttpException $exception) {
            throw $exception;
        }
    }
}
