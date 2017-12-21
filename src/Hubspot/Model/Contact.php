<?php

namespace Hubspot\Model;

class Contact
{
    /** @var string */
    protected $id;

    /** @var array */
    protected $properties = [];

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id)
    {
        $this->id = $id;

        return $this;
    }

    public function getProperties(): ?array
    {
        return $this->properties;
    }

    public function setProperties(?array $properties)
    {
        $this->properties = $properties;

        return $this;
    }


    public function addProperty(string $name, string $value)
    {
        $this->properties[$name] = $value;

        return $this;
    }

    public function removeProperty(string $name)
    {
        unset($this->properties[$name]);

        return $this;
    }

}
