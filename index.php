<?php
  require("vendor/autoload.php");
  $passwordHasher = new Pentagonal\Phpass\PasswordHash(8,false);
  require("helper/util/JsonRenderView.php");
  require("helper/util/JsonRenderMiddleware.php");
  $app = new \Slim\Slim();
  //$app->config('debug', false); // reenable in production
  $app->view(new \JsonRenderView());
  $app->add(new \JsonRenderMiddleware());
  $app->add(new \CorsSlim\CorsSlim());
  require("config.php");
  require("helper/util/database.php");
  require("helper/util/jwt.php");
  require("helper/ErrorCode.php");
  require("helper/Authen.php");
  require("helper/Repo.php");
  require("helper/Story.php");
  require("helper/User.php");
  require("helper/Node.php");
  require("helper/Edge.php");
  require("helper/util/IrinLang.php");
  require("helper/Namsai.php");
  require("helper/UserSession.php");
  require("helper/Variable.php");
  // Enjoy
  /*require("helper/jwt.php");
  require("helper/pdo.php");
  require("helper/Repo.php");
  */
  require("routes/index.php");

  $app->run();
?>
