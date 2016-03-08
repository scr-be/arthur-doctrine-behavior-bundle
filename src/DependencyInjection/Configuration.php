<?php

/*
 * This file is part of the Wonka Bundle.
 *
 * (c) Scribe Inc.     <scr@src.run>
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\Arthur\DoctrineBehaviorBundle\DependencyInjection;

use Scribe\WonkaBundle\Component\DependencyInjection\AbstractConfiguration;

/**
 * Class Configuration.
 */
class Configuration extends AbstractConfiguration
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $this
            ->getBuilderRoot()
            ->getNodeDefinition()
            ->info('Configuration for scr-be/arthur-doctrine-behavior-bundle')
            ->canBeEnabled()
            ->children()
                ->arrayNode('subscribers_enable')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('timestampable')
                        ->treatNullLike(false)
                        ->isRequired()
                        ->defaultFalse()
                        ->info('Toggle "timestampable" doctrine subscriber"')
                    ->end()
                    ->booleanNode('slugable')
                        ->treatNullLike(false)
                        ->isRequired()
                        ->defaultFalse()
                        ->info('Toggle "sluggable" doctrine subscriber"')
                    ->end()
                    ->booleanNode('publishable')
                        ->treatNullLike(false)
                        ->isRequired()
                        ->defaultFalse()
                        ->info('Toggle "publishable" doctrine subscriber"')
                    ->end()
                    ->booleanNode('logable')
                        ->treatNullLike(false)
                        ->isRequired()
                        ->defaultFalse()
                        ->info('Toggle "loggable" doctrine subscriber"')
                    ->end()
                    ->booleanNode('blame')
                        ->treatNullLike(false)
                        ->isRequired()
                        ->defaultFalse()
                        ->info('Toggle "blamable" doctrine subscriber"')
                    ->end()
                ->end();

        return $this
            ->getBuilderRoot()
            ->getTreeBuilder();
    }

    /**
     * @return NodeDefinition
     */
    protected function getCacheNode()
    {
        return $this
            ->getBuilder('cache')
            ->getNodeDefinition()
            ->addDefaultsIfNotSet()
            ->info('The optional generator cache component.')
            ->children()
            ->integerNode('ttl')
            ->info('The TTL (time-to-live) for cache entries.')
            ->defaultValue(3600)
            ->treatNullLike(3600)
            ->treatFalseLike(0)
            ->treatTrueLike(3600)
            ->end()
            ->booleanNode('enabled')
            ->defaultFalse()
            ->info('Should the cache component be used?')
            ->treatNullLike(false)
            ->end()
            ->end();
    }
}

/* EOF */
