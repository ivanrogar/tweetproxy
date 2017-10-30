<?php

namespace TweetProxyBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

/**
 * Class TweetProxyExtension
 * @package TweetProxyBundle\DependencyInjection
 */
class TweetProxyExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('tweetproxy.consumer_key', $config['consumer_key']);
        $container->setParameter('tweetproxy.consumer_secret', $config['consumer_secret']);
        $container->setParameter('tweetproxy.token', $config['token']);
        $container->setParameter('tweetproxy.token_secret', $config['token_secret']);
        $container->setParameter('tweetproxy.fetch_count', $config['fetch_count']);
        $container->setParameter('tweetproxy.display_count', $config['display_count']);
    }
}
