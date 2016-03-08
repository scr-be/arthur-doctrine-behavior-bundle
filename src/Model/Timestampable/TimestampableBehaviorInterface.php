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

/**
 * Class TimestampableBehaviorInterface.
 */
interface TimestampableBehaviorInterface
{
    /**
     * @param \DateTime|null $createdOn
     *
     * @return $this
     */
    public function setCreatedOnAsForcedManualInput(\DateTime $createdOn);

    /**
     * @return bool
     */
    public function hasCreatedOn();

    /**
     * @return null|\DateTime
     */
    public function getCreatedOn();

    /**
     * @param string $format
     *
     * @return string
     */
    public function formatCreatedOn($format);

    /**
     * @param \DateTime $updatedOn
     *
     * @return $this
     */
    public function setUpdatedOnAsForcedManualInput(\DateTime $updatedOn);

    /**
     * @return bool
     */
    public function hasUpdatedOn();

    /**
     * @return \DateTime|null
     */
    public function getUpdatedOn();

    /**
     * @param string $format
     *
     * @return string
     */
    public function formatUpdatedOn($format);
}

/* EOF */
