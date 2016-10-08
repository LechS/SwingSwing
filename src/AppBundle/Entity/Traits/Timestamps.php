<?php

namespace AppBundle\Entity\Traits;

use Doctrine\ORM\Mapping AS ORM;
use DateTime;

trait Timestamps
{
    /**
     * @ORM\Column(name="created", type="datetime", nullable=true, options={"default": 0})
     * @var \DateTime
     */
    private $created;

    /**
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updated;

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updated = new DateTime;
    }

    public function getCreated()
    {
        return empty($this->created) ? new DateTime() : $this->created;
    }

    public function setCreated(DateTime $created)
    {
        $this->created = $created;
        return $this;
    }

    public function getUpdated()
    {
        return empty($this->updated) ? new DateTime() : $this->updated;
    }

    public function setUpdated(DateTime $updated)
    {
        $this->updated = $updated;
        return $this;
    }
}
