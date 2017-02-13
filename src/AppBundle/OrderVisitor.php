<?php

namespace AppBundle;

use Commercetools\Core\Client;
use Commercetools\Core\Model\Order\OrderCollection;
use Commercetools\Core\Request\Orders\OrderQueryRequest;
use Commercetools\Core\Response\PagedQueryResponse;
use Countable;
use Iterator;

/**
 * Iterates over the complete found list.
 * @author blange <lange@bestit-online.de>
 * @package AppBundle
 * @version $id$
 */
class OrderVisitor implements Countable, Iterator
{
    /**
     * The used client.
     * @var Client
     */
    private $client = null;

    /**
     * The last response for the fetching of orders.
     * @var PagedQueryResponse
     */
    protected $lastResponse = null;

    /**
     * The found order collection.
     * @var void|OrderCollection
     */
    protected $orderCollection = null;

    /**
     * The query to fetch the orders.
     * @var OrderQueryRequest|void
     */
    protected $orderQuery = null;

    /**
     * Is a pagination used?
     * @var bool
     */
    private $withPagination = true;

    /**
     * OrderVisitor constructor.
     * @param Client $client
     */
    public function __construct(Client $client, bool $withPagination = true)
    {
        $this
            ->setClient($client)
            ->withPagination($withPagination);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return $this->getLastResponse()->getTotal();
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->getOrderCollection()->current();
    }

    /**
     * Returns the used client.
     * @return Client
     */
    private function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Returns the last order fetching response.
     * @return PagedQueryResponse
     */
    public function getLastResponse(): PagedQueryResponse
    {
        if (!$this->lastResponse) {
            $this->loadOrderCollection();
        }

        return $this->lastResponse;
    }

    /**
     * Returns the found order collection.
     * @return OrderCollection
     */
    private function getOrderCollection(): OrderCollection
    {
        if (!$this->orderCollection) {
            $this->loadOrderCollection();
        }

        return $this->orderCollection;
    }

    /**
     * Returns the order query.
     * @return OrderQueryRequest
     */
    public function getOrderQuery(): OrderQueryRequest
    {
        if (!$this->orderQuery) {
            $this->loadOrderQuery();
        }

        return $this->orderQuery;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->getOrderCollection()->key();
    }

    /**
     * Loads the order collection.
     * @return OrderVisitor
     */
    private function loadOrderCollection(): OrderVisitor
    {
        $this->setLastResponse($this->getClient()->execute($this->getOrderQuery()));

        $this->setOrderCollection($this->getLastResponse()->toObject());

        return $this;
    }

    /**
     * Returns the order query request.
     * @return OrderVisitor
     */
    private function loadOrderQuery(): OrderVisitor
    {
        $this->setOrderQuery(new OrderQueryRequest());

        return $this;
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        return $this->getOrderCollection()->next();
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        return $this->getOrderCollection()->rewind();
    }

    /**
     * Sets the used client.
     * @param Client $client
     * @return OrderVisitor
     */
    private function setClient(Client $client): OrderVisitor
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Sets the last order fetching response.
     * @param PagedQueryResponse $lastResponse
     * @return OrderVisitor
     */
    private function setLastResponse(PagedQueryResponse $lastResponse): OrderVisitor
    {
        $this->lastResponse = $lastResponse;

        return $this;
    }

    /**
     * Sets the order collection.
     * @param OrderCollection $orderCollection
     * @return OrderVisitor
     */
    private function setOrderCollection(OrderCollection $orderCollection): OrderVisitor
    {
        $this->orderCollection = $orderCollection;

        return $this;
    }

    /**
     * Sets the order query.
     * @param OrderQueryRequest $orderQuery
     * @return OrderVisitor
     */
    private function setOrderQuery(OrderQueryRequest $orderQuery): OrderVisitor
    {
        $this->orderQuery = $orderQuery;

        return $this;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid(): bool
    {
        $isValid = $this->getOrderCollection()->valid();

        if (!$isValid) {
            // TODO Check count vs. total
            if (!$this->withPagination()) {
                // TODO Change Query

                // Load next list part.
                $this->loadOrderCollection();

                $isValid = $this->getOrderCollection()->valid();
            }
        }

        return $isValid;
    }

    /**
     * Sets the pagination status for this list.
     * @param bool $newStatus
     * @return bool
     */
    private function withPagination(bool $newStatus = true): bool
    {
        $oldStatus = $this->withPagination;

        if (func_num_args()) {
            $this->withPagination = $newStatus;
        }

        return $oldStatus;
    }
}
