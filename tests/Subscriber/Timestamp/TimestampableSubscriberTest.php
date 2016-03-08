<?php

/*
 * This file is part of the Teavee HTML Generator Bundle.
 *
 * (c) Rob Frawley 2nd <rmf@build.fail>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\Arthur\DoctrineBehaviorBundle\Tests\Subscriber\Timestampable;

use Scribe\Arthur\DoctrineBehaviorBundle\Subscriber\Timestampable\TimestampableSubscriber;
use Scribe\Wonka\Utility\Reflection\ClassReflectionAnalyser;
use Scribe\Wonka\Utility\UnitTest\WonkaTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class TimestampableSubscriberTest.
 */
class TimestampableSubscriberTest extends WonkaTestCase
{
    const FQCN = 'Scribe\Arthur\DoctrineBehaviorBundle\Subscriber\Timestampable\TimestampableSubscriber';

    public function getSubscriber($enabled = true)
    {
        return new TimestampableSubscriber($enabled);
    }

    public function test_instance()
    {
        $this->assertInstanceOf(self::FQCN, $this->getSubscriber());
        $this->assertTrue($this->getSubscriber(true)->isEnabled());
        $this->assertFalse($this->getSubscriber(false)->isEnabled());
    }

    public function test_subscribed_events()
    {
        $this->assertEquals(['loadClassMetadata', 'preUpdate', 'prePersist'],
            $this->getSubscriber()->getSubscribedEvents());
    }

    public function test_load_class_metadata_event()
    {
        $s = $this->getSubscriber();
        $this->assertTrue($s->isEnabled());
        $s->setAnalyser(new ClassReflectionAnalyser());

        $timestampableTrait = $this->getMockBuilder('Scribe\Arthur\DoctrineBehaviorBundle\Model\Timestampable\TimestampableBehaviorTrait')
            ->getMockForTrait();

        $metadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()
            ->setMethods(['getReflectionClass', 'mapField'])
            ->getMock();
        $metadata
            ->expects($this->atLeast(1))
            ->method('getReflectionClass')
            ->willReturn(new \ReflectionClass($timestampableTrait));

        $eventArgs = $this->getMockBuilder('Doctrine\ORM\Event\LoadClassMetadataEventArgs')
            ->disableOriginalConstructor()
            ->setMethods(['getClassMetadata'])
            ->getMock();
        $eventArgs
            ->expects($this->atLeast(1))
            ->method('getClassMetadata')
            ->willReturn($metadata);
        $metadata
            ->expects($this->atLeast(1))
            ->method('mapField')
            ->willReturn(null);

        $s->loadClassMetadata($eventArgs);
    }

    public function test_load_class_metadata_event_disabled()
    {
        $s = $this->getSubscriber(false);
        $this->assertFalse($s->isEnabled());

        $timestampableTrait = $this->getMockBuilder('Scribe\Arthur\DoctrineBehaviorBundle\Model\Timestampable\TimestampableBehaviorTrait')
            ->getMockForTrait();

        $metadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()
            ->setMethods(['getReflectionClass'])
            ->getMock();
        $metadata
            ->expects($this->never())
            ->method('getReflectionClass')
            ->willReturn($timestampableTrait);

        $eventArgs = $this->getMockBuilder('Doctrine\ORM\Event\LoadClassMetadataEventArgs')
            ->disableOriginalConstructor()
            ->setMethods(['getClassMetadata'])
            ->getMock();
        $eventArgs
            ->expects($this->never())
            ->method('getClassMetadata')
            ->willReturn($metadata);

        $s->loadClassMetadata($eventArgs);
    }
}

/* EOF */
