<?php


class Validar {
   static function isEmail($dato){
       return filter_var($dato, FILTER_VALIDATE_EMAIL);       
   }
   
   static function isNumber($dato){
       return filter_var($dato, FILTER_VALIDATE_INT);       
   }
   
   static function isIP($dato){
       return filter_var($dato, FILTER_VALIDATE_IP);       
   }
   
   static function isFloat($dato){
       return filter_var($dato, FILTER_VALIDATE_FLOAT);       
   }
   
   static function isURL($dato){
       return filter_var($dato, FILTER_VALIDATE_URL);       
   }
   
   static function isMinLength($dato, $long){
       return strlen($dato) >= $long;
   }
   
   static function isLogin($dato){
       return preg_match('/^[A-Za-z][A-Za-z0-9]{5,9}$/', $dato);
   }

}

