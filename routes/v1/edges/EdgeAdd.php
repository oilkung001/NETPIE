<?php
/*
สร้างเส้นเชื่อมใหม่ระหว่าง 2 Node
POST /repos/:repo/edges
PARAMETER:
  - access_token
  - current (current node id)
  - next (node that point next)
  - order (Edge order)(optional)
RESPONSE
  - id (edge's id)
*/
$app->post('/repos/:repo/edges',function($repo) use ($app){
  $access_token = $app->request->post('access_token');
  $cNode = $app->request->post('current');
  $nNode = $app->request->post('next');
  $order = $app->request->post('order');
  if(!isset($name)){
    $name = "";
  }
  if(!isset($access_token)){
    $app->render(400,ErrorCode::get(1));
    return;
  }
  $userId = Authen::getId($access_token);
  if(!isset($userId)){
    $app->render(400,ErrorCode::get(2));
    return;
  }
  if(!isset($cNode)||!isset($nNode)){
    $app->render(400,ErrorCode::get(25));
  }
  $repoId = Repo::get($repo);
  if(is_null($repo)){
    $app->render(400,ErrorCode::get(10));
    return ;
  }
  if(!Repo::hasWritePermission($repoId,$userId)){
    $app->render(400,ErrorCode::get(17));
    return ;
  }
  if($cNode != 0 && !Node::isExist($cNode,$repoId)){
    $app->render(400,ErrorCode::get(26));
    return ;
  }
  if(!Node::isExist($nNode,$repoId)){
    $app->render(400,ErrorCode::get(27));
    return ;
  }
  if(isset($order) && Edge::isExistOrder($cNode,$order)){
    $app->render(400,ErrorCode::get(24));
    return ;
  }
  if(isset($order)){
    $order = intval($order);
  }else{
    $order = null;
  }
  $result = Edge::add(intval($cNode),intval($nNode),$order);
  $app->render(200,array(
    'id' => intval($result),
  ));
});
?>
