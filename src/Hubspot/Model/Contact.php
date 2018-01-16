<?php

namespace Hubspot\Model;

use ArrayObject;

class Contact extends ArrayObject
{
    /** @var string */
    protected $id;


    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id)
    {
        $this->id = $id;

        return $this;
    }

    public function setProperty(string $name, ?string $value)
    {
        $this->offsetSet($name, $value);

        return $this;
    }

    public function getProperty(string $name)
    {
        return $this->offsetGet($name);
    }

    public function removeProperty(string $name)
    {
        $this->offsetUnset($name);

        return $this;
    }

    public function getEmail()
    {
        return $this->offsetExists('email')
            ? $this->offsetGet('email')
            : null
        ;
    }

    public function setEmail(string $value)
    {
        $this->offsetSet('email', $value);

        return $this;
    }
}
