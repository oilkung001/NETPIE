<?php
  class Edge{
    /*add*/
    public static function add($cNode,$nNode,$order){
      global $pdo;
      $query = $pdo->insert(array('edge_nodeid','edge_nodenext','edge_order'))
                ->into('edge')
                ->values(array($cNode,$nNode,$order));
      $query->execute();
      return $pdo->lastInsertId();
    }
    public static function remove($cNode,$nNode){
      global $pdo;
      $query = $pdo
        ->delete()
        ->from('edge')
        ->where('edge_nodeid', '=', $cNode)
        ->where('edge_nodenext','=',$nNode);
      $query->execute();
    }
    public static function next($cNode){
      global $pdo;
      $query = $pdo
        ->select()
        ->from('edge')
        ->where('edge_nodeid', '=', $cNode);
      $result = $query->execute()->fetchAll();
      $EdgeData = array('id'=>$cNode);
      $nextNode = array();
      foreach ($result as $row) {
        $nextNode[] = $row['edge_nodenext'];
      }
      if(count($result)>0){
        $EdgeData['next']=$nextNode;
      }
      return $EdgeData;
    }
    /*
    remvoeNextTo
    remove all edge that link to this PARAMETER
    */
    public static function remvoeNextTo($nNode){
      global $pdo;
      $query = $pdo
        ->delete()
        ->from('edge')
        ->where('edge_nodenext','=',$nNode);
      $query->execute();
    }
    public static function get($cNode,$nNode = null){
      global $pdo;
      if($nNode!=null){
        $query = $pdo
          ->select()
          ->from('edge')
          ->where('edge_nodeid', '=', $cNode)
          ->where('edge_nodenext','=',$nNode);
        $result = $query->execute();
        if($result->rowCount()>0){
          $result = $result->fetch();
          return array(
            'id' => $result['edge_id'],
            'current'=> $result['edge_nodenext'],
            'next'=> $result['edge_nodeid']
          );
        }else{
          return null;
        }
      }else{
        $query = $pdo
          ->select()
          ->from('edge')
          ->where('edge_nodeid', '=', $cNode);
        $result = $query->execute();
        if($result->rowCount()>0){
          $output = array('id'=>$cNode,'next'=>array());
          $result = $result->fetchAll();
          foreach ($result as $row) {
            $out =  array(
              'id' => $row['edge_id'],
              'current'=> $row['edge_nodeid'],
              'next'=> $row['edge_nodenext'],
              'order'=> $row['edge_order'],
            );
            $output['next'][] = $out;
          }
          return $output;
        }else{
          return null;
        }
      }
    }
    public static function isExist($cNode,$nNode){
      return (self::get($cNode,$nNode))?true:false;
    }
    public static function getMaxOrder($cNode){
      $data = self::get($cNode);
      if(!$data){
        return null;
      }
      $out = array();
      foreach($data['next'] as $edge){
        $out[] = $edge['order'];
      }
      return max($out);
    }
    public static function isOrderExist($cNode,$order){
      global $pdo;
      $query = $pdo
        ->select()
        ->from('edge')
        ->where('edge_nodeid', '=', $cNode)
        ->where('edge_order','=',$order);
      $result = $query->execute();
      return ($result->rowCount()>0)?true:false;
    }
  }
?>
