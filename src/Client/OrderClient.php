<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Randock\Utils\Uuid\UuidUtils;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Randock\VisaCenterApi\Exception\OrderNotFoundException;
use Randock\VisaCenterApi\Exception\OrderContainsErrorsException;
use Randock\VisaCenterApi\Exception\VisaCenterGetOrderFatalErrorException;

class OrderClient extends AbstractClient
{
    /**
     * @param string $visaFormUri
     *
     * @return \stdClass
     */
    public function getOrderSchemaForm(string $visaFormUri): \stdClass
    {
        return json_decode(
            $this->request(
                'GET',
                $visaFormUri
            )->getBody()->getContents()
        );
    }

    /**
     * @param string $orderUuid
     *
     * @throws OrderNotFoundException
     *
     * @return \stdClass
     */
    public function getOrder(string $orderUuid): \stdClass
    {
        try {
            return json_decode(
                $this->request(
                    'GET',
                    sprintf(
                        '/api/orders/%s.json',
                        $orderUuid
                    )
                )->getBody()->getContents()
            );
        } catch (HttpException $e) {
            throw new OrderNotFoundException();
        }
    }

    /**
     * @param array $order
     *
     * @throws OrderContainsErrorsException
     * @throws VisaCenterGetOrderFatalErrorException
     *
     * @return string
     */
    public function createOrder(array $order): string
    {
        try {
            $response = $this->request(
                'POST',
                '/api/orders.json',
                [
                    'json' => $order['order'],
                    'query' => [
                        'locale' => $order['locale'],
                        'visaType' => $order['visaTypeId']
                    ]
                ]
            );

            $orderUrlVisaCenter = parse_url($response->getHeaders()['Location'][0]);

            return UuidUtils::getUuidFromString($orderUrlVisaCenter['path']);
        } catch (HttpException $e) {
            if ($e->getStatusCode() === 400) {
                throw new OrderContainsErrorsException($e->getMessage());
            }
            if ($e->getStatusCode() === 500) {
                throw new VisaCenterGetOrderFatalErrorException();
            }
            throw $e;
        }
    }
}
