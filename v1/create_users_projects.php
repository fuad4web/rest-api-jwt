<?php
    
    ini_set("display_errors", 1);    //include headers
    
    //include vendors
    require '../vendor/autoload.php';
    use \Firebase\JWT\JWT;
    use \Firebase\JWT\Key;

    header('Access-Control-Allow-Origin: *'); // it allows all origin, any dfomain or subdomain whether localhost or real serve url
    header('Content-Type: application/json; charset=UTF-8');  // data we are getting inside request
    header('Access-Control-Allow-Methods: POST');  // method type

    include_once('../config/database.php');
    include_once('../classes/Users.php');

    // OBJECTS
    $db = new Database();

    $connects = $db->connect();          // connect CONNECT Class from the database file

    $user_obj = new Users($connects);

    if($_SERVER['REQUEST_METHOD'] === "POST") {
        
        $data = json_decode(file_get_contents('php://input'));

        // SUBMIT DATA
        // $user_obj->user_id = $data->user_id;
        $user_obj->name = $data->name;
        $user_obj->description = $data->description;
        $user_obj->status = $data->status;
        
        $all_headers = getallheaders();
        $jwt = $all_headers['authorization'];

        // foreach($all_headers as $key => $val){

            // if($jwt == "authorization") {
            if(!empty($jwt)) {
                
                try {
                    
                    if(!empty($user_obj->name) && !empty($user_obj->description) && !empty($user_obj->status)) {
                        
                        $secret_key = 'owt125';
            
                        // $decoded_data = JWT::decode($val, new Key($secret_key, 'HS256'));
                        $decoded_data = JWT::decode($jwt, new Key($secret_key, 'HS256'));

                        $user_id = $user_obj->checkInput($decoded_data->data->id);

                        $name = $user_obj->checkInput($user_obj->name);
                        $description = $user_obj->checkInput($user_obj->description);
                        $status = $user_obj->checkInput($user_obj->status);

                        $create_row = $user_obj->create('tbl_projects', array('user_id'=>$user_id, 'name'=>$name, 'description' => $description, 'status' => $status));
                        var_dump($create_row);

                        if($create_row) {
                        http_response_code(200);
                            echo json_encode([
                                "status" => true,
                                "message" => "User Project Created Successfully"
                            ]);
                        } else {
                            echo json_encode(
                                ["status" => false,
                                "message" => "Couldn't Process Request"]
                            );
                        }

                } else {
                    http_response_code(404);
                    echo json_encode([
                        "status" => false,
                        "message" => 'Fill in all Fields'
                    ]);
                }
               
               } catch(Exception $ex) {
                   http_response_code(500); //internal server error
                   echo json_encode(array(
                       "status" => false,
                       "message" => $ex->getMessage()
                   ));
               }
              } else {
               echo json_encode(array(
                   "status" => false,
                   "message" => "You are not logged In"
               ));
              }
            // }

        // }

    } else {
        http_response_code(503);
        echo json_encode([
            "status" => false,
            "message" => 'Access Denied'
        ]);
    }

?>
