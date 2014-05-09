<?php

namespace CafFileStorage\Strategy;

abstract class AbstractStrategy
{

    abstract public function __construct($uri);
    abstract public function getBlob();
    abstract public function getFormat();
}
