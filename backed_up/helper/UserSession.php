<?php
//SELECT * FROM `session` WHERE `session_interactorid` = 'IP_::1' AND`session_repoid` = '1' AND `session_update` > NOW()-300
  class UserSession{
    public static function _add($repoId,$interactorId){
      global $pdo;
      $query = $pdo->insert(array('session_repoid','session_interactorid'))
                ->into('session')
                ->values(array($repoId,$interactorId));
      $query->execute();
      return $pdo->lastInsertId();
    }
    public static function _get($repoId,$interactorId){
      global $pdo;
      $query = $pdo->prepare("SELECT * FROM `session` WHERE `session_repoid` = ? AND `session_interactorid` = ? AND `session_update` > NOW() - INTERVAL 300 SECOND");
      $query->execute(array($repoId,$interactorId));
      if($query->rowCount()==0){
        return null;
      }else{
        $result = $query->fetch();
        return array(
          'id' => $result['session_id'],
          'repo' => $result['session_repoid'],
          'interactor'=>$result['session_interactorid'],
          'node'=>$result['session_node'],
          'session_update'=>$result['session_update'],
        );
      }
    }
    public static function _extendLife($id,$repoId){
      global $pdo;
      $query = $pdo
        ->update(array('session_repoid' => $repoId))
        ->table('session')
        ->where('session_id', '=', $id);
      $result = $query->execute();
    }
    public static function getId($repoId,$interactorId){
      $data = self::_get($repoId,$interactorId);
      $id = null;
      if(!$data){
        $id = self::_add($repoId,$interactorId);
      }else{
        $id = $data['id'];
        self::_extendLife($data['id'],$data['repo']);
      }
      return $id;
    }
    public static function getNode($repoId,$interactorId){
      $data = self::_get($repoId,$interactorId);
      if(!$data){
        $id = self::_add($repoId,$interactorId);
        return 0;
      }else{
        self::_extendLife($data['id'],$data['repo']);
        return $data['node'];
      }
    }
    public static function setNode($repo,$interactorId,$node){
      global $pdo;
      $id = self::getId($repo,$interactorId);
      $query = $pdo
        ->update(array('session_node' => $node))
        ->table('session')
        ->where('session_id', '=', $id);
      $result = $query->execute();
    }
  }
?>
