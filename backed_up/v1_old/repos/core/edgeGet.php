<?php
  /*
  รับเส้นเชื่อม
  GET: /repos/:user/:repo/edge/:nodeid
  PARAMETER:
    - access_token (optional for private repo)
  RESPONSE:
    - id (node_id)
    - next [id,current,next,order]
  */
  $app->get('/:user/:repo/edges/:nodeid',function($username,$reponame,$nodeId) use ($app,$config,$pdo){
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
      $EdgeData = Edge::get($nodeId);
      if(!$EdgeData){
        $app->render(400,array(
           'error' => array(
             'code' => 26,
             'message' => 'node_id '.$nodeId.' isn\'t exist'
           )
        ));
      }else{
        $app->render(200,$EdgeData);
      }
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
