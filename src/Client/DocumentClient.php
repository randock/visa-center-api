<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use GuzzleHttp\RequestOptions;
use Symfony\Component\HttpFoundation\Request;
use Randock\Utils\Http\Exception\HttpException;
use Randock\VisaCenterApi\Exception\FileCanNotBeSentException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DocumentClient extends AbstractClient
{
    /**
     *  Tem file directory prefix.
     */
    public const TEMP_NAME_PREFIX = '/tmpPhotoPrefix';

    /**
     * @param int    $fileId
     * @param string $tmpFileName
     *
     * @return string
     */
    public function getFile(int $fileId, string $tmpFileName): string
    {
        try {
            $this->request(
                Request::METHOD_GET,
                sprintf('api/files/%d', $fileId),
                [
                    RequestOptions::SINK => $tmpFileName,
                    RequestOptions::TIMEOUT => 30.0,
                ]
            );

            return $tmpFileName;
        } catch (HttpException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * @param string $objectId
     * @param string $type
     * @param string $identifier
     * @param string $filePath
     *
     * @throws FileCanNotBeSentException
     */
    public function uploadDocument(string $objectId, string $type, string $identifier, string $filePath)
    {
        try {
            $this->request(
                Request::METHOD_POST,
                '/api/files.json',
                [
                    'multipart' => [
                        [
                            'name' => 'objectId',
                            'contents' => $objectId,
                        ],
                        [
                            'name' => 'type',
                            'contents' => $type,
                        ],
                        [
                            'name' => 'identifier',
                            'contents' => $identifier,
                        ],
                        [
                            'name' => 'rawDocument',
                            'contents' => fopen($filePath, 'r'),
                        ],
                    ],
                ]
            );
        } catch (HttpException $exception) {
            throw new FileCanNotBeSentException();
        }
    }

    /**
     * @param string $documentId
     * @param string $type
     * @param string $filePath
     *
     * @throws FileCanNotBeSentException
     */
    public function uploadDocumentCropped(string $documentId, string $type, string $filePath = null): void
    {
        if (null!== $filePath) {
            $file = [
                'name' => 'rawDocument',
                'contents' => null === $filePath ? fopen($filePath, 'r') : null,
            ];
        }

        try {
            $this->request(
                Request::METHOD_POST,
                '/api/files/cropped.json',
                [
                    'multipart' => [
                        [
                            'name' => 'documentId',
                            'contents' => $documentId,
                        ],
                        [
                            'name' => 'ratio',
                            'contents' => $type,
                        ],
                        $file ?? []
                    ]
                ]
            );
        } catch (HttpException $exception) {
            throw new FileCanNotBeSentException();
        }
    }
}
