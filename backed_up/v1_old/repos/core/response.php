<?php
/*
สนทนากับบอท
POST: /repos/:user/:repo/responses
PARAMETER:
  - id (interactive's id)
  - input
RESPONSE:
  - message
*/
$app->post('/:user/:repo/responses',function($username,$reponame) use ($app,$config,$pdo){
  $senderId = $app->request->post('id');
  $input = $app->request->post('input');
  $repoId = Repo::forceGetId($username,$reponame);
  if(!$repoId){
    $app->render(404,array(
      'error'=> array(
        'code' => 13,
        'message' => 'respository '.$username.'/'.$reponame.' isn\'t found',
      ),
    ));
    return ;
  }
  if(!isset($input)){
    $app->render(400,array(
      'error' => array(
        'code' => 0,
        'message'=> 'input is require',
      )
    ));
  }
  if(!isset($senderId)){
    //Not recommend to leave id blank it use to identify user
    //Will use IP for compatiable but strongly don't recommend because
    //User who use nat will get same account data
    $senderId = 'IP_'.$app->request->getIp();
  }
  $response = Response::get($repoId,$senderId,$input);
  if($response){
    $app->render(200,array(
      'message'=>$response
    ));
  }else{
    $app->render(400,array(
      'error' => array(
        'code' => 0,
        'message'=> 'Return Not found',
      )
    ));
  }
});
?>
