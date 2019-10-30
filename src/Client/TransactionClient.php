<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Randock\VisaCenterApi\Model\Transaction;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Randock\VisaCenterApi\Model\Definition\TransactionInterface;
use Randock\VisaCenterApi\Exception\TransactionNotFoundException;
use Randock\VisaCenterApi\Exception\TransactionCanNotBeCreatedException;
use Randock\VisaCenterApi\Exception\TransactionCanNotBeUpdatedException;

class TransactionClient extends AbstractClient
{
    /**
     * @param string $orderUuid
     * @param string $transactionUuid
     *
     * @throws TransactionNotFoundException
     *
     * @return TransactionInterface
     */
    public function get(string $orderUuid, string $transactionUuid): TransactionInterface
    {
        try {
            return $this->getByResource(
                \sprintf(
                    '/api/orders/%s/transactions/%s.json',
                    $orderUuid,
                    $transactionUuid
                )
            );
        } catch (HttpException $e) {
            throw new TransactionNotFoundException();
        }
    }

    /**
     * @param string $orderUuid
     * @param array  $transactionData
     *
     * @throws TransactionCanNotBeCreatedException
     *
     * @return TransactionInterface
     */
    public function create(string $orderUuid, array $transactionData): TransactionInterface
    {
        try {
            $transactionResource = $this->request(
                'POST',
                \sprintf(
                    '/api/orders/%s/transactions.json',
                    $orderUuid
                ),
                [
                    'json' => $transactionData,
                ]
            );
            $transactionUrlVisaCenter = \parse_url(
                $transactionResource->getHeaders()['Location'][0]
            );

            return $this->getByResource($transactionUrlVisaCenter['path']);
        } catch (HttpException $e) {
            throw new TransactionCanNotBeCreatedException();
        }
    }

    /**
     * @param string $orderUuid
     * @param string $transactionUuid
     * @param array  $transactionData
     *
     * @throws TransactionCanNotBeUpdatedException
     *
     * @return TransactionInterface
     */
    public function patch(string $orderUuid, string $transactionUuid, array $transactionData): TransactionInterface
    {
        try {
            return Transaction::fromStdClass(
                $this->toStdClass(
                    $this->request(
                        'PATCH',
                        \sprintf(
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
            throw new TransactionCanNotBeUpdatedException();
        }
    }

    /**
     * @param string $resource
     *
     * @throws TransactionNotFoundException
     *
     * @return TransactionInterface
     */
    private function getByResource(string $resource): TransactionInterface
    {
        try {
            return Transaction::fromStdClass(
                $this->toStdClass(
                    $this->request(
                        'GET',
                        $resource
                    )
                )
            );
        } catch (HttpException $e) {
            throw new TransactionNotFoundException();
        }
    }
}
