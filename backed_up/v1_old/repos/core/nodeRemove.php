<?php
/*
ลบโหนด
DELETE: /repos/:user/:repo/nodes/:id
PARAMETER:
  - access_token
RESPONSE:
  - id (node's id)
*/
$app->delete('/:user/:repo/nodes/:id',function($username,$reponame,$nodeId) use ($app,$config,$pdo){
  $access_token = $app->request->delete('access_token');
  $cut = $app->request->delete('cut');
  if(isset($access_token)){
    $userid = jwtToUserId($access_token);
    if(!$userid){
      $app->render(401,array(
         'error' => array(
           'code' => 401,
           'message' => 'access_token is invalid'
         )
      ));
    }else{
      if(Repo::getId($username,$reponame,$userid)){
        if(Node::isExist($nodeId)){
          if(isset($cut) && $cut == "false"){
            Node::remove($nodeId);
          }else{
            Node::cut($nodeId);
          }
          $app->render(200,array(
            'id' => intval($nodeId),
          ));
        }else{
          $app->render(400,array(
             'error' => array(
               'code' => 26,
               'message' => 'node_id '.$nodeId.' isn\'t exist'
             )
          ));
        }
        $app->render(200,array(
          'id' => intval($nodeId),
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
         'code' => 25,
         'message' => 'access_token is require'
       )
    ));
  }
});
?>
