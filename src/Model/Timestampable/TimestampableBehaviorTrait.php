<?php

/*
 * This file is part of the Scribe Mantle Bundle.
 *
 * (c) Scribe Inc. <source@scribe.software>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\Arthur\DoctrineBehaviorBundle\Model\Timestampable;

use Scribe\Arthur\DoctrineBehaviorBundle\Exception\BehaviorOrmException;

/**
 * Class TimestampableBehaviorTrait.
 */
trait TimestampableBehaviorTrait
{
    /**
     * @var \DateTime
     */
    protected $createdOn;

    /**
     * @var \DateTime
     */
    protected $updatedOn;

    /**
     * @var bool
     */
    protected $createdOnAsForcedManualInput;

    /**
     * @var bool
     */
    protected $updatedOnAsForcedManualInput;

    /**
     * @return $this
     */
    protected function initializeCreatedOnAsForcedManualInput()
    {
        $this->createdOnAsForcedManualInput = false;

        return $this;
    }

    /**
     * @return $this
     */
    protected function initializeUpdatedOnAsForcedManualInput()
    {
        $this->updatedOnAsForcedManualInput = false;

        return $this;
    }

    /**
     * @param \DateTime|null $createdOn
     *
     * @return $this
     */
    public function setCreatedOnAsForcedManualInput(\DateTime $createdOn)
    {
        $this->createdOnAsForcedManualInput = true;
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasCreatedOn()
    {
        return $this->createdOn !== null;
    }

    /**
     * @return null|\DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param string $format
     *
     * @throws BehaviorOrmException
     *
     * @return string
     */
    public function formatCreatedOn($format)
    {
        if (!$this->hasCreatedOn()) {
            throw BehaviorOrmException::create()
                ->setMessage('Cannot format date of null value for createdOn in %s::%s.')
                ->with(get_called_class(), __METHOD__);
        }

        return $this->createdOn->format($format);
    }

    /**
     * @param \DateTime $updatedOn
     *
     * @return $this
     */
    public function setUpdatedOnAsForcedManualInput(\DateTime $updatedOn)
    {
        $this->updatedOnAsForcedManualInput = true;
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasUpdatedOn()
    {
        return $this->updatedOn !== null;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * @param string $format
     *
     * @throws BehaviorOrmException
     *
     * @return string
     */
    public function formatUpdatedOn($format)
    {
        if (!$this->hasUpdatedOn()) {
            throw BehaviorOrmException::create()
                ->setMessage('Cannot format date of null value for updatedOn in %s::%s.')
                ->with(get_called_class(), __METHOD__);
        }

        return $this->updatedOn->format($format);
    }

    /**
     * @return $this
     */
    protected function doCreatedOnTimestampableBehaviorAction()
    {
        if (($this->createdOnAsForcedManualInput && $this->createdOn instanceof \DateTime) ||
            ($this->createdOn instanceof \DateTime)
        ) {
            $this->createdOnAsForcedManualInput = false;

            return $this;
        }

        $this->createdOn = new \DateTime();

        return $this;
    }

    /**
     * @return $this
     */
    protected function doUpdatedOnTimestampableBehaviorAction()
    {
        if (!$this->updatedOnAsForcedManualInput || !($this->updatedOn instanceof \DateTime)) {
            $this->updatedOn = new \DateTime();
        }

        $this->updatedOnAsForcedManualInput = false;

        return $this;
    }
}

/* EOF */
