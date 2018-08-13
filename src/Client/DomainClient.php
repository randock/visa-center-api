<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Client;

use Symfony\Component\HttpFoundation\Request;
use Randock\Utils\Http\Exception\HttpException;

class DomainClient extends AbstractClient
{
    /**
     * @return \stdClass
     */
    public function getDomainsNestedByCompany(): \stdClass
    {
        try {
            $response = $this->toStdClass(
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
