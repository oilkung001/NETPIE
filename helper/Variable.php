<?php
class Variable
{
  public static function getList($repoId)
  {
    global $database;
    $where = array(
      'AND' => array(
        'repoid' => $repoId,
        'type' => 'app'
      ),
      'ORDER'=>array(
        'name' => 'ASC'
      )
    );
    $fields = array('id','name','value');
    $data = $database->select('variable',$fields,$where);
    foreach($data as &$o){
      $o['id'] = intval($o['id']);
    }
    return $data;
  }
  public static function add($repoId,$name,$value){
    global $database;
    $data = array(
      'repoid' => $repoId,
      'name' => $name,
      'value' => $value,
      'type' => 'app'
    );
    $id = $database->insert('variable',$data);
    return intval($id);
  }
  public static function update($id,$name,$value){
    global $database;
    if(!empty($name) && !empty($value)){
      $database->update('variable',array('name'=>$name,'value'=>$value),array('id'=>$id));
    }else if(!empty($value)){
      $database->update('variable',array('value'=>$value),array('id'=>$id));
    }else{
      $database->update('variable',array('name'=>$name),array('id'=>$id));
    }
  }
  public static function delete($id){
    global $database;
    $database->delete('variable',array('id'=>$id));
  }
  public static function isExist($varId,$repoId){
    global $database;
    if($repoId != null){
      return $database->has('variable',array(
        'AND'=>array(
            'id'=>$varId,
            'repoid'=>$repoId,
        )
      ));
    }else{
      return $database->has('variable',array('id'=>$varId));
    }
  }
}

?>
