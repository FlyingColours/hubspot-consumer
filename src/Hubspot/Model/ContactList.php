<?php

namespace Hubspot\Model;

class ContactList
{
    protected $id;

    /** @var string */
    protected $name;

    /** @var bool */
    protected $dynamic = true;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function isDynamic(): ?bool
    {
        return $this->dynamic;
    }

    public function setDynamic(?bool $dynamic)
    {
        $this->dynamic = $dynamic;

        return $this;
    }
}
