<?php
class UserSession
{
  public static function getNode($repoId,$interactorId)
  {
    $data = self::get($repoId,$interactorId);
    if(!$data){
      $id = self::add($repoId,$interactorId);
      return 0;
    }else{
      self::update($data['id']);
      return $data['nodeid'];
    }
  }
  public static function setNode($repo,$interactorId,$node)
  {
    global $database;
    $sObject = self::get($repo,$interactorId);
    $data = array('session_node'=>$node);
    $where = array('id'=>$sObject['id']);
    $database->update('session',$data,$where);
  }
  public static function update($id)
  {
    global $database;
    $data = array('session_update'=>date("Y-m-d H:i:s",time()));
    $where = array('id'=>$id);
    $database->update('session',$data,$where);
  }
  public static function add($repoId,$interactorId)
  {
    global $database;
    $data = array(
      'repoid' => $repoId,
      'interactorid' => $interactorId,
    );
    $id = $database->insert('session',$data);
    return intval($id);
  }
  public static function get($repoId,$interactorId)
  {
    global $database;
    $fields = array(
      'id',
      'repoid',
      'interactorid',
      'nodeid'
    );
    $where = array(
      'AND' => array(
        'repoid' => $repoId,
        'interactorid'=> $interactorId,
        'session_update[>=]'=> date("Y-m-d H:i:s",time()-300)
      )
    );
    $data = $database->get("session", $fields,$where);
    return ($data)?$data:null;
  }
}
?>
