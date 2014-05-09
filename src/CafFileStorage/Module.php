<?php

namespace CafFileStorage;

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '../../../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'CafFileStorage\FileStorage' => function ($sm) {
                    return new FileStorage($sm);
                }
            ),
            'aliases' => array(
                'filestorage' => 'CafFileStorage\FileStorage'
            )
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                     __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }
}
