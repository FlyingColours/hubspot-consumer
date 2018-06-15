<?php

namespace Hubspot\Iterator;

use Hubspot\Consumer;
use Hubspot\Model\Contact;

class ContactIterator implements \Iterator
{
    /**
     * @var Consumer
     */
    private $consumer;

    /**
     * @var array
     */
    private $properties;

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var array
     */
    private $currentPage = [];

    /**
     * ContactIterator constructor.
     * @param Consumer $consumer
     * @param array $properties Properties to populate the Contact object with
     */
    public function __construct(Consumer $consumer, array $properties = [])
    {
        $this->consumer = $consumer;
        $this->properties = $properties;
    }

    public function current(): Contact
    {
        return $this->currentPage['contacts'][$this->position];
    }

    public function key(): int
    {
        return $this->currentPage['contacts'][$this->position]->getId();
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function rewind(): void
    {
        $this->position = 0;
        $this->currentPage = null;
        $this->getNextPage();
    }

    public function valid(): bool
    {
        if (array_key_exists($this->position, $this->currentPage['contacts'])){
            return true;
        }

        if (!$this->currentPage['has_more']) {
            return false;
        }

        $this->getNextPage();

        if (array_key_exists($this->position, $this->currentPage['contacts'])){
            return true;
        }

        return false;
    }

    protected function getNextPage(): void
    {
        $this->currentPage = $this->consumer->getContacts(
            $this->properties,
            ['vidOffset' => $this->currentPage['vid_offset'], 'count' => 100]
        );
        $this->position = 0;
    }
}
