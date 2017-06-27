# PHP Client for Ejabberd
PHP Ejabberd Client to communicate with XMPP Client


### Installation
```bash
composer require cloudadic/php-ejabberd
```

### Usage
```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Ejabberd\Client;

$client = Client([
  'port' => 5285,
  'host' => '192.178.12.1',
  'apiEndPoint' => 'your_endpoint'
]);


```

### Examples
```php

// Add User
$user = 'john@doe.com';
$client->addUser($user);

// Ban Account
$user = 'john@doe.com';
$reason = 'Acting too smart';
$client->banAccount($user, $reason);
```
