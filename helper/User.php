<?php
/**
* @class user
**/
class User
{
  public function add($username,$email,$password,$name = '')
  {
    global $database;
    $passwordHasher = new Pentagonal\Phpass\PasswordHash(8,false);
    $hash = $passwordHasher->hashPassword($password);
    $data = array(
      'username' => $username,
      'email' => $email,
      'hash' => $hash,
      'name' => $name
    );
    $userId = $database->insert('user',$data);
    return $userId;
  }
  public function update($id,$data)
  {
    global $database;
    if(is_numeric($id)){
      $where = array('id' => $id);
    }else{
      $where = array('username' => $id);
    }
    if(isset($data['password'])){
      $passwordHasher = new Pentagonal\Phpass\PasswordHash(8,false);
      $data['hash'] = $passwordHasher->hashPassword($password);
      unset($data['password']);
    }
    return $database->update('user', $data, $where);
  }
  public function get($id)
  {
    global $database;
    if(is_numeric($id)){
      $where = array('id' => $id);
    }else{
      $where = array('username' => $id);
    }
    $fields = array('id','name','username','email','bio');
    $data = $database->get('user',$fields,$where);
    if($data == false){
      return null;
    }
    $data['id'] = intval($data['id']);
    return $data;
  }
  /**
  * check malform username
  **/
  public function isUsernameMalform($username)
  {
    if(is_numeric($username)){
      return true;
    }
    return false;
  }
  public function isEmailExist($email)
  {
    global $database;
    $data = array('email'=>$email);
    $count = $database->count("user",$data);
    return ($count>0)?true:false;
  }
  public function isUsernameExist($username)
  {
    global $database;
    $data = array('username'=>$username);
    $count = $database->count("user",$data);
    return ($count>0)?true:false;
  }
  public static function isReserved($username){
    global $config;
    return (array_search($username, $config['reserved_username']) !== false)?true:false;
  }

}
?>
