<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Randock\VisaCenterApi\CollectionApiResponse;

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
                    'GET',
                    '/api/visatypes.json',
                    $options
                )
            ),
            $this,
            $fetchMore
        );

        return $response;
    }
}
