<?php
require_once '../src/ElasticEmail.php';
// Rename config-dist.php to config.php and configure apikey and email account
$config = require_once './config.php';

$Email = new ElasticEmail\ElasticEmail($config['api_key'],$config['email_account']);

$message = array(
  'from_email' => 'fromemail@example.com',
  'from_name' => 'From Name',
  'body_text' => 'This is the plain text',
  'body_html' => '<h1>Elastic Email Example</h1> This is HTML content',
  'subject' => 'The Subject '.date('Y-m-d h:i:s a'),
  'to' => array(
      0 => array('email'=>'toemail@example.com','name'=>'To Name','type'=>'to')
    )
  );

// Send message
$rs = $Email->messages->send($message);

// Print result
echo $rs;
