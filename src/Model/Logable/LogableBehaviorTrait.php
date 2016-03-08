<?php

/*
 * This file is part of the Scribe Mantle Bundle.
 *
 * (c) Scribe Inc. <source@scribe.software>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\Arthur\DoctrineBehaviorBundle\Model\Logable;

/**
 * Class LogableBehaviorTrait.
 */
trait LogableBehaviorTrait
{
    /**
     * @param array $changeSets
     *
     * @return string
     */
    public function triggerPostUpdateLoggableEvent(array $changeSets = [])
    {
        $message = [];
        foreach ($changeSets as $property => $changeSet) {
            for ($i = 0; $i < count($changeSet); ++$i) {
                if ($changeSet[$i] instanceof \Datetime) {
                    $changeSet[$i] = $changeSet[$i]->format('Ymd His');
                }
            }

            if ($changeSet[0] != $changeSet[1]) {
                $message[] = sprintf(
                    '%s #%d : property "%s" changed from "%s" to "%s"',
                    __CLASS__,
                    $this->getIdentity(),
                    $property,
                    !is_array($changeSet[0]) ? $changeSet[0] : print_r($changeSet[0], true),
                    !is_array($changeSet[1]) ? $changeSet[1] : print_r($changeSet[0], true)
                );
            }
        }

        return implode("\n", $message);
    }

    /**
     * @return string
     */
    public function triggerPostPersistLoggableEvent()
    {
        return sprintf('%s #%d : created', __CLASS__, $this->getIdentity());
    }

    /**
     * @return string
     */
    public function triggerPreRemoveLoggableEvent()
    {
        return sprintf('%s #%d : created', __CLASS__, $this->getIdentity());
    }
}

/* EOF */
