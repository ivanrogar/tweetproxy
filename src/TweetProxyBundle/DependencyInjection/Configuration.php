<?php

namespace TweetProxyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package TweetProxyBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder
            ->root('tweet_proxy')
                ->children()
                    ->scalarNode('consumer_key')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('consumer_secret')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('token')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('token_secret')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('fetch_count')->defaultValue(20)->end()
                    ->scalarNode('display_count')->defaultValue(20)->end()
                ->end()
        ;

        return $treeBuilder;
    }
}
