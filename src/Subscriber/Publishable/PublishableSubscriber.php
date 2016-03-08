<?php

/*
 * This file is part of the Scribe Mantle Bundle.
 *
 * (c) Scribe Inc. <source@scribe.software>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\Arthur\DoctrineBehaviorBundle\Subscriber\Publishable;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Scribe\Arthur\DoctrineBehaviorBundle\Subscriber\AbstractSubscriber;

/**
 * Class PublishableSubscriber.
 */
class PublishableSubscriber extends AbstractSubscriber
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
                'triggerGenerateSlugEvent',
            ],
            Events::prePersist => [
                'triggerGenerateSlugEvent',
            ],
        ];
    }

    /**
     * @return string[]
     */
    protected function getEntityFieldDefinitions()
    {
        return [
            'draftedOn' => [ 'type' => 'datetime', 'nullable' => true, ],
            'publishOn' => [ 'type' => 'datetime', 'nullable' => true, ],
        ];
    }

    /**
     * @return string[]
     */
    protected function getEntityInstanceAssertions()
    {
        return [
            'Scribe\Arthur\DoctrineBehaviorBundle\Model\Publishable\PublishableBehaviorTrait',
        ];
    }
}

/* EOF */
