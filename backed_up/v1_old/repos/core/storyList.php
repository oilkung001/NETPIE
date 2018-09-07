<?php
  /*
  ร้บหัวข้อเรื่อง
  GET: /repos/:user/:repo/stories
  PARAMETER:
    - access_token (optional for private repo)
  RESPONSE:
    - id (repo's id)
    - stories (id (story's id),name)
    //TODO: get repoId from StoryId and check is repoId same with repoId from prevent cross repo request
  */
  $app->get('/:user/:repo/stories',function($username,$reponame) use ($app,$config,$pdo){
    $access_token = $app->request->get('access_token');
    $userid = null;
    if($access_token){
      $userid = jwtToUserId($access_token);
      if(!$userid){
        $app->render(401,array(
           'error' => array(
             'code' => 401,
             'message' => 'access_token is invalid'
           )
        ));
        return ;
      }
    }
    if($repoId = Repo::getId($username,$reponame,$userid)){
      $storyData = Story::getList($repoId);
      $app->render(200,array(
        'id'=>$repoId,
        'stories' => $storyData,
      ));
    }else{
      $app->render(400,array(
        'error' => array(
          'code' => 13,
          'message' => 'respository '.$username.'/'.$reponame.' isn\'t found'
        )
      ));
    }
  });

?>
