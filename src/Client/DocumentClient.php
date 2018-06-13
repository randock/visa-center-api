<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use GuzzleHttp\RequestOptions;
use Symfony\Component\HttpFoundation\Request;
use Randock\Utils\Http\Exception\HttpException;
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
}
