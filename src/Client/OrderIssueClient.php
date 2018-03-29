<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Randock\Utils\Http\Exception\HttpException;
use Randock\Utils\Http\Exception\FormErrorsException;
use Randock\VisaCenterApi\Exception\OrderNotFoundException;
use Randock\VisaCenterApi\Exception\CustomIssueNotFoundException;

class OrderIssueClient extends AbstractClient
{
    /**
     * @param string $orderId
     *
     * @throws OrderNotFoundException
     *
     * @return \stdClass
     */
    public function getOrderIssues(string $orderId): \stdClass
    {
        try {
            $response = $this->toStdClass($this->request(
                Request::METHOD_GET,
                sprintf('/api/orders/%s/issues.json', $orderId)
            ));
        } catch (HttpException $exception) {
            throw new OrderNotFoundException();
        }

        return $response;
    }

    /**
     * @param string $orderId
     *
     * @throws CustomIssueNotFoundException
     *
     * @return \stdClass
     */
    public function getCustomIssue(string $orderId): \stdClass
    {
        try {
            $response = $this->toStdClass($this->request(
                Request::METHOD_GET,
                sprintf('/api/orders/%s/custom/issue.json', $orderId)
            ));
        } catch (HttpException $exception) {
            throw new CustomIssueNotFoundException();
        }

        return $response;
    }

    /**
     * @param string $message
     * @param string $orderId
     *
     * @throws FormErrorsException
     */
    public function sendCustomIssue(string $message, string $orderId): void
    {
        try {
            $this->request(
                Request::METHOD_POST,
                sprintf('/api/orders/%s/custom/issue/send.json', $orderId),
                [
                    'json' => [
                        'message' => $message,
                    ],
                ]
            );
        } catch (HttpException $exception) {
            if (Response::HTTP_BAD_REQUEST === $exception->getStatusCode()) {
                $this->throwFormErrorsException($exception);
            }

            throw $exception;
        }
    }
}
