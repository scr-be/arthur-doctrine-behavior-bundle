<?php

/*
 * This file is part of the Scribe Mantle Bundle.
 *
 * (c) Scribe Inc. <source@scribe.software>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\Arthur\DoctrineBehaviorBundle\Model\Publishable;

/**
 * Class PublishableBehaviorTrait.
 */
trait PublishableBehaviorTrait
{
    /**
     * @var \Datetime|null
     */
    protected $publishOn;

    /**
     * @var \DateTime
     */
    protected $draftedOn;

    /**
     * @return $this
     */
    public function initializeDraftedOn()
    {
        $this->draftedOn = new \DateTime();

        return $this;
    }

    /**
     * @return \Datetime|null
     */
    public function getPublishOn()
    {
        return $this->publishOn;
    }

    /**
     * @param \Datetime|null $publishOn
     *
     * @return $this
     */
    public function setPublishOn(\DateTime $publishOn = null)
    {
        $this->publishOn = $publishOn;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasPublishOn()
    {
        return $this->publishOn !== null;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        if (null === $this->publishOn) {
            return false;
        }

        return $this->publishOn <= (new \Datetime());
    }

    /**
     * @return $this
     */
    public function publish()
    {
        $this->publishOn = new \Datetime();

        return $this;
    }

    /**
     * @return $this
     */
    public function unPublish()
    {
        $this->publishOn = null;

        return $this;
    }
}

/* EOF */
