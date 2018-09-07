<?php
/*
สนทนากับบอท
POST: /repos/:repo/messages
PARAMETER:
  - id (interactive's id)
  - input
RESPONSE:
  - (array)message
*/
$app->post('/repos/:repo/messages',function($repo) use ($app){
  $senderId = $app->request->post('id');
  $input = $app->request->post('input');
  $repoId = Repo::get($repo)['id'];
  if(is_null($repo)){
    $app->render(400,ErrorCode::get(10));
    return ;
  }
  if(!isset($input)){
    $app->render(400,ErrorCode::get(29));
    return ;
  }
  if(!isset($senderId)){
    //Not recommend to leave id blank it use to identify user
    //Will use IP for compatiable but strongly don't recommend because
    //User who use nat will get same account data
    $senderId = 'IP_'.$app->request->getIp();
  }
  $response = Namsai::get($repoId,$senderId,$input);
  if(is_null($response)){
    $app->render(400,ErrorCode::get(30));
  }else{
    $app->render(200,array(
      'message'=>$response
    ));
  }
});
?>
