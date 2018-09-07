<?php
class Response{
  /*
  _match
  use for travel node
  */
  public static function match($repoId,$input,$currentNode){
    global $pdo;
    if($currentNode!=0){
      $query = $pdo->prepare('SELECT `node_id` FROM `node` JOIN edge ON node.node_id = edge.edge_nodenext WHERE node_repoid = ? AND edge_nodenext IN (SELECT edge_nodenext FROM edge WHERE edge_nodeid = ?) AND ? REGEXP `node_pattern` ORDER BY edge_order DESC');
      $result = $query->execute(array($repoId,$currentNode,$input));
      if($query->rowCount() == 0){
        $currentNode = 0;
      }else{
        return $result->fetch()['node_id'];
      }
    }
    if($currentNode == 0){
      $query = $pdo->prepare('SELECT `node_id` FROM `node` JOIN edge ON node.node_id = edge.edge_nodenext JOIN story ON node.node_storyid = story.story_id WHERE node_repoid = ? AND edge_nodeid = 0 AND ? REGEXP `node_pattern` ORDER BY story_order DESC');
      $result = $query->execute(array($repoId,$input));
      if($query->rowCount() == 0){
        return null;
      }else{
        return $query->fetch()['node_id'];
      }
    }
  }
  public static function getChild($nodeId){
    global $pdo;
    //SELECT * FROM `node` JOIN edge ON node.node_id = edge.edge_nodenext WHERE edge.edge_nodeid = '4' AND node_type != 'pattern'
    $query = $pdo
      ->select(array('node_id','node_type','node_value'))
      ->from('node')
      ->join('edge','node.node_id','=','edge.edge_nodenext')
      ->where('edge_nodeid','=',$nodeId)
      ->where('node_type','!=','pattern');
    $result = $query->execute();
    if($result->rowCount() == 0){
      return null;
    }else{
      $output = array('id'=>$nodeId,'next'=>array());
      $result = $result->fetchAll();
      foreach ($result as $row) {
        $out =  array(
          'id' => $row['node_id'],
          'type'=> $row['node_type'],
          'value'=> $row['node_value'],
        );
        $output['next'][] = $out;
      }
      return $output;
    }
  }
  /*
  getInputMatch to extract data from input
  */
  public static function getInputMatch($pattern,$input){
    mb_ereg_search_init($input);
    $data = mb_ereg_search_regs($pattern);
    $length = count($data);
    for($i=0;$i<$length;$i++){
      $data[$i] = trim($data[$i]);
    }
    return $data;
  }
  public static function get($repoId,$userId,$input){
    $cNode = UserSession::getNode($repoId,$userId);
    $cNode = self::match($repoId,$input,$cNode);
    if(!$cNode){
      return null;
    }
    $cPattern = Node::getPattern($cNode);
    $inputMatch = self::getInputMatch($cPattern,$input);
    $output = array();
    while(true){
      $child = self::getChild($cNode);
      if($child == null){
        break;
      }
      $ptr = (count($child['next'])>1)?rand(0,count($child['next'])-1):0;
      $type = $child['next'][$ptr]['type'];
      $value = $child['next'][$ptr]['value'];
      if($type == 'response'){
        $output[] = self::mergeResponse($value,$inputMatch);
      }else if($type == 'webhook'){
        $webhook = Webhook::hook($value);
        $sessionId = UserSession::getId($reopId,$userId);
        Variable::set('hook',$repoId,$userId,$sessionId,$webhook);
      }else if($type == 'operation'){

      }else if($type == 'condition'){

      }else if($type == 'bookmark'){

      }
      $cNode = $child['next'][$ptr]['id'];
    }
    UserSession::setNode($repoId,$userId,$cNode);
    if(count($output)==0){
      return null;
    }else{
      return $output;
    }
  }
  public static function mergeResponse($expression,$data){
    $i = 0;
    $length = mb_strlen($expression);
    $buffer = '';
    $front = 0;
    $isBuffering = false;
    while($i<$length){
      $ch = mb_substr($expression,$i,1);
      if($ch == '{'){
        $front = $i;
        $isBuffering = true;
        $buffer = '';
      }else if($ch == '}'){
        $exFront = mb_substr($expression,0,$front);
        $exRear = mb_substr($expression,$i+1,$length-$front-1);
        $exMiddle = '';
        if(is_numeric($buffer)){
          $exMiddle = $data[intval($buffer)];
        }
        //need support user/session/app/webhook later
        $expression = $exFront.$exMiddle.$exRear;
      }else if($isBuffering == true){
        $buffer.=$ch;
      }
      $i++;
    }
    return $expression;
  }
}

?>
