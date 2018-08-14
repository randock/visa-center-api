<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Symfony\Component\HttpFoundation\Request;
use Randock\Utils\Http\Exception\HttpException;

class DomainClient extends AbstractClient
{
    /**
     * @return array
     */
    public function getDomainsNestedByCompany(): array
    {
        try {
            $response = $this->parseContentToArray(
                $this->request(
                    Request::METHOD_GET,
                    '/api/domains/nested/company.json'
                )
            );
        } catch (HttpException $exception) {
            throw $exception;
        }

        return $response;
    }
}
