<?php

namespace CafFileStorage;

use CafFileStorage\Strategy\AbstractStrategy as FileStrategy;

class FileStorage
{

    protected $config;
    protected $sm;
    protected $namespace = 'default';
    protected $files = array();
    protected $fileDriver = null;

    public function __construct($sm)
    {
        $this->sm = $sm;
        $this->config = $sm->get('Config')['filestorage'];
    }

    public function setNamespace($namespace)
    {
        if (!array_key_exists($namespace, $this->config['namespaces'])) {
            throw new \InvalidArgumentException("Invalid namespace: {$namespace}");
        }

        $this->fileDriver = null;
        $this->namespace = $namespace;
        return $this;
    }

    public function addFile($id, FileStrategy $file)
    {
        $this->files[$id] = $file;
        return $this;
    }

    public function __call($method, $params)
    {
        if ('add' !== substr($method, 0, 3) || 'addFile' === $method) {
            return call_user_func_array(array($this, $method), $params);
        }

        $class = '\\CafFileStorage\\Strategy\\' . substr($method, 3);

        if (!class_exists($class)) {
            throw new \InvalidArgumentException('Stretegy does not exists: ' . $class);
        }

        list($id, $uri) = $params;

        $this->addFile($id, new $class($uri));

        return $this;
    }

    public function save()
    {
        $driver = $this->getFileDriver();

        foreach ($this->files as $id => $file) {
            $driver->save($id, $file);
        }

        return $this;
    }

    public function getFileDriver()
    {
        if ($this->fileDriver) {
            return $this->fileDriver;
        }

        $driver = $this->config['namespaces'][$this->namespace]['driver'];

        if (!array_key_exists($driver, $this->config['drivers'])) {
            throw new InvalidArgumentException('Invalid driver: ' . $driver);
        }

        $driverClass = $this->config['drivers'][$driver];

        $options = array();
        if (is_array($this->config['namespaces'][$this->namespace]['options'])) {
            $options = $this->config['namespaces'][$this->namespace]['options'];
        }

        $this->fileDriver = new $driverClass($options);
        return $this->fileDriver;
    }

    public function exists($id)
    {
        return $this->getFileDriver()->exists($id);
    }

    public function getFile($id)
    {
        return $this->getFileDriver()->getFile($id);
    }
}
