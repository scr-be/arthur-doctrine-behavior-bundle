<?php

/*
 * This file is part of the Scribe Mantle Bundle.
 *
 * (c) Scribe Inc. <source@scribe.software>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\Arthur\DoctrineBehaviorBundle\Subscriber\Timestampable;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Scribe\Arthur\DoctrineBehaviorBundle\Subscriber\AbstractSubscriber;

/**
 * Class TimestampableSubscriber.
 */
class TimestampableSubscriber extends AbstractSubscriber
{
    /**
     * @return string[]
     */
    protected function getEntityEventMapToMethod()
    {
        return [
            Events::loadClassMetadata => [
                // assign columns if needed
            ],
            Events::preUpdate => [
                'doCreatedOnTimestampableBehaviorAction',
                'doUpdatedOnTimestampableBehaviorAction',
            ],
            Events::prePersist => [
                'doCreatedOnTimestampableBehaviorAction',
                'doUpdatedOnTimestampableBehaviorAction',
            ],
        ];
    }

    /**
     * @return string[]
     */
    protected function getEntityFieldDefinitions()
    {
        return [
            'updated_on' => [ 'type' => 'datetime', 'nullable' => true, ],
            'created_on' => [ 'type' => 'datetime', 'nullable' => true, ],
        ];
    }

    /**
     * @return string[]
     */
    protected function getEntityInstanceAssertions()
    {
        return [
            'Scribe\Arthur\DoctrineBehaviorBundle\Model\Timestampable\TimestampableBehaviorTrait',
        ];
    }
}

/* EOF */
