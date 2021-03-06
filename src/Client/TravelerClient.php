<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Randock\VisaCenterApi\Model\Traveler;
use Symfony\Component\HttpFoundation\Request;
use Randock\Utils\Http\Exception\HttpException;
use Randock\VisaCenterApi\Exception\TravelerNotFoundException;

class TravelerClient extends AbstractClient
{
    /**
     * @param string $orderId
     * @param string $uuid
     */
    public function deleteTraveler(string $uuid): void
    {
        try {
            $response = $this->request(
                'DELETE',
                \sprintf(
                    '/api/travelers/%s.json',
                    $uuid
                )
            );

            return;
        } catch (HttpException $exception) {
            if (404 === $exception->getStatusCode()) {
                throw new TravelerNotFoundException($exception->getMessage());
            }
            throw $exception;
        }
    }

    /**
     * @param string $uuid
     *
     * @throws TravelerNotFoundException
     *
     * @return \stdClass
     */
    public function getTraveler(string $uuid)
    {
        try {
            $traveler = $this->toStdClass(
                $this->request(
                    'GET',
                    \sprintf(
                        '/api/travelers/%s.json',
                        $uuid
                    )
                )
            );

            if ($this->getTransform()) {
                $traveler = Traveler::fromStdClass($traveler);
            }

            return $traveler;
        } catch (HttpException $exception) {
            if (404 === $exception->getStatusCode()) {
                throw new TravelerNotFoundException($exception->getMessage());
            }
            throw $exception;
        }
    }

    /**
     * @param array $travelersId
     * @param int   $visaType
     *
     * @throws TravelerNotFoundException
     *
     * @return \stdClass
     */
    public function getReusableData(array $travelersId, int $visaType): \stdClass
    {
        try {
            return $this->toStdClass(
                $this->request(
                    Request::METHOD_POST,
                    '/api/travelers/reusable-data.json',
                    [
                        'query' => [
                            'visaType' => $visaType,
                        ],
                        'json' => [
                            'travelersId' => $travelersId,
                        ],
                    ]
                )
            );
        } catch (HttpException $exception) {
            throw new TravelerNotFoundException();
        }
    }
}
