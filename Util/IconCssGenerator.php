<?php

namespace Flexix\IconGeneratorBundle\Util;

use Symfony\Component\Yaml\Yaml;

class IconCssGenerator {

    protected $bundles;
    protected $generator;

    public function __construct($generator,$bundles)
    {
        $this->generator=$generator;
        $this->bundles=$bundles;
    }
    
    
    protected function getTarget()
    {
        return $this->getResourcesDir().'public'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'entities.css';
    }


    protected function getResourcesDir()
    {
        $dirArr=explode(DIRECTORY_SEPARATOR,__DIR__);
        array_pop($dirArr);
        $bundleDir=implode(DIRECTORY_SEPARATOR,$dirArr);
        $templatesDir=$bundleDir.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR;
        return $templatesDir;
                
    }
    
    protected function getTemplate()
    {
        //return $this->getResourcesDir().'views'.DIRECTORY_SEPARATOR.
        return 'entities.css.twig';
    }
    
    protected function getSkeletonDir()
    {
        return $this->getResourcesDir().'views';
    }
    
    public function renderCssFile() {
      
        $skeletonDirs=[];
        $skeletonDirs[]=$this->getSkeletonDir();
        $this->generator->setSkeletonDirs($skeletonDirs);
        return $this->generator->renderFile($this->getTemplate(),$this->getTarget(), ['bundles'=>$this->bundles]);
    }
    
}
