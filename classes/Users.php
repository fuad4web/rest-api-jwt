<?php

    class Users {

        //define properties
        public $name;
        public $email;
        public $password;
        public $user_id;
        public $project_name;
        public $description;
        public $status;

        private $conn;
        private $users_tbl;
        private $projects_tbl;

        // constructor
        public function __construct($db) {
            $this->conn = $db;
            $this->users_tbl = 'tbl_users';
            $this->projects_tbl = 'tbl_projects';
        }

        //cleansing of values collected from replies
        public function checkInput($var) {
            $var = htmlspecialchars($var);
            $var = trim($var);
            $var = stripcslashes($var);
            $var = strip_tags($var);
            return $var;
        }

        
        // to check/verify whether something is existing in a table with 1 conditions
        public function check_exist_one_col($column, $keyword) {
            $stmt = $this->conn->prepare("SELECT `$column` FROM $this->users_tbl WHERE `$column` = :keyword");
            $stmt->bindParam(":keyword", $keyword, PDO::PARAM_STR);
            $stmt->execute();

            $count = $stmt->rowCount();
            if($count > 0) {
                return true;
            } else {
                return false;
            }
        }

        
        public function create($table, $fields = array()) {
            // remove the , from the key values in the fields(i.e the values input into databse)
            $columns = implode(',', array_keys($fields));
            $values = ':'.implode(', :', array_keys($fields));
            $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";
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

        
        public function userData($user_id) {
            $stmt = $this->conn->prepare("SELECT * FROM $this->users_tbl WHERE `id` = :id");
            $stmt->bindParam(":id", $user_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        }


        public function login($email, $password) {
            $stmt = $this->conn->prepare("SELECT * FROM $this->users_tbl WHERE `email` = :email");
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();

            $count = $stmt->rowCount();

           if($count > 0) {
                // if($user->password == password_verify($password, $user->password)) {
                    // echo $password . "    " . $user->password;
                    // exit();
                $user = $stmt->fetch(PDO::FETCH_OBJ);
                if(md5($password) == $user->password) {
                    return $user;
                } else {
                    return 'invalid';
                }
            } else {
                return false;
            }
        }

        public function getheads($all_headers, $heads) {
            // $stmt = $this->conn->prepare("SELECT * FROM $this->users_tbl WHERE `id` = :id");
            // $stmt->bindParam(":id", $user_id, PDO::PARAM_STR);
            // $stmt->execute();
            // return $stmt->fetch(PDO::FETCH_OBJ);

            foreach($all_headers as $key => $data) {
                $heads->bindValue($key, $data);
            }
            return $heads->fetch(PDO::FETCH_OBJ);
            // foreach ($all_headers as $name => $value) {
            //     // "$name => $value";
            // $heads = "$name => $value";
            // return $heads->fetch(PDO::FETCH_OBJ);
            // // return $heads;
            // }
        }


    }

?>
