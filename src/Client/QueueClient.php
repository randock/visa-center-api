<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Symfony\Component\HttpFoundation\Request;
use Randock\Utils\Http\Exception\HttpException;
use Randock\VisaCenterApi\Exception\FileCanNotBeSentException;

class QueueClient extends AbstractClient
{
    /**
     * @param bool        $revision
     * @param string|null $orderUuid
     *
     * @return array
     */
    public function getPassportQueue(bool $revision = false, string $orderUuid = null): array
    {
        $query = ['revision' => $revision];

        if (null !== $orderUuid) {
            $query['orderUuid'] = $orderUuid;
        }

        try {
            $response = $this->parseContentToArray(
                $this->request(
                    Request::METHOD_GET,
                    '/api/queues/passport.json',
                    [
                        'query' => $query,
                    ]
                )
            );
        } catch (HttpException $exception) {
            throw $exception;
        }

        return $response;
    }

    /**
     * @param bool        $revision
     * @param string|null $orderUuid
     *
     * @return array
     */
    public function getPhotoQueue(bool $revision = false, string $orderUuid = null): array
    {
        $query = ['revision' => $revision];

        if (null !== $orderUuid) {
            $query['orderUuid'] = $orderUuid;
        }

        try {
            $response = $this->parseContentToArray(
                $this->request(
                    Request::METHOD_GET,
                    '/api/queues/photo.json',
                    [
                        'query' => $query,
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
    public function getQueuesCount(): array
    {
        try {
            $response = $this->parseContentToArray(
                $this->request(
                    Request::METHOD_GET,
                    '/api/queues/count.json'
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
     * @param array  $revisionChanges
     */
    public function approvePassport(string $traveler, string $identifier, array $revisionChanges = []): void
    {
        try {
            $this->request(
                Request::METHOD_POST,
                \sprintf(
                    '/api/queues/passport/%s.json',
                    $traveler
                ),
                [
                    'json' => [
                        'identifier' => $identifier,
                        'revisionChanges' => $revisionChanges,
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
    public function validatePassport(string $traveler, string $identifier, string $passport): void
    {
        try {
            $this->request(
                Request::METHOD_POST,
                \sprintf(
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
    public function cropPhoto(string $traveler, string $identifier, string $photo): void
    {
        try {
            $this->request(
                Request::METHOD_POST,
                \sprintf(
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

    /**
     * @param array $documentsToBeApproved
     *
     * @throws FileCanNotBeSentException
     */
    public function approveDocumentsCrop(array $documentsToBeApproved): void
    {
        try {
            $this->request(
                Request::METHOD_POST,
                '/api/queues/photo/approve/crop.json',
                ['json' => ['documents' => $documentsToBeApproved],
                ]
            );
        } catch (HttpException $exception) {
            throw new FileCanNotBeSentException();
        }
    }
}
