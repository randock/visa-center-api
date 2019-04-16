<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Randock\VisaCenterApi\Model\VisaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Randock\Utils\Http\Exception\HttpException;
use Randock\VisaCenterApi\CollectionApiResponse;
use Randock\Utils\Http\Exception\FormErrorsException;
use Randock\VisaCenterApi\Exception\VisaTypeNotFoundException;

class VisaTypeClient extends AbstractClient
{
    /**
     * @param int   $page
     * @param int   $limit
     * @param bool  $fetchMore
     * @param array $queryParams
     *
     * @return CollectionApiResponse
     */
    public function getVisaTypes(int $page = 1, int $limit = 20, bool $fetchMore = false, $queryParams = []): CollectionApiResponse
    {
        $options = [
            'query' => [
                'page' => $page,
                'limit' => $limit,
                'orderParameter' => [
                    'updatedAt',
                ],
                'orderValue' => [
                    'ASC',
                ],
            ],
        ];

        $options['query'] = array_merge($options['query'], $queryParams);
        $response = new CollectionApiResponse(
            $this->toStdClass(
                $this->request(
                    Request::METHOD_GET,
                    '/api/visatypes.json',
                    $options
                )
            ),
            $this,
            $fetchMore
        );

        return $response;
    }

    /**
     * @param int $id
     *
     * @throws VisaTypeNotFoundException
     *
     * @return mixed
     */
    public function getVisaType(int $id)
    {
        try {
            $visaType = $this->toStdClass(
                $this->request(
                    Request::METHOD_GET,
                    sprintf(
                        '/api/visatypes/%d.json',
                        $id
                    )
                )
            );

            if ($this->getTransform()) {
                $visaType = VisaType::fromStdClass($visaType);
            }

            return $visaType;
        } catch (HttpException $exception) {
            // custom exception if the visaType is not found
            if (Response::HTTP_NOT_FOUND === $exception->getStatusCode()) {
                throw new VisaTypeNotFoundException();
            }

            throw $exception;
        }
    }

    /**
     * @param       $id
     * @param array $data
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function updateVisaType($id, array $data)
    {
        try {
            $this->request(
                Request::METHOD_PATCH,
                sprintf(
                    '/api/visatypes/%d.json',
                    $id
                ),
                [
                    'json' => $data,
                ]
            );

            // return the visaType
            return $this->getVisaType($id);
        } catch (HttpException $exception) {
            // custom exception if the visaType is not found
            if (Response::HTTP_NOT_FOUND === $exception->getStatusCode()) {
                throw new VisaTypeNotFoundException();
            }

            // form errors?
            if (Response::HTTP_BAD_REQUEST === $exception->getStatusCode()) {
                $this->throwFormErrorsException($exception);
            }

            throw $exception;
        }
    }

    /**
     * @param array $data
     *
     * @throws VisaTypeNotFoundException
     * @throws FormErrorsException
     *
     * @return mixed
     */
    public function createVisaType(array $data)
    {
        try {
            $response = $this->request(
                Request::METHOD_POST,
                '/api/visatypes.json',
                [
                    'json' => $data,
                ]
            );

            // id
            $location = $response->getHeader('Location')[0];
            preg_match('/visatypes\/(\d+)/', $location, $matches);
            $id = (int) $matches[1];

            // return the visaType
            return $this->getVisaType($id);
        } catch (HttpException $exception) {
            // form errors?
            if (Response::HTTP_BAD_REQUEST === $exception->getStatusCode()) {
                $this->throwFormErrorsException($exception);
            }

            throw $exception;
        }
    }

    /**
     * @param int $id
     *
     * @throws VisaTypeNotFoundException
     */
    public function deleteVisaType(int $id)
    {
        try {
            $this->request(
                Request::METHOD_DELETE,
                sprintf(
                    '/api/visatypes/%d.json',
                    $id
                )
            );
        } catch (HttpException $exception) {
            // custom exception if the visaType is not found
            if (Response::HTTP_NOT_FOUND === $exception->getStatusCode()) {
                throw new VisaTypeNotFoundException();
            }

            throw $exception;
        }
    }
}
