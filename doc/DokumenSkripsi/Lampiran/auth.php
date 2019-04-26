<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$config['domain'] = getenv('CI_BASE_URL');
$config['google-clientid'] = getenv('GOOGLE_CLIENTID');
$config['google-clientsecret'] = getenv('GOOGLE_CLIENTSECRET');
$config['google-redirecturi'] = $config['domain'] . '/auth/oauth2callback';

$config['email-config'] = Array(
    'protocol' => 'smtp',
    'smtp_host' => getenv('SMTP_HOST'),
    'smtp_port' => intval(getenv('SMTP_PORT')),
    'smtp_user' => getenv('SMTP_USER'),
    'smtp_pass' => getenv('SMTP_PASS'),
    'mailtype' => 'html',
    'charset' => 'iso-8859-1'
);