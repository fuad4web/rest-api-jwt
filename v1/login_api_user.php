<?php

    ini_set("display_errors", 1);

    //include vendors
    require '../vendor/autoload.php';
    use \Firebase\JWT\JWT;

    //include headers
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
        $user_obj->email = $data->email;
        // $user_obj->password = password_hash($data->password, PASSWORD_DEFAULT);
        $user_obj->password = $data->password;

        if(!empty($user_obj->email) && !empty($user_obj->password)) {

            $email = $user_obj->checkInput($user_obj->email);
            $password = $user_obj->checkInput($user_obj->password);

            if($user_obj->login($email, $password) === false) {

             http_response_code(200);
                echo json_encode([
                    "status" => false,
                    "message" => "Email not Existing, Create an Account!"
                ]);

            } else {

                if($user_obj->login($email, $password) === 'invalid') {

                    echo json_encode([
                        "status" => false,
                        "message" => "Invalid Password"
                    ]);

                } elseif($user_obj->login($email, $password) !== false || $user_obj->login($email, $password) !== 'invalid') {

                    $user_datas = $user_obj->login($email, $password);
                    $user = $user_obj->userData($user_datas->id);

                    $secret_key = 'owt125';

                    $alg = 'HS256';

                    $iss = "localhost";
                    $iat = time();
                    $nbf = $iat + 10;
                    $exp = $iat + 30;
                    $aud = "myusers";
                    $user_arr_data = array(
                        "id" => $user->id,
                        "name" => $user->name,
                        "email" => $user->email
                    );
 
                    $payload_info = array(
                        "iss" => $iss,
                        "iat" => $iat,
                        "nbf" => $nbf,
                        "exp" => $exp,
                        "aud" => $aud,
                        "data" => $user_arr_data
                    );

                    $jwt = JWT::encode($payload_info, $secret_key, $alg);
                 
                    echo json_encode([
                        "status" => true,
                        "jwt" => $jwt,
                        "message" => "User Succesfully Login"
                    ]);

                } else {
                                     
                    echo json_encode([
                        "status" => false,
                        "message" => "Gerrout Jhor"
                    ]);

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
