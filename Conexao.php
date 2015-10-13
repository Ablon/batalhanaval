<?php
class Conexao{
    public static $instance;
 
    public static function getConexao() {
        
        $local = 'localhost';
        $banco = 'batalha';
        $usuario = 'root';
        $senha = '';

        if (!isset(self::$instance)) {
            self::$instance = new PDO("mysql:host=$local;dbname=$banco", $usuario, $senha);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$instance->exec("SET CHARACTER SET utf8");
        } 
        
        return self::$instance;
    }
   
}