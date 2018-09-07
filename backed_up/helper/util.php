<?php
function isAccessable($repoId,$userId){
  global $pdo;
  if(!$userId){
    return false;
  }
  $query = $pdo
    ->select()
    ->from('repo')
    ->where('repo_id','=',$repoId)
    ->where('repo_ownerid','=',$userId);
  $result = $query->execute();
  if($result->rowCount()!=0){
    return true;
  }
  $query = $pdo
    ->select()
    ->from('contributor')
    ->where('repo_id','=',$repoId)
    ->where('user_id','=',$userId);
  $result = $query->execute();
  if($result->rowCount()!=0){
    return true;
  }else{
    return false;
  }
}
function usernameToId($username){
  global $pdo;
  $query = $pdo
    ->select(array('user_id'))
    ->from('user')
    ->where('user_name','=',$username);
  $result = $query->execute();
  return $result->fetch()['user_id'];
}
function findRepoId($userName,$repoName){
  global $pdo;
  $userId = usernameToId($userName);
  $query = $pdo
    ->select(array('repo_id'))
    ->from('repo')
    ->where('repo_name','=',$repoName)
    ->where('repo_ownerid','=',$userId);
  $result = $query->execute();
  if($result->rowCount()!=0){
    return $result->fetch()['repo_id'];
  }else{
    return null;
  }
}
function isPrivateRepo($repoId){
  global $pdo;
  $query = $pdo
    ->select(array('repo_private'))
    ->from('repo')
    ->where('repo_id','=',$repoId);
  $result = $query->execute();
  if($result->rowCount() == 0){
    return null;
  }else{
    $result = $result->fetch();
    if($result['repo_private'] == 0){
      return false;
    }else{
      return true;
    }
  }
}
function getRepoId($userName,$repoName,$userId){
  $repoId = findRepoId($username,$reponame);
  if(isPrivateRepo($repoId)){
    if(isAccessable($repoId,$userId)){
      return $repoId;
    }else{
      return null;
    }
  }else if($repoId){
    return $repoId;
  }else{
    return null;
  }
}

?>
