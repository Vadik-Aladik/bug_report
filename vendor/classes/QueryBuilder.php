<?php

    require "Database.php";
    class QueryBuilder
    {
        private $db;
        private $connect;
        private $query = []; // Глобальное свойство для сборки запроса из нескольких методов
        private $param_types = []; // Типы данных згачений для подготовленного запроса

        private $query_values = []; 
        private $column = [];
        private $param;

        private $where = false; // Флаг для вывода записи, в случае, если есть where в запросе, то отправить подготовленный запрос, если нет то просто запрос

        public function __construct()
        {
            $this->db = new Database();
            $this->connect = $this->db->connect;
        }
        
        // ___________INSERT___________

        public function create(string $table, array $data)
        {
            $this->param_types = [];

            $column = implode(",", array_keys($data));
            $values = implode(",", array_fill(0, count($data), "?"));

            foreach ($data as $elem) {
                $this->type_param($elem);
            }

            $param = implode($this->param_types);

            $sql = "INSERT INTO $table ($column) VALUES ($values)";
            $query_local = $this->connect->prepare($sql);
            $query_local->bind_param($param, ...array_values($data));

            $succes = $query_local->execute();
            $query_local->close();

            return $succes;
        }

        // ___________SELECT___________

        public function select($table, $column = '*')
        {
            $this->reset_variable();

            $sql = "SELECT $column FROM $table";

            $this->query[] = $sql;
            return $this;
        }

        public function where(array $query_array) // 0.Column table 1.Value 2.Logic symbol (= < > and more) 3.Operation (AND OR) !! if you want some WHERE create two-dimensional array!! 
        {                                                 // where([["email", 'qwer01@mail.ru', '=', 'OR'], ['id', '4', '<'], ['login', 'james']]) OR where([['login', 'james']])
            
            // $this->param_types = [];
            // $this->query_values = [];
            $this->where = true;
            $sql_line = "";

            foreach($query_array as $query_elem){
                if(is_array(value: $query_elem)){
                    $column = $query_elem[0];
                    $this->query_values[] = $query_elem[1];

                    $symbol_logic = $query_elem[2] ?? "=";
                    $operation = $query_elem[3] ?? "AND";

                    $this->type_param($query_elem[1]);

                    $sql_line .= "$column $symbol_logic ? $operation" . " ";
                }
                else{
                    $column = $query_array[0];
                    $this->query_values[] = $query_array[1];

                    $this->type_param($query_array[1]);

                    $symbol_logic = $query_array[2] ?? "=";
                    $sql = "WHERE $column $symbol_logic ?";
                    $this->query[] = $sql;
                    return $this;
                }
            }

            $sql = "WHERE " . $sql_line;
            $sql_explode = explode(" ", $sql);
            array_splice($sql_explode, -2);
            $sql_final = implode(" ", $sql_explode);

            $this->query[] = $sql_final;
            return $this;
        }

        public function one()
        {
            $sql = implode(" ", $this->query) . " LIMIT 1";

            $result = $this->select_get_result($sql);

            $row = $result->fetch_assoc();

            $result->close();

            $this->reset_variable();

            return $row;
        }

        public function all()
        {
            $data = [];
            $sql = implode(" ", $this->query);

            $result = $this->select_get_result($sql);

            while($row = $result->fetch_assoc()){
                $data[] = $row;
            }

            $result->close();

            $this->reset_variable();

            return $data;
        }

        public function test()
        {
            $sql = implode(" ", $this->query);
            $param = implode($this->param_types);
            
            var_dump($sql, $param);
        }

        // ___________JOIN___________

        public function innerJoin($table_join, $local_key, $foreign_key)
        {
            $select_explode = explode(" ",$this->query[0]);
            $column = $select_explode[count($select_explode) - 1];
            $sql = "INNER JOIN $table_join ON $column.$local_key = $table_join.$foreign_key";

            $this->query[] = $sql;
            return $this;
        }

        // ___________UPDATE DELETE__________________

        public function update(string $table, array $set)       //('table', ['column' => 'date', 'column1' => 'date1']) || ('table', ['column' => 'date'])
        {
            $this->reset_variable();
            $sql_line = ""; 
            $set_len = count($set);
            $i = 0;
            //  UPDATE $table SET $column0 = "new date" WHERE id = 2 || UPDATE $table SET $column0 = "new date", $column1 = "new date 1" WHERE id = 2
            $sql_line .= "UPDATE $table SET ";

            foreach($set as $query_key=>$query_elem){
                $i++;
                if($i != $set_len){
                    $sql_line .= "$query_key = ?, ";
                }
                else{
                    $sql_line .= "$query_key = ? ";
                }
                $this->type_param($query_elem);
                $this->query_values[] = $query_elem;
            }

            $this->query[] = $sql_line;
            return $this;
        }

        public function delete($table)
        {
            $this->reset_variable();
            $sql_line = "";

            $sql_line .= "DELETE FROM $table";

            $this->query[] = $sql_line;
            return $this;
        }

        public function go()
        {
            $sql = implode(" ", $this->query);
            $this->prepare_query($sql);

            $this->reset_variable();
        }

        // ___________HELPER METHODS___________

        private function type_param($meaning)
        {
            if(is_numeric($meaning)){
                array_push($this->param_types, "i");
            }
            else if(is_float($meaning)){
                array_push($this->param_types, "d");
            }
            else if(is_string($meaning)){
                array_push($this->param_types, "s");
            }
            else{
                array_push($this->param_types, "s");
            }
        }

        private function prepare_query($sql)
        {
            $param = implode($this->param_types);

            $query_local = $this->connect->prepare($sql);
            $query_local->bind_param($param, ...array_values($this->query_values));
            $success = $query_local->execute();
            $query_local->close();

            return $success;

            // var_dump($sql);
            // var_dump($param);
            // var_dump(array_values($this->query_values));
        }

        private function select_get_result($sql)
        {
            if($this->where){
                $param = implode($this->param_types);
                $query_local = $this->connect->prepare($sql);
                $query_local->bind_param($param, ...array_values($this->query_values));
            }
            else{
                $query_local = $this->connect->prepare($sql);
            }

            $query_local->execute();
            $result = $query_local->get_result();
            return $result;
        }

        private function reset_variable(bool $where = false)
        {
            $this->param_types = [];
            $this->query_values = [];
            $this->query = [];
            $this->where = $where;
        }

        

    }

?>