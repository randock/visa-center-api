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
     * @param bool        $ocrRevision
     * @param string|null $orderUuid
     *
     * @return array
     */
    public function getPassportQueue(bool $revision = false, bool $ocrRevision = false, string $orderUuid = null): array
    {
        $query['revision'] = $revision;
        $query['ocrRevision'] = $ocrRevision;

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
     * @param string $traveler
     * @param string $identifier
     * @param array  $revisionChanges
     */
    public function approveOcrPassportRevision(string $traveler, string $identifier, array $revisionChanges = []): void
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
     * @param array $documentsToBeApproved
     *
     * @throws FileCanNotBeSentException
     */
    public function approveDocumentsCrop(array $documentsToBeApproved): void
    {
        try {
            $this->request(
                Request::METHOD_POST,
                '/api/queues/documents/approve/crop.json',
                ['json' => ['documents' => $documentsToBeApproved],
                ]
            );
        } catch (HttpException $exception) {
            throw new FileCanNotBeSentException();
        }
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
}
