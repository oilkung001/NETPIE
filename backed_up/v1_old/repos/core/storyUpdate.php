<?php
/*
เปลี่ยนชื่อหัวข้อเรื่อง
POST: /repos/:user/:repo/stories/:id
PARAMETER:
  - access_token
  - name
RESPONSE:
  - id (node's id)
*/
$app->post('/:user/:repo/stories/:id',function($username,$reponame,$storyId) use ($app,$config,$pdo){
  $access_token = $app->request->post('access_token');
  $name = $app->request->post("name");
  if(isset($access_token) && isset($name)){
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
        if(Story::isExist($storyId)){
          Story::update($storyId,$name);
          $app->render(200,array(
            'id' => intval($storyId),
          ));
        }else{
          $app->render(400,array(
            'error' => array(
              'code' => 27,
              'message' => 'story_id '.$storyId.' isn\'t exist'
            )
          ));
        }
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
         'code' => 28,
         'message' => 'name and access_token are require'
       )
    ));
  }
});
?>
