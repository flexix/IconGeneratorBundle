<?php

namespace Flexix\IconGeneratorBundle\Util;

use Symfony\Component\Yaml\Yaml;

class IconConfigGenerator {

    protected $filePath;
    protected $manager;

    const REPLACE_MASK = '/[A-Z]([A-Z](?![a-z]))*/';

    public function __construct($filePath, $manager) {
        $this->filePath = $filePath;
        $this->manager = $manager;
    }

    
    protected function getSnakeCase($text) {
        return ltrim(strtolower(preg_replace(self::REPLACE_MASK, '_$0', $text)), '_');
    }

    protected function getDashCase($text) {
        return ltrim(strtolower(preg_replace(self::REPLACE_MASK, '-$0', $text)), '-');
    }

    protected function getBundleName($entityDirectoryNamespace) {
        $entityDirectoryNamespaceArr = explode('\\', $entityDirectoryNamespace);
        unset($entityDirectoryNamespaceArr[count($entityDirectoryNamespaceArr) - 1]);
        return substr(implode($entityDirectoryNamespaceArr), 0, -6);
    }

    protected function getEntityName($entityNamespace) {
        $entityNamespaceArr = explode('\\', $entityNamespace);
        return end($entityNamespaceArr);
    }

    protected function readEntities($root) {
        $allMetadata = $this->manager->getMetadataFactory()->getAllMetadata();

        if (!array_key_exists('flexix_icon_generator', $root)) {
            $root['flexix_icon_generator'] = [];
        }


        if (!array_key_exists('bundles', $root['flexix_icon_generator']) || !is_array($root['flexix_icon_generator']['bundles'])) {
            $root['flexix_icon_generator']['bundles'] = [];
        }

        foreach ($allMetadata as $metadata) {

            $namespace = $this->getSnakeCase($this->getBundleName($metadata->namespace));

            if (!array_key_exists($namespace, $root['flexix_icon_generator']['bundles'])) {
                $root['flexix_icon_generator']['bundles'][$namespace] = [];
            }

            $entityName = $this->getEntityName($metadata->name);
            $alias = $this->getDashCase($entityName);
            if ($this->checkAliasExists($root['flexix_icon_generator']['bundles'], $alias,$namespace)) {
                $alias = sprintf('%s.%s', $this->getDashCase($this->getBundleName($metadata->namespace)), $alias);
            }

            $snakeEntityName = $this->getSnakeCase($entityName);
            if(!array_key_exists($snakeEntityName, $root['flexix_icon_generator']['bundles'][$namespace])){
            $root['flexix_icon_generator']['bundles'][$namespace][$snakeEntityName]['alias'] = $alias;
            $root['flexix_icon_generator']['bundles'][$namespace][$snakeEntityName]['class'] = $metadata->name;
            $root['flexix_icon_generator']['bundles'][$namespace][$snakeEntityName]['icon'] = '\f1b1';
            $root['flexix_icon_generator']['bundles'][$namespace][$snakeEntityName]['color'] = '#aaa';
            $root['flexix_icon_generator']['bundles'][$namespace][$snakeEntityName]['icon_color'] = '#fff';}
        }
        $this->recursiveSort($root);
        return $root;
    }

    protected function checkAliasExists($bundles, $alias,$namespace) {

        foreach ($bundles as $bundle => $entities) {
            foreach ($entities as $entity) {
                if (array_key_exists('alias', $entity) && $entity['alias'] == $alias  &&  $bundle!=$namespace ) {
                    return true;
                }
            }
        }
    }

    protected function recursiveSort(&$array) {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $this->recursiveSort($value);
            }
        }
        return ksort($array);
    }

    public function updateConfigFile() {


        $root = Yaml::parse(file_get_contents($this->filePath));

        if (!$root) {
            $root = [];
        }

        $bundles = $this->readEntities($root);
        $yaml = Yaml::dump($bundles, 5);
        file_put_contents($this->filePath, $yaml);
    }

}
