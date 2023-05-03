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

     $all_headers = getallheaders();
     
     $jwt = $all_headers['authorization'];
     
    //  foreach($all_headers as $key => $val) {
        //  if($key == "Authorization") {

            if(!empty($jwt)) {
               
             try {
                      
                 $secret_key = 'owt125';

                //  $decoded_data = JWT::decode($val, new Key($secret_key, 'HS256'));
                    $decoded_data = JWT::decode($jwt, new Key($secret_key, 'HS256'));
                                  
                 $listtem = $student->select_all_students('tbl_projects');  //name of functionm to select a row  am tryin to fetch

                 if($listtem->rowCount() > 0) {

                  $post_arr = array();
                  $post_arr['records'] = array();
      
                  // $students["records"] = array();
                  while($row = $listtem->fetch(PDO::FETCH_ASSOC)) {
                      extract($row);
                      array_push($post_arr["records"], array(
                          'id' => $row['id'],
                          'user_id' => $row['user_id'],
                          'name' => $row['name'],
                          'description' => $row['description'],
                          'status' => $row['status'],
                          'created_at' => date("Y-m-d", strtotime($row['created_at']))
                      ));
                  }
      
                  http_response_code(200);
                  echo json_encode(array(
                      "status" => true,
                      "data" => $post_arr
                  ));
      
                  } else {
                      http_response_code(404);
                      echo json_encode([
                          "status" => false,
                          "message" => 'Not Found'
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
                echo json_encode([
                    "status" => false,
                    "message" => 'Empty Value'
                ]);
            }
          
        //  }
    //  }

    } else {
        
        http_response_code(503);
        echo json_encode([
            "status" => false,
            "message" => 'Access Denied'
        ]);

    }

?>
