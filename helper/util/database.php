<?php
require(__DIR__.'/medoo.php');
$database = new medoo([
    'database_type' => 'mysql',
    'database_name' => $config['db']['name'],
    'server' => $config['db']['host'],
    'username' => $config['db']['user'],
    'password' => $config['db']['password'],
    'charset' => 'utf8'
]);
?>
