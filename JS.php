<?php
 Class JString {
     private $str = "";
     /****************************************************/
     public function __construct($string = null)
     {
         $this->str = $this->getString($string);
         $rawString = $this->getString($string);
         $this->str = "".$rawString;
     }
     /****************************************************/
     public function __toString()        
     {
         return $this->str;
     }
     /****************************************************/
     public function getString($string = null)
     {
         return $string."";
     }
     /****************************************************/
     public function length()
     {
         return mb_strlen($this->str);
     }
     /****************************************************/
     public function append($string = null)
     {
         $this->insert($this->length(),$string);
         return $this;
     }
     /****************************************************/
     public function insert($index = 0,$string = null)
     {
     $objString = new JString($string);
     $before = $index == 0 ? "" : $this->substring(0,$index);
     $after = $this->substring($index,$this->length());
     $this->str = $before.$objString.$after;
     return $this;
     }
     /****************************************************/
     public function substring($start = 0,$end = 0)
     {
         $newStr = ($start == $end) ? "" : mb_substr($this->str,$start,($end>0)? $end - $start : null);
         return new JString($newStr);
     }
     /****************************************************/
     public function reverse(){
         return new JString(implode("",array_reverse($this->getChars())));
     }
     /****************************************************/
     public function getChars($start=0,$end = 0)
     {
         $ret = array();
         $max = ($end > 0 && $end < $this->length()) ? $end : $this->length();
         
         for ($i = $start;$i<$max;$i++){
             $ret[] = $this->substring($i,$i+1);
         }
         return $ret;
     }
     /****************************************************/
     public function charAt($position)
     {
     $ret = "";
     $chars = $this->getChars();
     if(isset($chars[$position])){
         $ret = $chars[$position];
     }
     return $ret;  
     }
     /****************************************************/
     public function equals($string = null)
     {
         return $this->str == $this->getString($string);
     }
     /****************************************************/
     public function startsWith($string = null)
     {
     return ($this->indexOf($string)== 0);    
     }
     /****************************************************/
     public function endsWith($string = null)
     {
       $stringObj = new JString($string);
       $revInput = $stringObj->reverse();
       $revOrig = $this->reverse();
       return $revOrig->startsWith($revInput);
     }
     /****************************************************/
     public function IndexOf($string  = null,$offset = 0)
     {
     $ret = -1;
     $rawString = $this->getString($string);
     if($rawString != "")
     {
      $result  = mb_strpos($this->str,$rawString,$offset);
      if($result !== false)
     {
          $ret = $result;
     }
     }
     return $ret;
     }
     /****************************************************/
     public function lastIndexOf($string = null,$offset = 0)
     {
         $stringObj =  new JString($string);
         $revInput = $stringObj->reverse();
         $revOrig = $this->reverse();
         $result = $revOrig->IndexOf($revInput,$offset);
         if($result != -1){
             $ret = $revOrig->length() -1 -$result; 
         }
         else {
             $ret = $result;
         }
         return $ret;
     }
     /****************************************************/
     public function concat($string1)
     {
     return new JString($this->str.$this->getString($string1));    
     }
     /****************************************************/
     public function replace($what = null,$with = null){
         return new JString(str_replace($this->getString($what),$this->getString($with), $this->str));
     }
     /****************************************************/
     public function replaceNewLines($with = null){
         return new JString($this->replace("\r\n",$with)->replace("\r",$with)->replace("\n",$with));
     }
     /****************************************************/
     public function trim()
     {
     return new JString(trim($this->str));    
     }
     /****************************************************/
     public function toLowerCase()
     {
     return new JString(mb_strtolower($this->str));    
     }
     /****************************************************/
     public function toUpperCase()
     {
     return new JString(mb_strtoupper($this->str));    
     }
     /****************************************************/
     public function contains($string = null)
     {
     return $this->IndexOf($string) != -1;    
     }
        /****************************************************/
     public function isEmpty()
     {
         return $this->length() == 0;
         
     }
        /****************************************************/
        public function replaceFirst($what = null,$with = null)
                
     {
      if($this->contains($what)){
          $objWhat = new JString($what);
          $objWith = new JString($with);
          
          $startIndex  = $this->IndexOf($objWhat);
          $endIndex = $startIndex + $objWhat->length();
          return new JString($this->substring(0,$startIndex).$objWith.$this->substring($endIndex) );
      } else
      {
      return $this;   
            }
        }
      /****************************************************/
        public function replaceLast($what = null,$with = null)
        {
        $objWhat = new JString($what);
        $objWith = new JString($with);
        
        return $this->replace()->replaceFirst($objWhat->reverse(),$objWith->reverse())->reverse();
        }
     
        /****************************************************/
     
     public function split($string = null)
       {
         $rawString = $this->getString($string);
         $retRaw = $rawString != "" ? explode($rawString, $this->str) : array();
         $ret = array();
         foreach ($retRaw as $current){
             $ret[] = new JString($current);
         }
         return $ret;
       }
          /****************************************************/
 public function splitPlain($string  = null)
 {
     $result = [];
     $data = $this->split($string);
     foreach ($dara as $current){
         $resul[] = $current."";
     }
     return $result;
 }
    /****************************************************/
 
    public function htmlSpecialChars()
    {
    return new JString(htmlSpecialChars($this->str));    
    }
        /****************************************************/
    public function stripsSlashes(){
        return new JString(stripsSlashes($this->str));
    }
        /****************************************************/
    
    public function toCamelCase(){
        
        $ret = $this->toLowerCase();
        while(true){
            $i  =$ret->IndexOf("_");
            if($i == -1){
                break;
            }
            $before  = $ret->substring(0,$i);
            $toUp = $i<$ret->length() - 1 ? $ret->charAt($i+1)->toLowerCase(): "";
            $after = $ret->substring($i+2);
            $ret  = new JString($before.$toUp.$after);
        }
        return $ret;
    }
 /****************************************************/
    public function fromCamelCase()
    {
        $ret = new JString();
        $chars  = $this->getChars();
        foreach ($chars as $currentChar){
            if($currentChar->toLowerCase() == $currentChar){
                $ret->append($currentChar);
            }
            else {
                $ret->append("_")->append($currentChar->toLowerCase());
            }
        }
        return $ret;
    }
  /****************************************************/
    public function trimTo($count){
        if($count >= $this->length()){
            return new JString($this->str);
        }
        else {
            $tmpString = $this->substring(0,$count);
            $index = $tmpString->lastIndexOf(" ");
            return $this->substring(0,$index)->append(" ...");
        }
    }
 
     /****************************************************/
    public function recursiveReplace($what,$to){
        
        $result  = new JString($this);
        if(!static::from($to)->contains($what)){
            while($result->contains($what)){
                $result = $result->replace($what,$to);
            }
        }
        return $result;
    }
     /****************************************************/
    
    public static function from($string){
        return reverse($string);
    }
 }
 
  /****************************************************/
?>