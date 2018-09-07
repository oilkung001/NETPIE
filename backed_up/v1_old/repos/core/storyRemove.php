<?php
/*
ลบหัวข้อเรื่อง
DELETE: /repos/:user/:repo/stories/id
PARAMETER:
  - access_token
RESPONSE:
  - id (story's id)
//TODO: check cross repo remove story id
*/
$app->delete('/:user/:repo/stories/:id',function($username,$reponame,$storyId) use ($app,$config,$pdo){
  $access_token = $app->request->delete('access_token');
  $cut = $app->request->delete('cut');
  if($access_token){
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
          if(isset($cut) && $cut == "false"){
            Story::remove($storyId);
          }else{
            Story::cut($storyId);
          }
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
