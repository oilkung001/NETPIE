<?php
/*
สร้างเส้นเชื่อมใหม่ระหว่าง 2 Node
POST /repos/:user/:repo/edges
PARAMETER:
  - access_token
  - current (current node id)
  - next (node that point next)
  - order (Edge order)(optional)
RESPONSE
  - id (edge's id)
*/
$app->post('/:user/:repo/edges',function($username,$reponame) use ($app,$config,$pdo){
  $access_token = $app->request->post('access_token');
  $cNode = $app->request->post('current');
  $nNode = $app->request->post('next');
  $order = $app->request->post('order');
  if(isset($access_token) && isset($cNode) && isset($nNode)){
    $userid = jwtToUserId($access_token);
    if(!$userid){
      $app->render(401,array(
         'error' => array(
           'code' => 401,
           'message' => 'access_token is invalid'
         )
      ));
    }else{
      if($repoId = Repo::getId($username,$reponame,$userid)){
        $isExistId = (!Node::isExist($cNode) && $cNode!=0)?$cNode:(!Node::isExist($nNode) &&$cNode!=0)?$nNode:null;
        if($isExistId){
          $app->render(400,array(
             'error' => array(
               'code' => 26,
               'message' => 'node_id '.$isExistId.' isn\'t exist',
             )
          ));
          return;
        }
        if(Edge::isExist($cNode,$nNode)){
            $app->render(400,array(
               'error' => array(
                 'code' => 30,
                 'message' => 'Edge from '.$cNode.' to '.$nNode.' is already exist',
               )
            ));
            return;
        }
        if($order){
          $order = intval($order);
          if(Edge::isOrderExist($cNode,$order)){
            $app->render(400,array(
               'error' => array(
                 'code' => 32,
                 'message' => 'node_id '.$cNode.' already has order number '.$order,
               )
            ));
            return null;
          }
        }else{
          $order = Edge::getMaxOrder($cNode);
          $order = (!$order)?1:$order+1;
        }
        $result = Edge::add($cNode,$nNode,$order);
        $app->render(200,array(
          'id' => intval($result),
        ));
      }else{
        $app->render(400,array(
           'error' => array(
             'code' => 13,
             'message' => 'respository '.$username.'/'.$reponame.' isn\'t found'
           )
        ));
      }
    }
  }else{
    $app->render(400,array(
       'error' => array(
         'code' => 29,
         'message' => 'current, next and access_token are require'
       )
    ));
  }
});

?>
