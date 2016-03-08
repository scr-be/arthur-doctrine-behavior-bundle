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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\EventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Scribe\Wonka\Utility\ClassInfo;
use Scribe\Wonka\Utility\Logger\InvokableLoggerInterface;
use Scribe\Wonka\Utility\Reflection\ClassReflectionAnalyserInterface;

/**
 * Class AbstractSubscriber.
 */
abstract class AbstractSubscriber implements SubscriberInterface
{
    /**
     * @var InvokableLoggerInterface
     */
    protected $logger;

    /**
     * @var ClassReflectionAnalyserInterface
     */
    protected $analyser;

    /**
     * @var bool
     */
    protected $subscriberEnabled;

    /**
     * @var ClassMetadata
     */
    protected $metadata;

    /**
     * @param bool $enabled
     */
    final public function __construct($enabled = false)
    {
        $this->subscriberEnabled = $enabled;
        $this->serviceCollection = new ArrayCollection();
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->subscriberEnabled === true;
    }

    /**
     * @param InvokableLoggerInterface $logger
     *
     * @return $this
     */
    public function setLogger(InvokableLoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @param ClassReflectionAnalyserInterface $analyser
     *
     * @return $this
     */
    public function setAnalyser(ClassReflectionAnalyserInterface $analyser)
    {
        $this->analyser = $analyser;

        return $this;
    }

    /**
     * @return int[]
     */
    public function getSubscribedEvents()
    {
        return (array) array_keys($this->getEntityEventMapToMethod());
    }

    /**
     * @param int $event
     *
     * @return string[]
     */
    protected function getEntityMethodsFor($event)
    {
        $map = $this->getEntityEventMapToMethod();

        return (array) array_key_exists($event, $map) ? $map[$event] : [];
    }

    /**
     * @return string[]
     */
    abstract protected function getEntityEventMapToMethod();

    /**
     * @return string[]
     */
    abstract protected function getEntityFieldDefinitions();

    /**
     * @return string[]
     */
    abstract protected function getEntityInstanceAssertions();

    /**
     * @param \ReflectionClass $class
     *
     * @return bool
     */
    protected function isSupported(\ReflectionClass $class)
    {
        $asserts = $this->getEntityInstanceAssertions();

        $results = array_filter($asserts, function ($assertion) use ($class) {
            return array_key_exists($assertion, $class->getTraitNames()) ||
                   array_key_exists($assertion, $class->getInterfaceNames());
        });

        return isCountableEmpty($results) === true ?: false;
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     *
     * @return ClassMetadata
     */
    protected function getClassMetadataFromLoadClassEvent(LoadClassMetadataEventArgs $eventArgs)
    {
        return $eventArgs->getClassMetadata();
    }

    /**
     * @param ClassMetadata $metadata
     *
     * @return \ReflectionClass
     */
    protected function getReflectionFromMetadata(ClassMetadata $metadata)
    {
        return $metadata->getReflectionClass();
    }

    /**
     * @param string $snakeCase
     *
     * @return string
     */
    protected function snakeCaseToCamelCase($snakeCase)
    {
        $camelCase = preg_replace_callback("{(?:^|_)([a-z])}", function($matches) {
            return strtoupper($matches[1]);
        }, $snakeCase);

        return lcfirst($camelCase);
    }

    /**
     * @param ClassMetadata    $metadata
     * @param \ReflectionClass $classRef
     */
    protected function mapLifecycleEvents(ClassMetadata $metadata, \ReflectionClass $classRef)
    {
        foreach ($this->getSubscribedEvents() as $event) {
            $this->mapEntityMethods(
                $this->getEventEntityMethods($event, $classRef), $event, $metadata);
        }
    }

    /**
     * @param mixed            $event
     * @param \ReflectionClass $classRef
     *
     * @return array
     */
    protected function getEventEntityMethods($event, \ReflectionClass $classRef)
    {
        $methods = $this->getEntityMethodsFor($event);

        return (array) array_filter($methods, function ($method) use ($classRef) {
            return $this->analyser->hasMethod($method, $classRef);
        });
    }

    /**
     * @param array         $methods
     * @param mixed         $event
     * @param ClassMetadata $metadata
     */
    protected function mapEntityMethods($methods, $event, ClassMetadata $metadata)
    {
        foreach ($methods as $m) {
            $metadata->addLifecycleCallback($m, $event);
        }
    }

    /**
     * @param ClassMetadata    $metadata
     * @param \ReflectionClass $classRef
     */
    protected function mapMetadataFields(ClassMetadata $metadata, \ReflectionClass $classRef)
    {
        $original = $this->getEntityFieldDefinitions();

        $filtered = array_filter($original, function ($d, $f) use ($metadata) {
            return ! ($metadata->hasField($f) && $d['type'] === $metadata->getTypeOfField($f));
        }, ARRAY_FILTER_USE_BOTH);

        array_walk($filtered, function ($d, $f) use ($metadata) {
            $metadata->mapField(array_merge($d, [
                'fieldName' => $f, 'name' => $this->snakeCaseToCamelCase($f),
            ]));
        });
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        if (! $this->isEnabled()) {
            return;
        }

        $metadata = $this->getClassMetadataFromLoadClassEvent($eventArgs);
        $classRef = $this->getReflectionFromMetadata($metadata);

        if ($this->isSupported($classRef)) {
            $this->mapMetadataFields($metadata, $classRef);
            $this->mapLifecycleEvents($metadata, $classRef);
        }
    }
}

/* EOF */
