<?php

    //include headers
    ini_set("display_errors", 1);
    header('Access-Control-Allow-Origin: *'); // it allows all origin, any dfomain or subdomain whether localhost or real serve url
    header('Content-Type: application/json; charset=UTF-8');  // data we are getting inside request
    header('Access-Control-Allow-Methods: POST');  // method type

    include_once('../config/database.php');
    include_once('../classes/Users.php');

    // OBJECTS
    $db = new Database();

    // connect CONNECT Class from the database file
    $connects = $db->connect();

    $user_obj = new Users($connects);

    if($_SERVER['REQUEST_METHOD'] === "POST") {
        
        // if(isset[$_POST])
        $data = json_decode(file_get_contents('php://input'));

        // SUBMIT DATA
        $user_obj->name = $data->name;
        $user_obj->email = $data->email;
        // $user_obj->password = password_hash($data->password, PASSWORD_DEFAULT);
        $user_obj->password = md5($data->password);

        if(!empty($user_obj->name) && !empty($user_obj->email) && !empty($user_obj->password)) {

            $name = $user_obj->checkInput($user_obj->name);
            $email = $user_obj->checkInput($user_obj->email);
            $password = $user_obj->checkInput($user_obj->password);

            if($user_obj->check_exist_one_col('email', $email) == true) {

                echo json_encode([
                    "status" => false,
                    "message" => "Email Existing"
                ]);

            } else {

                $create_row = $user_obj->create('tbl_users', array('name'=>$name, 'email'=>$email, 'password' => $password));

                if($create_row) {
                    echo json_encode([
                        "status" => true,
                        "message" => "User Succesfully Created"
                    ]);
                } else {
                    echo json_encode(
                        ["status" => false,
                        "message" => "Couldn't Process Request"]
                    );
                }

            }

        } else {
            http_response_code(404);
            echo json_encode([
                "status" => false,
                "message" => 'Empty Value(s)'
            ]);
        }

    } else {
        http_response_code(503);
        echo json_encode([
            "status" => false,
            "message" => 'Access Denied'
        ]);
    }

?>
