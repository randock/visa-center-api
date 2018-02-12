<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Randock\Utils\Http\Exception\HttpException;
use Randock\VisaCenterApi\CollectionApiResponse;
use Randock\VisaCenterApi\Exception\VisaTypeNotFoundException;

class FileClient extends AbstractClient
{


    /**
     * @param string $fileName
     * @param string $raw
     * @throws \Randock\Utils\Http\Exception\FormErrorsException
     */
    public function sendFile(string $fileName, string $raw)
    {
        try {
            $this->request(
                Request::METHOD_POST,
                '/api/manualfiles.json',
                [
                    'fileName' => $fileName,
                    'fileRaw' => $raw
                ]
            );
        } catch (HttpException $exception) {
            if (Response::HTTP_BAD_REQUEST === $exception->getStatusCode()) {
                $this->throwFormErrorsException($exception);
            }

            throw $exception;
        }
    }


}
