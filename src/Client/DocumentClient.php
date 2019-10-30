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
     * @param int         $fileId
     * @param string      $tmpFileName
     * @param string|null $ratio
     * @param string|null $countryCode
     *
     * @return string
     */
    public function getFile(int $fileId, string $tmpFileName, string $ratio = null, string $countryCode = null): string
    {
        if (null !== $ratio) {
            $query = ['ratio' => $ratio];
        }

        if (null !== $countryCode) {
            $query = ['countryCode' => $countryCode];
        }

        try {
            $this->request(
                Request::METHOD_GET,
                \sprintf('api/files/%d', $fileId),
                [
                    'query' => $query ?? [],
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
    public function uploadDocument(string $objectId, string $type, string $identifier, string $filePath): void
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
                            'contents' => \fopen($filePath, 'r'),
                        ],
                    ],
                ]
            );
        } catch (HttpException $exception) {
            throw new FileCanNotBeSentException();
        }
    }

    /**
     * @param int         $documentId
     * @param string      $ratio
     * @param int|null    $minWidth
     * @param string|null $filePath
     * @param bool        $checked
     *
     * @throws FileCanNotBeSentException
     */
    public function uploadDocumentCropped(int $documentId, string $ratio, int $minWidth = null, string $filePath = null, bool $checked = false): void
    {
        $multipart =
            [
                [
                    'name' => 'documentId',
                    'contents' => $documentId,
                ],
                [
                    'name' => 'ratio',
                    'contents' => $ratio,
                ],
            ];

        if (null !== $minWidth) {
            $multipart = \array_merge($multipart,
                [
                    [
                        'name' => 'minWidth',
                        'contents' => $minWidth,
                    ],
                ]);
        }

        if (null !== $filePath) {
            $multipart = \array_merge($multipart,
                [
                    [
                        'name' => 'rawDocument',
                        'contents' => \fopen($filePath, 'r'),
                    ],
                    [
                        'name' => 'checked',
                        'contents' => $checked,
                    ],
                ]);
        }

        try {
            $this->request(
                Request::METHOD_POST,
                '/api/files/cropped.json',
                ['multipart' => $multipart]
            );
        } catch (HttpException $exception) {
            throw new FileCanNotBeSentException();
        }
    }

    /**
     * @param int    $documentId
     * @param string $issue
     */
    public function rejectDocument(int $documentId, string $issue): void
    {
        try {
            $this->request(
                Request::METHOD_POST,
                \sprintf('api/files/reject/%d', $documentId),
                [
                    'json' => [
                        'issue' => $issue,
                    ],
                ]
            );
        } catch (HttpException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
