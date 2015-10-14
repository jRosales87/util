<?php

/**
 * ClassNombreClase
 *
 * @version 1.01
 * @author Antonio Javier Pérez Medina
 * @license http://...
 * @copyright izvbycv
 * Esta clase permite subir archivos de forma sencilla.
 */


class SubirArchivos {

    private $input;
    private $files;
    private $destino;
    private $nombre;

    const IGNORAR = 0;
    const RENOMBRAR = 2;
    const REEMPLAZAR = 1;
    const ERROR_INPUT = -1;

    private $accion;
    private $maximo;
    private $arrayExtensiones;
    private $arrayTipos;
    private $error_php;
    private $error;
    private $mensaje_error;
    private $crearCarpeta;

    function __construct($param) 
    {
        $this->input = $param;
        $this->destino = "subir/";
        $this->nombre = "";
        $this->accion = SubirArchivos::IGNORAR;
        $this->maximo = 5 * 1014 * 1024;
        $this->arrayTipos = array();
        $this->arrayExtensiones = array("bmp", "css", "doc", "exe", "gif",
            "html", "jpg", "log", "mp3", "pdf",
            "php", "ppt", "rar", "tif", "txt", "zip");
        $this->error_php = UPLOAD_ERR_OK;
        $this->error = 0;
        $this->mensaje_error = "";
        $this->crearCarpeta = true;
    }

    /**
     * Devuelve el nombre del archivo
     * @access public
     * @return string nombre
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Devuelve el destino del archivo
     * @access public
     * @return string destino
     */
    public function getDestino() {
        return $this->destino;
    }

    /**
     * Devuelve el tamaño máximo del archivo en MB
     * @access public
     * @return string máximo
     */
    public function getMaximo() {
        return $this->maximo / 1024 / 1024;
    }

    /**
     * Muestra los tipos MIME permitidos
     * @access public
     */
    public function getTipo() {
        foreach ($this->tipo as $value) {
            echo $value . "<br/>";
        }
    }

    /**
     * Devuelve la política establecida
     * @access public
     * @return string accion
     */
    public function getAccion() {
        return $this->accion;
    }

    /**
     * Devuelve el error php
     * @access public
     * @return string error_php
     */
    public function getErrorPHP() {
        return $this->error_php;
    }

    /**
     * Devuelve el error 
     * @access public
     * @return string error
     */
    public function getError() {
        return $this->error;
    }

    /**
     * Muestra el error o la ausencia de error
     * @access public
     * @return string mesnsaje de error
     */
    public function getErrorMensaje() {
        switch ($this->getError()) {
            case 0:
                echo 'Archivo/s subido/s';
                break;

            case -1:
                echo "Error en input";
                break;

            case -2:
                echo "Error, tamaño máximo permitido " . $this->getMaximo() . " MB";
                break;

            case -3:
                echo "Error, extensión/es no válida";
                break;

            case -4:
                echo "La carpeta destino no existia pero fue creada, Archivos subidos";
                break;

            case -5:
                echo "Error, El archivo/s ya existe, selecciona renombrar o reemplazar";
                break;

            case -6:
                echo "Error al renombrar el archivo/s";
                break;

            case -7:
                echo "Error al crear la carpeta de destino";
                break;
        }
    }

    /**
     * Asigna el nombre al archivo.
     * @access public
     * @param string $param Cadena con el valor del nombre a establecer
     */
    public function setNombre($param) {
        $this->nombre = $param;
    }

    /**
     * Asigna el destino del archivo.
     * @access public
     * @param string $param Cadena con el valor del destino a establecer
     */
    public function setDestino($param) {
        $caracter = substr($param, -1);
        if ($caracter != "/") {
            $param.="/";
        }
        $this->destino = $param;
    }

    /**
     * Asigna el tamaño maximo del archivo al archivo.
     * @access public
     * @param string $param Cadena con el valor del tamaño maximo a establecer
     */
    public function setMaximo($param) {
        $this->maximo = $param;
    }

    /**
     * Asigna la politica a seguir
     * @access public
     * @param string $param Cadena con el valor de la politica a seguir
     */
    public function setAccion($param) {
        if ($param == self::RENOMBRAR || $param == self::REEMPLAZAR || $param == self::IGNORAR) {
            $this->accion = $param;
        } else {
            $this->accion = self::IGNORAR;
        }
    }

    /**
     * Asigna la carpeta de destino
     * @access public
     * @param string $param Cadena con el valor de la carpeta de destino
     */
    public function setCrearCarpeta($param) {
        $this->crearCarpeta = $param;
    }

    /**
     * Establece el error ocurrido
     * @access public
     * @return string mensaje de error
     */
    private function setMensajeError() {
        switch ($this->error_php) {
            case UPLOAD_ERR_OK:
                $this->mensaje_error = "Archivo/s Subido/s";
                break;
            case UPLOAD_ERR_INI_SIZE:
                $this->mensaje_error = "Error, tamaño del archivo excede upload_max_filesize";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $this->mensaje_error = "Error, tamaño del archivo excede MAX_FILE_SIZE";
                break;
            case UPLOAD_ERR_PARTIAL:
                $this->mensaje_error = "Error, archivo subido sólo parcialmente";
                break;
            case UPLOAD_ERR_NO_FILE:
                $this->mensaje_error = "Error, no se ha subido archivo";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $this->mensaje_error = "Error, no existe carpeta temporal";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $this->mensaje_error = "Error, no se puede escribir en disco";
                break;
            case UPLOAD_ERR_EXTENSION:
                $this->mensaje_error = "Error, alguna extensión de php ha impedido subir el archivo";
                break;
        }
    }

