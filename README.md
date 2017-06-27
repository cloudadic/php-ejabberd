# PHP Client for Ejabberd
PHP Ejabberd Client to communicate with XMPP Client


### Installation
```composer require cloudadic/php-ejabberd```

### Usage
```php
<?php

use Ejabberd\Client;

$client = Client([
  'port' => 5285,
  'host' => '192.178.12.1',
  'apiEndPoint' => 'your_endpoint'
]);


```
