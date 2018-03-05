<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;

class ProvinceClient extends AbstractClient
{

    /**
     * @param string $countryCode
     * @return ResponseInterface
     */
    public function getProvinces(string $countryCode): ResponseInterface
    {
        $options['query'] = [
            "country" => $countryCode
        ];

        return $this->request(
                    Request::METHOD_GET,
                    '/api/provinces.json',
                    $options
                );

    }

}
