<?php

namespace Lthrt\EntityBundle\Entity;

/**
 * EntityTrait.
 */
trait EntityTrait
{
    use CreatedTrait;
    use EntityGetSetTrait;
    use IdTrait;
    use UpdatedTrait;
    use UpdatedByTrait;

    /**
     * For duplcating entities as part of any reconciliation process.
     * Ensures new identity does no update old record.  Will be assigned
     * id on persistence.
     */
    public function __clone()
    {
        if ($this->id) {
            $this->id = null;
        }
    }

    /**
     * So pages don't crash if accidentally printed.
     *
     * @return integer
     */
    public function __toString()
    {
        return $this->id . "";
    }

    /**
     * Prevent resetting id.
     *
     * @return integer
     */
    public function setId()
    {
        // disabled
        return $this;
    }
}
