<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Randock\VisaCenterApi\CollectionApiResponse;

class VisaTypeClient extends AbstractClient
{
    public const VISA_TYPE_DEFAULT_URI = '/api/visatypes.json';

    /**
     * @param string $link
     *
     * @return mixed
     */
    public function requestLink(string $link)
    {
        return json_decode(
            $this->request(
                'GET',
                $link
            )->getBody()->getContents()
        );
    }

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
            'query' =>
                [
                    'page' => $page,
                    'limit' => $limit,
                    'orderParameter' => [
                        'updatedAt'
                    ],
                    'orderValue' => [
                        'ASC'
                    ]
                ]
        ];

        $options['query'] = array_merge($options['query'], $queryParams);
        $response = new CollectionApiResponse(
            json_decode(
                $this->request(
                    'GET',
                    self::VISA_TYPE_DEFAULT_URI,
                    $options
                )->getBody()->getContents()
            ),
            $this,
            $fetchMore
        );

        return $response;
    }
}