    /**
     * Añade un tipo MIME valido
     * @access public
     * @param string $param Cadena con el valor del tipo MIME
     */
    public function addTipo($param) {
        if (is_array($param)) {
            $this->arrayTipos = array_merge($this->arrayTipos, $param);
        } else {
            $this->arrayTipos[] = $param;
        }
    }

    /**
     * Comprueba si el input ha llegado 
     * @access private
     * @return boolean true si ha llegado, false si no ha llegado
     */
    private function isInput() {
        if (!isset($_FILES[$this->input])) {
            $this->error = -1;
            return false;
        }
        return true;
    }

    /**
     * Comprueba si se ha habido errores durante la subida 
     * @access private
     * @return boolean true si hay errores, false si no hay errores
     */
    private function isError() {
        if ($this->error_php != UPLOAD_ERR_OK) {
            return true;
        }
        return false;
    }

    /**
     * Comprueba si el tamaño del archivo no supera el maximo 
     * @access private
     * @return boolean true si el tamaño es correcto, false si no es correcto
     */
    private function isTamano() {
        if ($this->files["size"] > $this->maximo) {
            $this->error = -2;
            return false;
        }
        return true;
    }

    /**
     * Comprueba si el tamaño del los archivos no supera el maximo 
     * @access private
     * @param string $param variable con la posicion del array de archivos
     * @return boolean true si el tamaño es correcto, false si no es correcto
     */
    private function isTamanoArchivos($param) {
        if ($this->files["size"][$param] > $this->maximo) {
            $this->error = -2;
            return false;
        }
        return true;
    }

    /**
     * Comprueba si la extension del archivo esta permitido 
     * @access private
     * @param string $param variable con la extension del archivo
     * @return boolean true si esta permitido, false si no esta permitido
     */
    private function isExtension($param) {
        if (sizeof($this->arrayExtensiones) > 0 && !in_array($param, $this->arrayExtensiones)) {
            $this->error = -3;
            return false;
        }
        return true;
    }

    /**
     * Comprueba si el tipo MIME esta permitido 
     * @access private
     * @param string $param variable con el tipo MIME
     * @return boolean true si esta permitido, false si no esta permitido
     */
    private function isTipo($param) {
        if (sizeof($this->tipo) > 0 && !in_array($param, $this->tipo)) {
            $this->error_php = "tipo MIME no valido";
            return false;
        }
        return true;
    }

    /**
     * Comprueba si la carpeta de destino existe
     * @access private
     * @return boolean true si existe, false si no existe
     */
    private function isCarpeta() {
        if (!file_exists($this->destino) && !is_dir($this->destino)) {
            $this->error = -4;
            return false;
        }
        return true;
    }

    /**
     * Crea la carpeta de destino
     * @access private
     * @return boolean true si el tamaño es correcto, false si no es correcto
     */
    private function crearCarpeta() {
        return mkdir($this->destino, true);
    }

    /**
     * Sube el archivo/s
     * @access public
     * @return boolean false si no se ha subido correctamente
     */
    public function subir() {
        $this->error = 0;
        if (!$this->isInput()) {
            return false;
        }
        $this->files = $_FILES[$this->input];

        if (!$this->isCarpeta()) {
            if ($this->crearCarpeta) {
                $this->error_php = 0;
                if (!$this->crearCarpeta()) {
                    $this->error = -7;
                    return false;
                }
            } else {
                return false;
            }
        }
        foreach ($this->files["name"] as $key => $value) {
            $this->subiendo($key);
        }

        $this->getErrorMensaje();
    }

    /**
     * Sube el archivo de $key
     * @access private
     * @param int $key variable con la posicion del archivo a subir dentro del array
     * de archivos
     * @return boolean false si no se ha conseguido subir
     */
    private function subiendo($key) {
        $this->error_php = $this->files["error"][$key];
        if ($this->isError()) {
            $this->mensaje_error;
            return;
        }

        if (!$this->isTamanoArchivos($key)) {
            $this->error = -2;
            return false;
        }

        $partes = pathinfo($this->files["name"][$key]);
        $extension = $partes['extension'];
        $nombreOriginal = $partes['filename'];

        if (!$this->isExtension($extension)) {
            $this->error = -3;
            return false;
        }

        if ($this->nombre === "") {
            $this->nombre = $nombreOriginal;
        }

        $origen = $this->files["tmp_name"][$key];
        $destino = $this->destino . $this->nombre . "." . $extension;

        if ($this->accion == SubirArchivos::REEMPLAZAR) {
            return move_uploaded_file($origen, $destino);
        } elseif ($this->accion == SubirArchivos::IGNORAR) {
            if (file_exists($destino)) {
                $this->error = -5;
                return false;
            }
            return move_uploaded_file($origen, $destino);
        } elseif ($this->accion == SubirArchivos::RENOMBRAR) {
            $i = 1;

            while (file_exists($destino)) {
                $destino = $this->destino . $this->nombre . "_$i." . $extension;
                $i++;
            }
            return move_uploaded_file($origen, $destino);
        }
        $this->error = -6;
        return false;
    }
}