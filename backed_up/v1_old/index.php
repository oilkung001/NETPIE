<?php
  $app->group('/v1', function () use ($app,$config,$pdo) {
    require('auth.php');
    require('users/index.php');
    require('repos/index.php');
  });
?>
