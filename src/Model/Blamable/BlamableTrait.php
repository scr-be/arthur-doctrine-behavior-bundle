<?php

/*
 * This file is part of the Scribe Mantle Bundle.
 *
 * (c) Scribe Inc. <source@scribe.software>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\Arthur\DoctrineBehaviorBundle\Model\Blamable;

/**
 * Class BlamableTrait.
 */
trait BlamableTrait
{
    /**
     * @var
     */
    protected $createdBy;

    protected $updatedBy;

    protected $deletedBy;
}

/* EOF */
