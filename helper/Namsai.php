<?php
/**
* This class in heart of NAMSAI that use to travel and manage all node
**/
class Namsai
{
    public static function match($repoId,$input,$currentNode)
    {
      $nodeId = $currentNode;
      if($currentNode!=0){
        $nodeId = self::findNode($repoId,$input,$currentNode);
      }
      if($nodeId == 0){
        $nodeId = self::findRootNode($repoId,$input);
      }
      return ($nodeId!= 0)?$nodeId:null;
    }
    /**
    * Find Pattern Node that have edge with $currentNode
    * @return Interger $nodeId
    **/
    public static function findNode($repoId,$input,$currentNode)
    {
      global $database;
      $sql = 'SELECT node.id
        FROM node
        JOIN edge ON node.id = edge.nodenext
        WHERE node.repoid = :repoId
        AND edge.nodenext IN (
            SELECT edge.nodenext
            FROM edge WHERE edge.nodeid = :nodeId
        )
        AND :input REGEXP node.pattern
        ORDER BY edge_order DESC';
      $query = $database->pdo->prepare($sql);
      $query->bindParam(':repoId',$repoId);
      $query->bindParam(':nodeId',$currentNode);
      $query->bindParam(':input',$input);
      $result = $query->execute();
      if($query->rowCount() == 0){
        return 0;
      }else{
        return $result->fetch()['node_id'];
      }
    }
    /**
    * Find Pattern Node that have edge with root
    * @return Interger $nodeId
    **/
    public static function findRootNode($repoId,$input){
      global $database;
      $sql = 'SELECT node.id FROM node
      JOIN edge ON node.id = edge.nodenext
      JOIN story ON node.storyid = story.id
      WHERE node.repoid = :repoId
      AND edge.nodeid = 0
      AND :input REGEXP node.pattern
      ORDER BY story.priority DESC';
      $query = $database->pdo->prepare($sql);
      $query->bindParam(':repoId',$repoId);
      $query->bindParam(':input',$input);
      $result = $query->execute();
      if($query->rowCount() == 0){
        return null;
      }else{
        return intval($query->fetch()['id']);
      }
    }
    public static function get($repoId,$userId,$input)
    {
      $cNode = UserSession::getNode($repoId,$userId);
      $cNode = self::match($repoId,$input,$cNode);
      if(!$cNode){
        return null;
      }
      $nodeData = Node::get($cNode);
      $cPattern = IrinLang::toRegex($nodeData['value']);
      $inputMatch = self::getInputMatch($cPattern,$input);
      $output = array();
      //Random recursive node until it's null or pattern
      while(true){
        $child = Edge::getChild($cNode);
        if(!$child || count($child) == 0){
          break;
        }
        $ptr = (count($child)>1)?rand(0,count($child)-1):0;
        $childeNode = Node::get($child[$ptr]);
        $type = $childeNode['type'];
        $value = $childeNode['value'];
        if($type == 'response'){
          $output[] = self::mergeResponse($value,$inputMatch);
        }else if($type == 'webhook'){

        }else if($type == 'operation'){

        }else if($type == 'condition'){

        }else if($type == 'bookmark'){

        }
        $cNode = $childeNode['id'];
      }
      UserSession::setNode($repoId,$userId,$cNode);
      if(count($output)==0){
        return null;
      }else{
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
          if(is_numeric($buffer) && isset($data[intval($buffer)])){
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
