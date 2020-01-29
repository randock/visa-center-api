<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Randock\Utils\Http\Exception\HttpException;
use Randock\VisaCenterApi\Exception\ProvincesNotFoundException;
use Symfony\Component\HttpFoundation\Request;

class ProvinceClient extends AbstractClient
{
    /**
     * @param string $countryCode
     *
     * @return \stdClass
     * @throws ProvincesNotFoundException
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

            if(null===$provinces){
                throw new ProvincesNotFoundException();
            }

            return $provinces;
            
        } catch (HttpException $exception) {
            throw new ProvincesNotFoundException();
        }

    }
}
