<?php

class AutoCarga {
    static public function cargar($clase) {
 $archivo = "clases/" . str_replace('\\', '/', $clase) . ".php";
 if (file_exists($archivo)) {
 require $archivo;
 }
 
 }


}
spl_autoload_register('AutoCarga::cargar');


