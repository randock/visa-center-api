<?php

namespace Randock\VisaCenterApi\Client;

use Randock\Utils\Http\Exception\FormErrorsException;
use Randock\VisaCenterApi\Exception\CustomIssueNotFoundException;
use Randock\VisaCenterApi\Exception\OrderNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Randock\Utils\Http\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;

class OrderIssueClient extends AbstractClient
{
    /**
     * @param string $orderId
     *
     * @return \stdClass
     *
     * @throws OrderNotFoundException
     */
    public function getOrderIssues(string $orderId): \stdClass
    {
        try{
            $response = $this->toStdClass($this->request(
                Request::METHOD_GET,
                sprintf('/api/orders/%s/issues.json', $orderId)
            ));
        }catch (HttpException $exception) {
            throw new OrderNotFoundException();
        }

        return $response;
    }

    /**
     * @param string $orderId
     *
     * @return \stdClass
     *
     * @throws CustomIssueNotFoundException
     */
    public function getCustomIssue(string $orderId): \stdClass
    {
        try{
            $response = $this->toStdClass($this->request(
                Request::METHOD_GET,
                sprintf('/api/orders/%s/custom/issue.json', $orderId)
            ));
        }catch (HttpException $exception) {
            throw new CustomIssueNotFoundException();
        }

        return $response;
    }

    /**
     * @param array $data
     *
     * @param string $orderId
     *
     * @throws FormErrorsException
     */
    public function sendCustomIssue(array $data, string $orderId): void
    {
        try {
            $this->request(
                Request::METHOD_POST,
                sprintf('/api/orders/%s/custom/issue/send.json', $orderId),
                [
                    $data,
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
