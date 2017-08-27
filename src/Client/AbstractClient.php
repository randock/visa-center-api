<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Psr\Http\Message\ResponseInterface;
use Randock\Utils\Http\AbstractClient as CommonAbstractClient;

abstract class AbstractClient extends CommonAbstractClient
{
    /**
     * OrderClient constructor.
     *
     * @param string $base_uri
     * @param string $apiVersion
     * @param array  $auth
     */
    public function __construct(
        string $base_uri,
        string $apiVersion = '1.0',
        array $auth
    ) {
        $options = [
            'headers' => [
                'Accept' => sprintf(
                    'application/json;version=%s',
                    $apiVersion
                ),
                'content_type' => 'application/json',
            ],
            'auth' => $auth,
        ];

        parent::__construct($base_uri, $options);
    }

    /**
     * @param string $link
     *
     * @return mixed
     */
    public function requestLink(string $link)
    {
        return $this->toStdClass(
            $this->request(
                'GET',
                $link
            )
        );
    }

    /**
     * @param ResponseInterface $response
     *
     * @return \stdClass
     */
    public function toStdClass(ResponseInterface $response): \stdClass
    {
        return json_decode($response->getBody()->getContents());
    }
}
