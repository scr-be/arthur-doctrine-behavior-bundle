<?php

/*
 * This file is part of the Scribe Mantle Bundle.
 *
 * (c) Scribe Inc. <source@scribe.software>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\Arthur\DoctrineBehaviorBundle\Subscriber;

use Doctrine\Common\EventSubscriber;
use Scribe\Wonka\Utility\Logger\InvokableLoggerInterface;
use Scribe\Wonka\Utility\Reflection\ClassReflectionAnalyserInterface;

/**
 * Class SubscriberInterface.
 */
interface SubscriberInterface extends EventSubscriber
{
    /**
     * @param bool $enabled
     */
    public function __construct($enabled = false);

    /**
     * @return bool
     */
    public function isEnabled();

    /**
     * @param InvokableLoggerInterface $logger
     *
     * @return $this
     */
    public function setLogger(InvokableLoggerInterface $logger);

    /**
     * @param ClassReflectionAnalyserInterface $analyser
     *
     * @return $this
     */
    public function setAnalyser(ClassReflectionAnalyserInterface $analyser);

    /**
     * @return int[]
     */
    public function getSubscribedEvents();
}

/* EOF */
