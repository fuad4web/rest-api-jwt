<?php

    class Database {

        // variable declaration
        private $hostname;
        private $dbname;
        private $username;
        private $password;
        private $conn;

        public function connect() {

            // variable initialisation
            $this->hostname = "localhost";
            $this->dbname = "rest_php_api";
            $this->username = "root";
            $this->password = "";

            try {
                
                //$this->conn = new mysqli($this->hostname, $this->username, $this->password, $this->dbname);
                $this->conn = new PDO('mysql:host='.$this->hostname.'; dbname='.$this->dbname.'; charset=utf8', $this->username, $this->password);
                return $this->conn;

                //setting some database attributes
                $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $this->conn->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                define('APP_NAME', 'FUSKYDON PHP REST API TUTORIAL');

            } catch(PDOException $e) {
                echo 'Connection error! ' . $e->getMessage();
                echo '<script>alert("Database Connection Error");</script>';
            }

        }

    }

?>
