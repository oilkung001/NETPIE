<?php
/*
ลบเส้นเชื่อม
DELETE: /repos/:user/:repo/edges
PARAMETER:
  - current
  - next
  - access_token (optional for private repo)
RESPONSE:
  - id (Edge id)
*/
$app->delete('/:user/:repo/edges',function($username,$reponame) use ($app,$config,$pdo){
  $access_token = $app->request->delete('access_token');
  $cNode = $app->request->delete('current');
  $nNode = $app->request->delete('next');
  if($access_token && $cNode && $nNode){
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
        $id = Edge::get($cNode,$nNode);
        if(!$id){
            $app->render(400,array(
               'error' => array(
                 'code' => 31,
                 'message' => 'Edge from '.$cNode.' to '.$nNode.' isn\'t exist',
               )
            ));
            return;
        }
        $id = $id['id'];
        $result = Edge::remove($cNode,$nNode);
        $app->render(200,array(
          'id' => intval($id),
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
