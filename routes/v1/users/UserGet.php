<?php
$app->get('/users/:user',function($username) use ($app){
  $data = User::get($username);
  if(!$data){
    $app->render(404,ErrorCode::get(9));
    return;
  }
  $data['id'] = intval($data['id']);
  $app->render(200, $data);
});
?>
