<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi;

use Randock\VisaCenterApi\Exception\InvalidMagicCallException;

class VisaCenterApiResponse
{
    /**
     * @var \stdClass
     */
    protected $responseData;

    /**
     * VisaCenterApiClientResponse constructor.
     *
     * @param \stdClass $responseData
     */
    public function __construct(\stdClass $responseData)
    {
        $this->responseData = $responseData;
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @throws InvalidMagicCallException
     *
     * @return mixed|null
     */
    public function __call(string $name, array $arguments)
    {
        if (substr($name, 0, 3) === 'get') {
            $key = lcfirst(substr($name, 3));

            return $this->__get($key);
        }

        throw new InvalidMagicCallException();
    }

    /**
     * @param string $name
     *
     * @return null|mixed
     */
    public function __get(string $name)
    {
        return $this->responseData->$name ?? null;
    }
}
