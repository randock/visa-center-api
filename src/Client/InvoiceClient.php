<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Randock\Utils\Http\Exception\HttpException;
use Randock\VisaCenterApi\Exception\VisaCenterGetOrderFatalErrorException;

class InvoiceClient extends AbstractClient
{
    /**
     * @param int    $invoiceId
     * @param string $file
     *
     * @return ResponseInterface
     */
    public function getInvoice(int $invoiceId, string $file): ResponseInterface
    {
        return $this->request(
            Request::METHOD_GET,
            sprintf('/api/invoices/%s/pdf.json', $invoiceId),
            [
                'sink' => $file,
            ]
        );
    }

    /**
     * @param array $ordersToInvoice
     *
     * @throws VisaCenterGetOrderFatalErrorException
     */
    public function postOrdersToInvoice(array $ordersToInvoice): void
    {
        try {
            $this->request(
                Request::METHOD_POST,
                '/api/invoices.json',
                [
                    'json' => ['invoices' => $ordersToInvoice],
                ]
            );
        } catch (HttpException $exception) {
            if ($exception->getStatusCode() === 500) {
                throw new VisaCenterGetOrderFatalErrorException();
            }
            throw $exception;
        }
    }

    /**
     * @param int    $invoiceId
     * @param string $orderUuid
     *
     * @throws VisaCenterGetOrderFatalErrorException
     */
    public function sendInvoicePdf(int $invoiceId, string $orderUuid): void
    {
        try {
            $this->request(
            Request::METHOD_POST,
                    sprintf('/api/invoices/%s/orders/%s/pdf/send.json', $invoiceId, $orderUuid)
            );
        } catch (HttpException $exception) {
            if ($exception->getStatusCode() === 500) {
                throw new VisaCenterGetOrderFatalErrorException();
            }
            throw $exception;
        }
    }
}
