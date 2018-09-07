<?php
class Edge{
  public static function add($cNode,$nNode,$order = null){
    global $database;
    if(is_null($order)){
      $order = self::getMaxOrder($cNode)+1;
    }
    $node = Node::get($nNode);
    $data = array(
      'repoid' => $node['repoid'],
      'storyid' => $node['storyid'],
      'nodeid' => $cNode,
      'nodenext' => $nNode,
      'priority' => $order
    );
    $edgeId = $database->insert('edge',$data);
    return intval($edgeId);
  }
  public static function remove($cNode,$nNode){
    global $database;
    if($cNode == '*'){
      $where = array(
        'nodenext'=>$nNode,
      );
    }else{
      $where = array(
        'AND'=>array(
            'nodeid'=>$cNode,
            'nodenext'=>$nNode,
        )
      );
    }
    $database->delete('edge',$where);
  }
  public static function get($cNode,$nNode = null){
    global $database;
    $fields = array('id','repoid','storyid','nodeid','nodenext','priority(order)');
    $where = array(
      'AND'=>array(
          'nodeid'=>$cNode,
          'nodenext'=>$nNode,
      )
    );
    $data = $database->get('edge',$fields,$where);
    if($data == false){
      return null;
    }
    $data['id']=intval($data['id']);
    $data['repoid']=intval($data['repoid']);
    $data['storyid']=intval($data['storyid']);
    $data['nodeid']=intval($data['nodeid']);
    $data['nodenext']=intval($data['nodenext']);
    $data['order']=intval($data['order']);;
    return $data;
  }
  public static function isExist($edgeId,$repoId){

  }
  public static function hasEdge($cNode,$nNode){
    global $dabase;
    return $database->has('edge',array(
      'AND'=>array(
          'nodeid'=>$cNode,
          'nodenext'=>$nNode,
      )
    ));
  }
  public static function getMaxOrder($cNode){
    global $database;
    $maxId = $database->get('edge','priority',array(
      'nodeid' => $cNode,
      'ORDER' => array(
        'priority' => 'DESC'
      )
    ));
    return intval($maxId);
  }
  public static function isOrderExist($cNode,$order){
    global $dabase;
    return $database->has('edge',array(
      'AND'=>array(
          'nodeid'=>$cNode,
          'nodenext'=>$nNode,
      )
    ));
  }
  /*
  getChild:
  user to query nodeId for child list
  */
  public static function getChild($cNode,$storyId = null){
    global $database;
    $fields = array('nodenext');
    if(is_null($storyId)){
      $where = array(
        'nodeid' => $cNode,
        'ORDER' =>array(
          'priority' => 'ASC'
        )
      );
    }else{
      $where = array(
        'AND' => array(
          'nodeid' => $cNode,
          'storyid' => $storyId
        ),
        'ORDER' =>array(
          'priority' => 'ASC'
        )
      );
    }
    $data = $database->select('edge',$fields,$where);
    $output = array();
    foreach ($data as $key => $value) {
      $output[] = intval($value['nodenext']);
    }
    return $output;
  }
}
?>
