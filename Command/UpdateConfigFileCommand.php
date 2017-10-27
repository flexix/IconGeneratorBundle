<?php

namespace Flexix\IconGeneratorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Flexix\IconGeneratorBundle\Util\IconConfigGenerator;

class UpdateConfigFileCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('flexix:icon-generator:update-config-file')
                ->setDescription('Updates config file');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $manager = $this->getContainer()->get('doctrine')->getManager();
        $iconConfigGeneratorFile = $this->getContainer()->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'icon_generator.yml';
        if (!file_exists($iconConfigGeneratorFile)) {
            fopen($iconConfigGeneratorFile, "w");
        }
        $classMapper = new IconConfigGenerator($iconConfigGeneratorFile, $manager);
        $classMapper->updateConfigFile();
         $output->writeln('<info>Remember to add  "- { resource: icon_generator.yml }" to import section</info>');     
        
        
    }

}
