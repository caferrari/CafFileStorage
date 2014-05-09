# ZF2 File Storage Module

This module helps you to store and retrieve files based on namespaces/id

Saving Base64 encoded images
----------------------------

In the module.config.php

```php
<?php
namespace Application;

return array(
    'filestorage' => array(
        'namespaces' => array(
            'my_nice_namespace' => array(
                'driver' => 'filesystem',
                'options' => array(
                    'directory' => getcwd() . '/data/images',
                    'subdivide' => true,
                    'files_per_folder' => 1000
                )
            )
        )
    )
);
```


Usage in the controller:

```php
<?php

namespace Application\Controller;

//...
class SomeController
{
    //...
    public function someAction()
    {
        //... receive post data and insert into the database

        $this->getServiceLocator()->get('filestorage')
            ->setNamespace('my_nice_namespace')
            ->addBase64Image($id, $base64Data)
            ->save();

        //...
    }

}
```
