<?php

namespace CafFileStorage\Strategy;

use \Imagick;

class Base64Image extends AbstractStrategy
{

    private $imagick;

    public function __construct($uri)
    {
        if (substr($uri, 0, 10) !== 'data:image') {
            throw new \InvalidArgumentException('Invalid image data');
        }

        $image = preg_replace('@^data:image/[a-z]+;base64,@', '', $uri);
        $imagick = new Imagick();
        $imagick->readImageBlob(base64_decode($image));
        $imagick->setImageColorspace(255);
        $imagick->setCompression(Imagick::COMPRESSION_JPEG);
        $imagick->setCompressionQuality(90);
        $imagick->setImageFormat('jpeg');

        return $this->imagick = $imagick;
    }

    public function getImagick()
    {
        return $this->imagick;
    }

    public function getBlob()
    {
        if (!$this->imagick) {
            throw new \RuntimeException("Image not loaded");
        }

        return $this->imagick->getimageblob();
    }

    public function getFormat()
    {
        if (!$this->imagick) {
            throw new \RuntimeException("Image not loaded");
        }

        return $this->imagick->getFormat();
    }
}
