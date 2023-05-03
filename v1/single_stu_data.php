<?php

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

    $student->id = $student->checkInput(isset($_GET['id']) ? $_GET['id'] : die());

    if($_SERVER['REQUEST_METHOD'] === "GET") {

        if(!empty($student->id)) {

            if($student->check_exist_one_col('id', $student->id) == true) {
                            
                $single_student = $student->select_one_val('name', 'id', $student->id);  //name of functionm to select a row  am tryin to fetch
                
                $post_arr = array(
                    'data' => $single_student
                );
    
                //convert to JSON and output it out
                http_response_code(202);
                echo json_encode($post_arr);
                echo json_encode([
                    "status" => true,
                    "message" => "Viewed Successfully"
                ]);

            } else {

                http_response_code(404);
                echo json_encode([
                    "status" => false,
                    "message" => 'Not Found'
                ]);

            }

        } else {
            echo json_encode([
                "status" => false,
                "message" => 'Id Value Empty'
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
