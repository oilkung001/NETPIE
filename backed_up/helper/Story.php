<?php
  class Story{
    /*
    add
    */
    public static function add($repoId,$storyName = '',$order = null){
      global $pdo;
      if($order == null){
        $order = self::getMaxOrder($repoId)+1;
      }
      $query = $pdo
        ->insert(array('story_repoid', 'story_name', 'story_order'))
        ->into('story')
        ->values(array($repoId,$storyName,$order));
      $result = $query->execute();
      return $pdo->lastInsertId();
    }
    /*
    get story id and story name
    */
    public static function get($stroyId){
      global $pdo;
      $query = $pdo
        ->select()
        ->from('story')
        ->where('story_id','=',$stroyId);
      $result = $query->execute();
      if($result->rowCount()==0){
        return null;
      }else{
        $result = $result->fetch();
        return array(
          'id'=>intval($result['story_id']),
          'name'=>$result['story_name'],
        );
      }
    }
    /*
    getRepoId
    */
    public static function getRepoId($storyId){
      global $pdo;
      $query = $pdo
        ->select()
        ->from('story')
        ->where('story_id','=',$storyId);
      $result = $query->execute();
      if($result->rowCount()==0){
        return null;
      }else{
        return $result->fetch()['story_repoid'];
      }
    }
    /*
    remove
    //TODO: must automatic remove node and edge when remove story
    */
    public static function remove($storyId){
      global $pdo;
      $query = $pdo
        ->delete()
        ->from('story')
        ->where('story_id', '=', $storyId);
      $query->execute();
    }
  /* isExist */
  public static function isExist($storyId){
    global $pdo;
    $query = $pdo
      ->select()
      ->from('story')
      ->where('story_id','=',$storyId);
    $result = $query->execute();
    return ($result->rowCount()!=0)?true:false;
  }
  /* update */
  public static function update($storyId,$name){
    global $pdo;
    $query = $pdo
      ->update(array('story_name' => $name))
      ->table('story')
      ->where('story_id', '=', $storyId);
    $result = $query->execute();
  }
  /*getMaxOrder*/
  public static function getMaxOrder($repoId){
    global $pdo;
    $query = $pdo
      ->select()
      ->from('story')
      ->where('story_repoid','=',$repoId)
      ->orderBy('story_order','DESC')
      ->limit(1);
    $result = $query->execute();
    if($result->rowCount() == 0){
      return null;
    }else{
      return $result->fetch()['story_order'];
    }
  }
  /*getList*/
  public static function getList($repoId){
    global $pdo;
    $query = $pdo
      ->select()
      ->from('story')
      ->where('story_repoid','=',$repoId)
      ->orderBy('story_order','DESC');
    $result = $query->execute()->fetchAll();
    $output = array();
    foreach ($result as $row) {
      $out = array(
        'id'=>$row['story_id'],
        'name'=>$row['story_name'],
        'order'=>$row['story_order'],
      );
      $output[] = $out;
    }
    return $output;
  }
  /*getKnowledge
  //TODO: must render sort by edge order
  */
  public static function getKnowledge($storyId){
    global $pdo;
    $query = $pdo->prepare("SELECT * FROM `edge` WHERE `edge_nodeid` = '0' AND edge_nodenext = ANY (SELECT `node_id` FROM `node` WHERE `node_storyid` = ?)");
    $query->execute(array($storyId));
    $result = $query->fetchAll();
    if($result != false){
      $tree = array();
      $getTreeFromDB = function ($node_id,&$cursor) use ($pdo,&$getTreeFromDB){
        $query = $pdo->select()->from("node")->where("node_id","=",$node_id);
        $result = $query->execute()->fetch();
        $cursor['id'] = $result['node_id'];
        $cursor['type'] = $result['node_type'];
        $cursor['value'] = $result['node_value'];
        $query = $pdo->select()->from("edge")->where("edge_nodeid","=",$node_id);
        $result = $query->execute();
        if($result->rowCount()!=0){
          $cursor['next']=array();
          $cursor=&$cursor['next'];
          foreach ($result->fetchAll() as $row) {
            $cursor[] = array();
            $getTreeFromDB($row["edge_nodenext"],$cursor[count($cursor)-1]);
          }
        }
      };
      foreach ($result as $row) {
        $tree[] = array();
        $getTreeFromDB($row["edge_nodenext"],$tree[count($tree)-1]);
      }
      return $tree;
    }else{
      return null;
    }
  }
  /*
  cut
  like node cut just remove story and wipeout garbage
  */
  public static function cut($storyId){
    $nodes = self::getRoot($storyId);
    if(isset($nodes['next'])){
      foreach ($nodes['next'] as $cNode) {
        Node::cut($cNode);
      }
    }
    self::remove($storyId);
  }
  /*
  getRoot
  */
  public static function getRoot($storyId){
    global $pdo;
    $query = $pdo
      ->select(array('node_id'))
      ->from('node')
      ->join('edge','node.node_id','=','edge.edge_nodenext')
      ->where('node_storyid','=',$storyId)
      ->where('edge_nodeid','=','0');
    $result = $query->execute();
    $output = array('id' => $storyId);
    $out = array();
    if($result->rowCount() != 0){
      foreach ($result->fetchAll() as $row) {
        $out[] = $row['node_id'];
      }
      $output['next'] = $out;
    }
    return $output;
  }
}
?>
