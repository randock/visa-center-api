<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Randock\VisaCenterApi\Exception\FileCanNotBeSentException;
use Symfony\Component\HttpFoundation\Request;
use Randock\Utils\Http\Exception\HttpException;

class FileClient extends AbstractClient
{


    /**
     * @param string $fileName
     * @param string $raw
     * @throws FileCanNotBeSentException
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
            throw new FileCanNotBeSentException();
        }
    }


}
