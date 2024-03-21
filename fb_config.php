<?php
require_once 'vendor/autoload.php';

session_start();

$fb = new Facebook\Facebook([
  'app_id' => '',
  'app_secret' => '',
  'default_graph_version' => 'v9.0',
]);