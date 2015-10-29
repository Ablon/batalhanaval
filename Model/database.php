<?php
class Connection {
    public static $instance;
    

    public static function getConnection() {
		$host = 'localhost';
		$db = 'naval';
		$user = 'root';
		$pass = '';
		$port = 3306;
        
		if (!is_object(self::$instance)) {
            self::$instance = new PDO("mysql:host=$host;dbname=$db;port=$port", $user, $pass);
			self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->exec("SET CHARACTER SET utf8");
            
        }
        
        return self::$instance;
    }
   
}

class Database {

    public static function query_default($query, $values = array()){
        $stmt = Connection::getConnection()->prepare($query);

        if(!empty($values)){
			
            foreach($values as $column => $value){
				
                $stmt->bindValue($column, $value);
            }
        }

        return $stmt->execute();
    }

    public static function select($sql, $fetch = false){
        $stmt = Connection::getConnection()->prepare($sql);
        $stmt->execute();
        if ($fetch){
            $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $stmt;
    }

    // Database::insert('jogador', array('nome' => 'Eduardo', 'idade' => 13))
    public static function insert($table, $content) {
        
        $column_num = 1;
        foreach($content as $column => $value){
			
            $values[$column_num] = $value; // passar depois para o $this->query_default
            $columns[] = $column;
            $values_on_query[] = '?';
			
			$column_num++;
        }
		
        $sql = "INSERT INTO $table (" . implode(', ', $columns)  . ") VALUES (" . implode(', ', $values_on_query) . ");";
		
        self::query_default($sql, $values);
    }


    // Database::update('jogador', array('nome' => 'Alberto'))
    public static function update() {
        $column_num = 1;
        foreach($content as $column => $value){
            $values[$column_num] = $value; // passar depois para o $this->query_default
            $columns[] = $column;
            $values_on_query[] = '?';
        }

        $sql = "UPDATE $table SET  VALUES (" . implode(', ', $values_on_query) . ");";

        $this->query_default($sql, $values);

    }
    public static function delete() { }

    // @TODO
    // update
    // delete
}