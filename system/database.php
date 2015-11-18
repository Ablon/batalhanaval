<?php

/**
 * BloodStorm : BatalhaNaval
 * 2 EMIA - 2015
 *
 * Feito com <3 por:
 * Eduardo Augusto Ramos
 * Felipe Pereira Jorge
 * Laís Vitória
 * Alice Mantovani
 * Filipe Gianotto
 *
 */
class Connection {

    public static $instance;

    public static function getConnection() {
        $host = 'localhost';
        $db = 'bloodstorm';
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

    public static function query_default($query, $values = array(), $return_obj = false) {
        $stmt = Connection::getConnection()->prepare($query);

        if (!empty($values)) {

            foreach ($values as $column => $value) {

                $stmt->bindValue($column, $value);
            }
        }

        if ($return_obj == true) {
            $stmt->execute();
            return $stmt;
        } else {
            return $stmt->execute();
        }
    }

    public static function select($sql, $content = array(), $fetch = false) {
        $stmt = self::query_default($sql, $content, true);

        if ($fetch) {
            $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $stmt;
    }

    // Database::insert('jogador', array('nome' => 'Eduardo', 'idade' => 13))
    public static function insert($table, $content) {

        $column_num = 1;
        foreach ($content as $column => $value) {

            $values[$column_num] = $value; // passar depois para o $this->query_default
            $columns[] = $column;
            $values_on_query[] = '?';

            $column_num++;
        }

        $sql = "INSERT INTO $table (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values_on_query) . ");";

        return self::query_default($sql, $values);
    }

    // Database::update('jogador', array('nome' => 'Alberto'))
    public static function update($table, $updates, $where) {
        $num = 1;

        foreach ($updates as $column => $value) {
            $columns[] = "$column = ?";
            $values[$num] = $value;
            $num++;
        }

        foreach ($where as $column => $value) {
            $values[$num] = $value;
            $where_column = $column;
        }

        $sql = "UPDATE $table SET " . implode(", ", $columns) . " WHERE $where_column = ?";

        self::query_default($sql, $values);
    }

    // Database::delete('jogador', array('id' => 1))
    public static function delete($table, $content) { // básico ainda, só uma coluna
        foreach ($content as $column => $value) {
            $sql = "DELETE FROM $table WHERE $column = '$value'";
        }
        $this->query_default($sql);
    }

}
