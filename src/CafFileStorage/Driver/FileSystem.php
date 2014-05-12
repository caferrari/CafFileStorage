<?php

namespace CafFileStorage\Driver;

use CafFileStorage\Strategy\AbstractStrategy;

class FileSystem implements Saveable
{

    protected $options;
    protected $directory;

    public function __construct($options)
    {
        if (!isset($options['directory'])) {
            throw new \RuntimeException('We need a directory option for this driver');
        }

        $this->options = $options;
        $this->directory = $options['directory'];

        if (!is_writable($this->directory)) {
            throw new \RuntimeException('Directory must be writeable: ' . $this->directory);
        }
    }

    public function save($id, AbstractStrategy $file)
    {
        $fileName = $this->getDirectory($id) . '/' . $id;
        file_put_contents($fileName, $file->getBlob());
    }

    protected function getDirectory($id)
    {
        if (!isset($this->options['subdivide']) || !$this->options['subdivide']) {
            return $this->directory;
        }

        $filesPerFolder = 32768;
        if (is_numeric($this->options['files_per_folder'])) {
            $filesPerFolder = $this->options['files_per_folder'];
        }

        $subdirectory = floor($id / $filesPerFolder);
        $directory = $this->directory . '/' . $subdirectory;

        if (!is_dir($directory)) {
            mkdir($directory);
        }

        return $directory;
    }

    protected function getFileName($id)
    {
        return $this->getDirectory() . '/' . $id;
    }

    public function exists($id)
    {
        return file_exists($this->getFileName($id));
    }

    public function getFile($id)
    {
        if (!$this->exists($id)) {
            throw new \OutOfBoundsException('File not found');
        }

        $filename = $this->getFileName($id);

        return (object)[
            'id' => $id,
            'type' => mime_content_type($filename),
            'size' => filesize($filename),
            'mtime' => filemtime($filename),
            'streamClosure' => function () use ($filename) {
                return fopen($filename, 'r');
            }
        ];
    }
}
