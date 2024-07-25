<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('POST', 'https://api.sandbox.empresas.btgpactual.com/v1/companies/companyId/pix-cash-in/locations', [
  'headers' => [
    'accept' => 'application/json',
    'content-type' => 'application/json',
  ],
]);

echo $response->getBody();
