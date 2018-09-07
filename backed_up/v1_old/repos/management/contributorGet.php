<?php
/*
ดึงข้อมูลผู้ร่วมพัฒนาทั้งหมด
GET: /repos/:user/:repo/contributor
PARAMETER:
  - access_token (Optional for private repo only)
RESPONSE:
  - id (repo's id)
  - contributor (array){id,username,name,email,bio}
*/
$app->get('/:user/:repo/contributor',function($username,$reponame) use ($app,$config,$pdo){
  $access_token = $app->request->get('access_token');
  $userId = null;
  if($access_token){
    $userId = jwtToUserId($access_token);
    if(!$userId){
      $app->render(401,array(
         'error' => array(
           'code' => 401,
           'message' => 'access_token is invalid'
         )
      ));
      return;
    }
  }
  $repoId = Repo::getId($username,$reponame,$userId);
  if(!$repoId){
    $app->render(404,array(
      'error'=> array(
        'code' => 13,
        'message' => 'respository '.$username.'/'.$reponame.' isn\'t found',
      ),
    ));
    return ;
  }
  $contributor = Repo::getContributor($repoId);
  $app->render(200,array(
    'id'=>$repoId,
    'contributor'=>$contributor,
  ));
});
?>
