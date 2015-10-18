<?php
class Conexao {
    public static $instance;
 
    public static function getConexao() {
        
        $local = 'localhost';
        $banco = 'mydb';
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

class Database {
    public static function insert($sql){
        $stmt = Conexao::getConexao()->prepare($sql);
        return $stmt->execute();
    }

    public static function select($sql, $fetch = false){
        $stmt = Conexao::getConexao()->prepare($sql);
        $stmt->execute();
        if ($fetch){
            $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $stmt;
    }

    // @TODO
    // update
    // delete
}