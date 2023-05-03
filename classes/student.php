<?php

    class Student {

        //declare variables
        public $name, $id;
        public $email;
        public $mobile;

        private $conn;
        private $table_name;

        // constructor
        public function __construct($db) {
            $this->conn = $db;
            $this->table_name = 'tbl_students';
        }

        
        //cleansing of values collected from replies
        public function checkInput($var) {
            $var = htmlspecialchars($var);
            $var = trim($var);
            $var = stripcslashes($var);
            $var = strip_tags($var);
            return $var;
        }


        public function create($fields = array()) {
            
            // remove the , from the key values in the fields(i.e the values input into databse)
            $columns = implode(',', array_keys($fields));
            $values = ':'.implode(', :', array_keys($fields));
            $sql = "INSERT INTO {$this->table_name} ({$columns}) VALUES ({$values})";
            // var_dump($sql);
            // exit();

            if($stmt = $this->conn->prepare($sql)) {

                foreach($fields as $key => $data) {
                    $stmt->bindValue(`:`.$key, $data);
                }

                $finalise = $stmt->execute();
                return $this->conn->lastInsertId();

                if($finalise) {
                    return true;
                } else {
                    //print error if something goes wrong
                    printf("Error %s. \n", $stmt->error);
                    return false;
                }
                
            }

        }

        
        // to check/verify whether something is existing in a table with 1 conditions
        public function check_exist_one_col($column, $keyword) {
            $stmt = $this->conn->prepare("SELECT `$column` FROM $this->table_name WHERE `$column` = :keyword");
            $stmt->bindParam(":keyword", $keyword, PDO::PARAM_STR);
            $stmt->execute();

            $count = $stmt->rowCount();
            if($count > 0) {
                return true;
            } else {
                return false;
            }
        }

        // to check/verify whether something is existing in a table with 1 conditions
        public function check_not_exist_one_col($column, $first_column, $keyword) {
            $stmt = $this->conn->prepare("SELECT `$column` FROM $this->table_name WHERE `$first_column` != :keyword");
            $stmt->bindParam(":keyword", $keyword, PDO::PARAM_STR);
            $stmt->execute();
            
            $count = $stmt->rowCount();
            if($count > 0) {
                return true;
            } else {
                return false;
            }
        }

        
        // to check/verify whether something is existing in a table with 2 conditions
        public function check_exist_two_col($column, $first_column, $first_keyword, $second_column, $second_keyword) {
            $stmt = $this->conn->prepare("SELECT `$column` FROM `$this->table_name` WHERE `$first_column` = :first_keyword AND `$second_column` = :second_keyword");
            $stmt->bindParam(":first_keyword", $first_keyword, PDO::PARAM_STR);
            $stmt->bindParam(":second_keyword", $second_keyword, PDO::PARAM_STR);
            $stmt->execute();

            $count = $stmt->rowCount();
            if($count > 0) {
                return true;
            } else {
                return false;
            }
        }
        

        public function select_all_students($table) {
            $stmt = $this->conn->prepare("SELECT * FROM " .$table);
            $stmt->execute();
            return $stmt;
            // $fetch_students = $stmt->fetch(PDO::FETCH_ASSOC);
            // return $fetch_students;
        }


        // selecting just a value from table with a condition
        public function select_one_val($column, $value, $keyword) {
            $stmt = $this->conn->prepare("SELECT `$column` FROM $this->table_name WHERE `$value` = :keyword");
            $stmt->bindParam(":keyword", $keyword, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        
        
        // selecting all columns and values from table with a condition in ascending order
        public function select_all_one_cond($column, $keyword) {
            $stmt = $this->conn->prepare("SELECT * FROM $this->table_name WHERE `$column` = :keyword");
            $stmt->bindParam(":keyword", $keyword, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);

            // $this->id = $fetch_single['id'];
            // $this->name = $fetch_single['name'];
            // $this->email = $fetch_single['email'];
            // $this->mobile = $fetch_single['mobile'];
            // $this->status = $fetch_single['status'];
            // $this->created_at = $fetch_single['created_at'];
        }

        

        //update function
        public function update($column, $id, $fields = array()) {
            $columns = '';
            $i = 1;

            foreach($fields as $name => $value) {
                if($i == 1)$columns .= "`$name` = '$value'";
                else $columns .= ", `$name` = '$value'";
                $i++;
            }

            $sql = "UPDATE $this->table_name SET $columns WHERE `$column` = {$id}";
            // var_dump($sql);
            $stmt = $this->conn->prepare($sql);
            return $stmt -> execute();

            if($stmt) {
                return true;
            } else {
                //print error if something goes wrong
                printf("Error %s. \n", $stmt->error);
                return false;
            }
        }

        
        //delete function
        public function delete($column, $keyword) {
            $stmt = $this->conn->prepare("DELETE FROM `$this->table_name` WHERE `$column` = :keyword");
            $stmt->bindParam(":keyword", $keyword, PDO::PARAM_STR);
            if($stmt->execute()) {
                return true;
            } else {
                //print error if something goes wrong
                printf("Error %s. \n", $stmt->error);
                return false;
            }
        }
        


        // public function select_one_value_two_cond($column, $first_column, $second_column, $first_keyword, $second_keyword) {
        //     $stmt = $this->conn->prepare("SELECT `$column` FROM " .$this->table. " WHERE `$first_column` = :first_keyword AND `$second_column` = :second_keyword");
        //     $stmt->bindParam(":first_keyword", $first_keyword, PDO::PARAM_STR);
        //     $stmt->bindParam(":second_keyword", $second_keyword, PDO::PARAM_STR);
        //     $stmt->execute();
        //     $fetch_single = $stmt->fetch(PDO::FETCH_ASSOC);
        //     $this->author = $fetch_single['author'];
        // }


    }

?>
