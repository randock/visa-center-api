<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi\Definition;

interface CredentialsProviderInterface
{
    /**
     * Return an array with username, password (without keys)
     *
     * @return array
     */
    public function getCredentials(): array;

}