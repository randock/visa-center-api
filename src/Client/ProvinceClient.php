<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Symfony\Component\HttpFoundation\Request;
use Randock\Utils\Http\Exception\HttpException;
use Randock\VisaCenterApi\Exception\ProvincesNotFoundException;

class ProvinceClient extends AbstractClient
{
    /**
     * @param string $countryCode
     *
     * @throws ProvincesNotFoundException
     *
     * @return \stdClass
     */
    public function getProvinces(string $countryCode): \stdClass
    {
        $options['query'] = [
            'country' => $countryCode,
        ];

        try {
            $provinces = $this->toStdClass($this->request(
                Request::METHOD_GET,
                '/api/provinces.json',
                $options
            ));

            if (null === $provinces) {
                throw new ProvincesNotFoundException();
            }

            return $provinces;
        } catch (HttpException $exception) {
            throw new ProvincesNotFoundException();
        }
    }
}
