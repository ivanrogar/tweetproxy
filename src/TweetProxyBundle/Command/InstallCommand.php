<?php

namespace TweetProxyBundle\Command;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TweetProxyBundle\Entity\User;

/**
 * Class InstallCommand
 * @package TweetProxyBundle\Command
 */
class InstallCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tweetproxy:install')
            ->setDescription('Install Tweetproxy');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $em = $this->getContainer()->get('doctrine')->getManager();
        $manager = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $metaData = $manager->getMetadataFactory()->getAllMetadata();
        $tool = new SchemaTool($manager);

        $output->write('Recreating schema ... ');
        $tool->dropSchema($metaData);
        $tool->createSchema($metaData);
        $output->writeln('ok');

        $testUser = new User();
        $testUser
            ->setEmail('tweetproxy@example.com')
            ->setFirstName('Tweet')
            ->setLastName('Proxy')
            ->setPasswordPlain('tweetproxy')
            ->setRoles(["ROLE_USER"])
            ->setIsActivated(true);

        $em->persist($testUser);
        $em->flush();

        return 0;
    }

}