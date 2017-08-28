<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

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
                sprintf(
                    '/api/travelers/%s.json',
                    $uuid
                )
            );

            return;
        } catch (HttpException $exception) {
            if ($exception->getStatusCode() === 404) {
                throw new TravelerNotFoundException($exception->getMessage());
            }
            throw $exception;
        }
    }
}
