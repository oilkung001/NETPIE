<?php
/*
สร้างโหนดใหม่
POST: /repos/:user/:repo/nodes
PARAMETER:
  - access_token
  - story_id
  - value
RESPONSE:
  - id (node's id)

*/
$app->post('/:user/:repo/nodes',function($username,$reponame) use ($app,$config,$pdo){
  $access_token = $app->request->post('access_token');
  $value = $app->request->post("value");
  $storyid = $app->request->post("story_id");
  $type = $app->request->post("type");
  if($access_token && $storyid && $type){
    if(!$value){
      $value = "";
    }
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
        $result = Node::add($storyid,$type,$value);
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
         'code' => 23,
         'message' => 'story_id,type and access_token are require'
       )
    ));
  }
});
?>
