<?php

declare(strict_types=1);

namespace Randock\VisaCenterApi;

use Randock\VisaCenterApi\Client\AbstractClient;

class CollectionApiResponse extends VisaCenterApiResponse implements \Iterator
{
    /**
     * @var AbstractClient
     */
    private $client;

    /**
     * @var bool
     */
    private $fetchMore;

    /**
     * CollectionApiResponse constructor.
     *
     * @param \stdClass      $data
     * @param AbstractClient $client
     * @param bool           $fetchMore
     */
    public function __construct(
        \stdClass $data,
        AbstractClient $client,
        bool $fetchMore
) {
        parent::__construct($data);
        $this->client = $client;
        $this->fetchMore = $fetchMore;
    }

    /**
     * @return array
     */
    public function __toArray()
    {
        return $this->responseData->_embedded->items;
    }

    /**
     * @return mixed
     */
    public function getLinks()
    {
        return parent::get_links();
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return parent::getPage();
    }

    /**
     * @return mixed
     */
    public function getPages()
    {
        return parent::getPages();
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return parent::getTotal();
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return parent::getLimit();
    }

    /**
     * Return the current element.
     *
     * @see http://php.net/manual/en/iterator.current.php
     *
     * @return mixed can return any type
     *
     * @since 5.0.0
     */
    public function current()
    {
        return current($this->responseData->_embedded->items);
    }

    /**
     * Move forward to next element.
     *
     * @see http://php.net/manual/en/iterator.next.php
     * @since 5.0.0
     */
    public function next(): void
    {
        if (!next($this->responseData->_embedded->items) && $this->fetchMore) {
            if ($this->getPage() < $this->getPages()) {
                $this->responseData = $this->client->requestLink($this->getLinks()->next->href);
                $this->rewind();
            }
        }
    }

    /**
     * Return the key of the current element.
     *
     * @see http://php.net/manual/en/iterator.key.php
     *
     * @return mixed scalar on success, or null on failure
     *
     * @since 5.0.0
     */
    public function key()
    {
        return key($this->responseData->_embedded->items);
    }

    /**
     * Checks if current position is valid.
     *
     * @see http://php.net/manual/en/iterator.valid.php
     *
     * @return bool The return value will be casted to boolean and then evaluated.
     *              Returns true on success or false on failure.
     *
     * @since 5.0.0
     */
    public function valid(): bool
    {
        return null !== key($this->responseData->_embedded->items);
    }

    /**
     * Rewind the Iterator to the first element.
     *
     * @see http://php.net/manual/en/iterator.rewind.php
     * @since 5.0.0
     */
    public function rewind(): void
    {
        reset($this->responseData->_embedded->items);
    }
}
