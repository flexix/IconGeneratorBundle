<?php

namespace Flexix\IconGeneratorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Flexix\IconGeneratorBundle\Util\IconConfigGenerator;

class GenerateCssFileCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('flexix:icon-generator:generate-css-file')
                ->setDescription('Generate css file');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
       
        $iconCssGenerator = $this->getContainer()->get('flexix_icon_generator.util.icon_ccs_generator');
        $iconCssGenerator->renderCssFile();
        $output->writeln('<info>OK</info>');     
        
    }

}
