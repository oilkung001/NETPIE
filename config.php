<?php
    $config = array(
      'db' => array(
        'name' => 'namsai',
        'host' => 'localhost',
        'user' => 'root',
        'password'=> ''
      ),
      'jwt' => array(
        'secret' => 'ThisIs-A-SecretForJWTYouMustChangeInProduction',
        'domain'=>'http://localhost', //use for JWT iss data
        'expire'=>2592000, //Token expire date in 2 month
      ),
      'reserved_username'=>array(
        'username',
        'name',
        'admin',
        'password',
        'email',
        'bio',
        'register',
        'docs',
        'apps'
      ),
      'english_username' => true,
    );
?>
