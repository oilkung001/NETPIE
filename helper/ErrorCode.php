<?php
/**
 *
 */
class ErrorCode
{
  function get($code,$attach = null){
    $c = array();
    $msg = self::build($code,$attach);
    if(is_null($msg)){
      $c['error']['code'] = 0;
      $c['error']['message'] = 'unknown error';
    }else{
      $c['error']['code'] = $code;
      $c['error']['message'] = $msg;
    }
    return $c;
  }
  function build($code,$attach = null)
  {
    if($code == 1){
      return 'access_token require';
    }else if($code == 2){
      return 'access_token is invalid';
    }else if($code == 3){
      return 'you don\'t have permission.';
    }else if($code == 4){
      return 'parameter name,email,username and password is require for register';
    }else if($code == 5){
      return 'this email has been already register';
    }else if($code == 6){
      return 'this username has been already register';
    }else if($code == 7){
      return 'this username is reserved for system';
    }else if($code == 8){
      return 'this username is malform';
    }else if($code == 9){
      return 'user not found';
    }else if($code == 10){
      return 'repo not found';
    }else if($code == 11){
      return 'parameter name is require for create new repo';
    }else if($code == 12){
      return 'this repo name is already exist on server';
    }else if($code == 14){
      return 'this repo name is reserved by system';
    }else if($code == 15){
      return 'user and password is require for authenization';
    }else if($code == 16){
      return 'user or password is mismatch';
    }else if($code == 17){
      return 'user not have permission to write this repo';
    }else if($code == 18){
      return 'user not have permission to read this repo';
    }else if($code == 19){
      return 'this story order is already exist';
    }else if($code == 20){
      return 'this story isn\'t belong to this repo';
    }else if($code == 21){
      return 'parameter story and type is require for create new node';
    }else if($code == 22){
      return 'this type is\'t acceptable';
    }else if($code == 23){
      return 'this node isn\'t belong to this repo';
    }else if($code == 24){
      return 'this edge order is already exist';
    }else if($code == 25){
      return 'parameter current and next is require for create new node';
    }else if($code == 26){
      return 'node current isn\'t belong to this repo';
    }else if($code == 27){
      return 'node next isn\'t belong to this repo';
    }else if($code == 28){
      return 'edge isn\'t exist';
    }else if($code == 29){
      return 'parameter input is require for message response';
    }else if($code == 30){
      return 'doesn\'t have any response';
    }else if($code == 31){
      return 'name is required';
    }else if($code == 32){
      return 'value is required';
    }else if($code == 33){
      return 'this variable isn\'t belong to this repo';
    }else{
      return null;
    }
  }
}
?>
