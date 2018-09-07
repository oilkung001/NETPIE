<?php
class User{
  /*
  getId from username
  */
  public static function getId($username){
    global $pdo;
    $query = $pdo
      ->select(array('user_id'))
      ->from('user')
      ->where('user_name','=',$username);
    $result = $query->execute();
    return $result->fetch()['user_id'];
  }
  /*
  getId fromToken
  */
  public static function fromToken($token){

  }
  public static function isEmailExist($email){
    global $pdo;
    $query = $pdo->select()->from('user')->where('user_email','=',$email);
    $count = $query->execute()->rowCount();
    return $count>0?true:false;
  }
  public static function isUserNameExist($username){
    global $pdo;
    $query = $pdo->select()->from('user')->where('user_name','=',$username);
    $count = $query->execute()->rowCount();
    return $count>0?true:false;
  }
  public static function isReserved($username){
    global $pdo;
    global $config;
    return (array_search($username, $config['reserved_username']) !== false)?true:false;
  }
  /*
  add new user
  */
  public static function add($username,$email,$password,$name = ""){
    global $pdo;
    $passwordHasher = new Pentagonal\Phpass\PasswordHash(8,false);
    $pdo
      ->insert(array('user_name','user_email','user_fullname','user_hash'))
      ->into('user')
      ->values(array($username,$email,$name,$passwordHasher->hashPassword($password)))
      ->execute();
    return $pdo->lastInsertId();
  }
  /*
  get user data
  */
  public static function get(){
    return "";
  }

}
?>
