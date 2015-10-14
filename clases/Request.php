<?php


class Request{
    
    private static function clean($valor, $filtrar){
        if($filtrar===true){
        return htmlspecialchars($valor);
        }
        return trim($valor);
    }
    static function get($nombre, $filtrar=true, $indice=null){
        if (isset($_GET[$nombre])){
            return self::read($_GET[$nombre], $filtrar, $indice);                
        }
        return null;
    }
    static function post($nombre,$filtrar=true, $indice=null){
        if (isset($_POST[$nombre])){
            return self::read($_POST[$nombre],$filtrar, $indice);
        }
        return null;
    }
    static function req($nombre, $indice=null){
        $valor = self::post($nombre, $indice);
        if ($valor!=null){ 
            return $valor;    
        }
        return self::get($nombre, $indice);
        
    }
    private static function read($parametro,$filtrar=true, $indice = null){
       if(is_array($parametro)){
                if($indice === null){
                $array = array();
                foreach($parametro as $valor){
                    $array[] = self::clean($valor, $filtrar);
                }
                return $array;
            }
            else{
                if(isset($parametro[$indice])){
                    return self::clean($parametro[$indice], $filtrar);
                }
            }
            }
            else{
            return self::clean($parametro, $filtrar);
            }
        
    }
    static function elements($nombre){
        
    }
}


