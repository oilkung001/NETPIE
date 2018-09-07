<?php
  /*
  ร้บหัวข้อเรื่อง
  GET: /repos/:user/:repo/stories/:id
  PARAMETER:
    - access_token (optional for private repo)
  RESPONSE:
    - id (story's id)
    - name
    //TODO: get repoId from StoryId and check is repoId same with repoId from prevent cross repo request
  */
  $app->get('/:user/:repo/stories/:id',function($username,$reponame,$storyId) use ($app,$config,$pdo){
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
    if(Repo::getId($username,$reponame,$userid)){
      $storyData = Story::get($storyId);
      if(!$storyData){
        $app->render(400,array(
          'error' => array(
            'code' => 27,
            'message' => 'story_id '.$storyId.' isn\'t exist'
          )
        ));
        return ;
      }

      if($knowledge = Story::getKnowledge($storyId)){
        $storyData['graph'] = $knowledge;
      }
      $app->render(200,$storyData);
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
