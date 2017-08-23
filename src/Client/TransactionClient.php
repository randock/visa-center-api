<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Randock\VisaCenterApi\Model\VisaCenterTransaction;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Randock\VisaCenterApi\Model\Definition\VisaCenterTransactionInterface;
use Randock\VisaCenterApi\Exception\VisaCenterTransactionNotFoundException;
use Randock\VisaCenterApi\Exception\VisaCenterTransactionCanNotBeCreatedException;
use Randock\VisaCenterApi\Exception\VisaCenterTransactionCanNotBeUpdatedException;

class TransactionClient extends AbstractClient
{
    /**
     * @param string $orderUuid
     * @param string $transactionUuid
     *
     * @throws VisaCenterTransactionNotFoundException
     *
     * @return VisaCenterTransactionInterface
     */
    public function get(string $orderUuid, string $transactionUuid): VisaCenterTransactionInterface
    {
        try {
            return $this->getByResource(
                sprintf(
                    '/api/orders/%s/transactions/%s.json',
                    $orderUuid,
                    $transactionUuid
                )
            );
        } catch (HttpException $e) {
            throw new VisaCenterTransactionNotFoundException();
        }
    }

    /**
     * @param string $orderUuid
     * @param array  $transactionData
     *
     * @throws VisaCenterTransactionCanNotBeCreatedException
     *
     * @return VisaCenterTransactionInterface
     */
    public function create(string $orderUuid, array $transactionData): VisaCenterTransactionInterface
    {
        try {
            $transactionResource = $this->request(
                'POST',
                sprintf(
                    '/api/orders/%s/transactions.json',
                    $orderUuid
                ),
                [
                    'json' => $transactionData,
                ]
            );
            $transactionUrlVisaCenter = parse_url(
                $transactionResource->getHeaders()['Location'][0]
            );

            return $this->getByResource($transactionUrlVisaCenter['path']);
        } catch (HttpException $e) {
            throw new VisaCenterTransactionCanNotBeCreatedException();
        }
    }

    /**
     * @param string $orderUuid
     * @param string $transactionUuid
     * @param array  $transactionData
     *
     * @throws VisaCenterTransactionCanNotBeUpdatedException
     *
     * @return VisaCenterTransactionInterface
     */
    public function patch(string $orderUuid, string $transactionUuid, array $transactionData): VisaCenterTransactionInterface
    {
        try {
            return VisaCenterTransaction::fromStdClass(
                $this->toStdClass(
                    $this->request(
                        'PATCH',
                        sprintf(
                            '/api/orders/%s/transactions/%s.json',
                            $orderUuid,
                            $transactionUuid
                        ),
                        [
                            'json' => $transactionData,
                        ]
                    )
                )
            );
        } catch (HttpException $e) {
            throw new VisaCenterTransactionCanNotBeUpdatedException();
        }
    }

    /**
     * @param string $resource
     *
     * @throws VisaCenterTransactionNotFoundException
     *
     * @return VisaCenterTransactionInterface
     */
    private function getByResource(string $resource): VisaCenterTransactionInterface
    {
        try {
            return VisaCenterTransaction::fromStdClass(
                $this->toStdClass(
                    $this->request(
                        'GET',
                        $resource
                    )
                )
            );
        } catch (HttpException $e) {
            throw new VisaCenterTransactionNotFoundException();
        }
    }
}
