<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Psr\Http\Message\ResponseInterface;
use Randock\Utils\Http\AbstractClient as CommonAbstractClient;
use Randock\VisaCenterApi\Definition\CredentialsProviderInterface;

abstract class AbstractClient extends CommonAbstractClient
{

    /**
     * @var CredentialsProviderInterface
     */
    private $credentialsProvider = null;

    /**
     * OrderClient constructor.
     *
     * @param string $baseUri
     * @param string $apiVersion
     * @param array  $auth
     */
    public function __construct(
        string $baseUri,
        string $apiVersion = '1.0',
        array $auth = null
    ) {
        $options = [
            'headers' => [
                'Accept' => sprintf(
                    'application/json;version=%s',
                    $apiVersion
                ),
                'Content-Type' => 'application/json',
            ],
            'auth' => $auth,
        ];

        parent::__construct($baseUri, $options);
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

    /**
     * @inheritdoc
     */
    protected function request(string $method, string $path, array $options = []): ResponseInterface
    {
        // check if we need to get credentials from a provider
        if ($this->credentialsProvider !== null) {
            $options['auth'] = $this->credentialsProvider->getCredentials();
        }

        return parent::request($method, $path, $options);

    }

    /**
     * @param CredentialsProviderInterface $credentialsProvider
     *
     * @return AbstractClient
     */
    public function setCredentialsProvider(
        CredentialsProviderInterface $credentialsProvider
    ): AbstractClient {
        $this->credentialsProvider = $credentialsProvider;

        return $this;
    }

}
