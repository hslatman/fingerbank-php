# fingerbank-php-client

A simple and incomplete Fingerbank API client written in PHP.

## Installation

WIP

## Usage


```php
<?php

require_once __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload

use Slatman\FingerbankClient;

$config = [
    'api_key' => 'A fingerbank API key'
];

$client = new FingerbankClient($config);

$dhcp_fingerprint = "1,15,3,6,44,46,47,31,33,121,249,43";
$result = $client->interrogateWithDHCPFingerprint($dhcp_fingerprint);

var_dump($result);

object(stdClass)#11 (4) {
  ["score"]=>
  int(68)
  ["version"]=>
  string(0) ""
  ["device_name"]=>
  string(27) "Operating System/Windows OS"
  ["device"]=>
  object(stdClass)#12 (7) {
    ["id"]=>
    int(1)
    ["name"]=>
    string(10) "Windows OS"
    ["created_at"]=>
    string(24) "2014-09-09T15:09:50.000Z"
    ["updated_at"]=>
    string(24) "2017-09-20T15:46:53.000Z"
    ["parent_id"]=>
    int(16879)
    ["virtual_parent_id"]=>
    NULL
    ["parents"]=>
    array(1) {
      [0]=>
      object(stdClass)#13 (6) {
        ["id"]=>
        int(16879)
        ["name"]=>
        string(16) "Operating System"
        ["created_at"]=>
        string(24) "2017-09-14T18:41:06.000Z"
        ["updated_at"]=>
        string(24) "2017-09-18T16:33:18.000Z"
        ["parent_id"]=>
        NULL
        ["virtual_parent_id"]=>
        NULL
      }
    }
  }
}

```

## Notes

This is an incomplete implementation of the Fingerbank API!

## TODO

* Add other URLs and correct handling
* Improve error handling
* Add test framework
* Provide entities for result objects
* Improve (API) documentation