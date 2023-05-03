<?php

    //include vendors
    require '../vendor/autoload.php';
    use \Firebase\JWT\JWT;
    use \Firebase\JWT\Key;

    //headers
    ini_set("display_errors", 1);
    header('Access-Control-Allow-Origin: *'); // it allows all origin, any dfomain or subdomain whether localhost or real serve url
    header('Content-type: application/json; charset=UTF-8'); //passing json data type
    header('Access-Control-Allow-Methods: GET');  // method type
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../config/database.php');
    include_once('../classes/student.php');

    // create object for database
    $db = new Database();

    $con = $db->connect();

    // create object for students
    $student = new Student($con);

    if($_SERVER['REQUEST_METHOD'] === "GET") {

        // $data = json_decode(file_get_contents('php://input'));
        $all_headers = getallheaders();
        foreach($all_headers as $key => $val){
            if($key == "Authorization") {

                if(!empty($val)) {

                    try {
                    
                    $secret_key = 'owt125';
        
                    $decoded_data = JWT::decode($val, new Key($secret_key, 'HS256'));
                //    echo $decoded_data->data->email;
                    echo json_encode(array(
                        "status" => true,
                        "message" => 'Seen JWT Token',
                        "user_data" => $decoded_data
                    ));
        
                    } catch(Exception $ex) {
                        http_response_code(500); //internal server error
                        echo json_encode(array(
                            "status" => false,
                            "message" => $ex->getMessage()
                        ));
                    }
        
                }
           
            }
        }

    } else {
        
        http_response_code(503);
        echo json_encode([
            "status" => false,
            "message" => 'Access Denied'
        ]);

    }

?>
