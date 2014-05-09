<?php

namespace CafFileStorage\Driver;

use CafFileStorage\Strategy\AbstractStrategy;

interface Saveable
{

    public function __construct($options);
    public function save($id, AbstractStrategy $file);
}
