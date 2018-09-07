<?php
  /*
  IRIN language
  https://github.com/pureexe/irin-lang
  partiailly port for NAMSAI
  */
  class IrinLang{
    /*
      translate from IrinLang to Regex
      Note: use multibyte to support thai language
    */
    public static function toRegex($expression){
      $expression = self::escape($expression);
      $regularExp = "";
      $i = 0;
      while($i<mb_strlen($expression)){
        if($i < mb_strlen ($expression)-1 && mb_substr($expression,$i,2) == "\\["){
          $j=1;
          while(mb_substr($expression,$i+$j,1)!="]"){
            $j++;
          }
          $optionals = mb_split("\\\\\\|",mb_substr($expression,$i+2,$j-3));
          if($i+$j<mb_strlen($expression) && mb_substr($expression,$i+$j+1,1) == " "){
            $k=0;
            while($k<mb_strlen($expression)){
              $optionals[$k] = $optionals[$k]."(?:\\s|\\b)+";
              $k++;
            }
            $j++;
          }
          if($i>0 && mb_substr($expression,$i-1,1) == " "){
            $regularExp = trim($regularExp);
            $k=0;
            while($k<count($optionals)){
              $optionals[$k] = "(?:\\s|\\b)+".$optionals[$k];
              $k++;
            }
            array_push($optionals,"(?:\\b|\\s)+");
          }else{
            array_unshift($optionals,"");
          }
          $optionals = join('|', $optionals);
          $optional = mb_ereg_replace('\\\\\\*','(?:.+)',$optionals);
          $regularExp.="(?:".$optionals.")";
          $i+=$j+1;
        }else if($i < mb_strlen($expression)-1 && mb_substr($expression,$i,2) == "\\("){
          $j = 1;
          while(mb_substr($expression,$i+$j,1) != ")"){
            $j++;
          }
          $optionals = mb_split("\\|",mb_substr($expression,$i+2,$j-3));
          $regularExp .= "(".join('|', $optionals).")";
          $i+=$j+1;
        }else{
          $regularExp.=mb_substr($expression,$i,1);
          $i++;
        }
      }
      $regularExp = mb_ereg_replace('\\\\\\*','(.+)',$regularExp);
      return "^".$regularExp."$";
    }
    /*
    Escape regular expression
    use PHP escape than manually in original version
    */
    public static function escape($expression){
      return preg_quote($expression);
    }
    /*Mysql Friendly Support*/
    public static function toMysql($expression){
      return str_replace("(?:","(",$expression);
    }
  }
?>
