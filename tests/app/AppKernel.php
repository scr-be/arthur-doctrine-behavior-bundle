<?php

/*
 * This file is part of the Scribe Mantle Bundle.
 *
 * (c) Scribe Inc. <source@scribe.software>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * Class AppKernel.
 */
class AppKernel extends \Scribe\WonkaBundle\Component\HttpKernel\Kernel
{
    /**
     * @return void
     */
    public function setup()
    {
        $this
            ->addBundle('\Symfony\Bundle\MonologBundle\MonologBundle')
            ->addBundle('\Symfony\Bundle\FrameworkBundle\FrameworkBundle')
            ->addBundle('\Symfony\Bundle\SecurityBundle\SecurityBundle')
            ->addBundle('\Scribe\WonkaBundle\ScribeWonkaBundle')
            ->addBundle('\Doctrine\Bundle\DoctrineBundle\DoctrineBundle')
            ->addBundle('\Scribe\Arthur\DoctrineBehaviorBundle\ScribeDoctrineBehaviorBundle')
            ->addBundle('\Symfony\Bundle\DebugBundle\DebugBundle', 'dev', 'test')
            ->addBundle('\Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle', 'dev', 'test')
            ->addBundle('\Scribe\Arthur\DoctrineFixturesBundle\ScribeArthurDoctrineFixturesBundle', 'dev', 'test');
    }
}
