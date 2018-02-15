<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Slatman\FingerbankClient;

$config = [
    'api_key'      => 'A Fingerbank API Key'
];

$c = new FingerbankClient($config);


//var_dump($c->user());
//var_dump($c->devices());
//var_dump($c->interrogate());

$dhcp_fingerprint = "1,15,3,6,44,46,47,31,33,121,249,43";
//var_dump($c->interrogateWithDHCPFingerprint($dhcp_fingerprint));

$mac = "e0b9ba88158a";
//var_dump($c->interrogateWithMacAddress($mac));

$user_agents = [
    "Mozilla/5.0 (iPad; CPU OS 9_3_5 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13G36 Safari/601.1"
];

//var_dump($c->interrogateWithUserAgents($user_agents));


var_dump($c->interrogate($dhcp_fingerprint, $mac, $user_agents));