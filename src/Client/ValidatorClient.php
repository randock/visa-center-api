<?php

namespace Randock\VisaCenterApi\Client;

use Randock\VisaCenterApi\Exception\OrderNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Randock\Utils\Http\Exception\HttpException;

class ValidatorClient extends AbstractClient
{
    /**
     * @param string $orderId
     *
     * @return \stdClass
     *
     * @throws OrderNotFoundException
     */

    public function getProvinces(string $orderId): \stdClass
    {
        try{
            $response = $this->toStdClass($this->request(
                Request::METHOD_GET,
                sprintf('/api/issues/%s.json', $orderId)
            ));
        }catch (HttpException $exception) {
            throw new OrderNotFoundException();
        }

        return $response;

    }
}
