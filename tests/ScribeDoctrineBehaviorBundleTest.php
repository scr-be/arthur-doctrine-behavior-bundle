<?php

/*
 * This file is part of the Teavee HTML Generator Bundle.
 *
 * (c) Rob Frawley 2nd <rmf@build.fail>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\Arthur\DoctrineBehaviorBundle\Tests;

use Scribe\WonkaBundle\Utility\TestCase\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ScribeDoctrineBehaviorBundle.
 */
class ScribeDoctrineBehaviorBundleTest extends KernelTestCase
{
    public function test_can_build_container()
    {
        static::assertTrue(static::$staticContainer instanceof ContainerInterface);
    }
}

/* EOF */
