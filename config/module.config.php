<?php

return array(
    'filestorage' => array(
        'namespaces' => array(
            'default' => array(
                'driver' => 'filesystem',
                'options' => array(
                    'directory' => getcwd() . '/data'
                )
            )
        ),
        'drivers' => array(
            'filesystem' => 'CafFileStorage\Driver\FileSystem'
        ),
        'default_options' => array(
            'filesystem' => array(
                'directory' => getcwd() . '/data',
                'subdivide' => false,
                'files_per_folder' => 32768
            )
        )
    )
);
