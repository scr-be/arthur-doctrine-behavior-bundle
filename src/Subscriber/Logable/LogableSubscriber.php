<?php

/*
 * This file is part of the Scribe Mantle Bundle.
 *
 * (c) Scribe Inc. <source@scribe.software>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\Arthur\DoctrineBehaviorBundle\Subscriber\Logable;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Scribe\Arthur\DoctrineBehaviorBundle\Subscriber\AbstractSubscriber;

/**
 * Class LogableSubscriber.
 */
class LogableSubscriber extends AbstractSubscriber
{
    /**
     * @var callable
     */
    protected $invokableLogger;

    /**
     * @param callable $invokableLogger
     *
     * @return $this
     */
    public function setInvokableLogger(callable $invokableLogger)
    {
        $this->invokableLogger = $invokableLogger;

        return $this;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $this->logChangeSet($eventArgs, __FUNCTION__);
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $this->logChangeSet($eventArgs, __FUNCTION__);
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $this->logChangeSet($eventArgs, __FUNCTION__);
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     * @param string             $for
     */
    protected function logChangeSet(LifecycleEventArgs $eventArgs, $for)
    {
        list(, $entity, $reflectionClass, $uow, $classMetadata) =
            $this->getHelperObjectsForLifecycleEvent($eventArgs);

        if (true !== $this->isSupported($reflectionClass)) {
            return;
        }

        foreach ($this->getSubscriberTriggers($for) as $trigger) {
            if (true !== $this->reflectionAnalyser->hasMethod($trigger, $reflectionClass)) {
                continue;
            }

            $uow->computeChangeSet($classMetadata, $entity);
            $changeSet = $uow->getEntityChangeSet($entity);

            $message = $entity->$trigger($changeSet);
            $invokableLogger = $this->invokableLogger;
            $invokableLogger($message);
        }
    }
}

/* EOF */
