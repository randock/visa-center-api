<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Symfony\Component\HttpFoundation\Request;

class ProvinceClient extends AbstractClient
{
    /**
     * @param string $countryCode
     *
     * @return \stdClass
     */
    public function getProvinces(string $countryCode): \stdClass
    {
        $options['query'] = [
            'country' => $countryCode,
        ];

        return $this->toStdClass($this->request(
            Request::METHOD_GET,
            '/api/provinces.json',
            $options
                ));
    }
}
