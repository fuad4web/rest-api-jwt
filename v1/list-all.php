<?php

    //headers
    ini_set("display_errors", 1);
    header('Access-Control-Allow-Origin: *'); // it allows all origin, any dfomain or subdomain whether localhost or real serve url
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

        $data = $student->select_all_students('tbl_projects');  //name of functionm to select all values am tryin to fetch

        if($data->rowCount() > 0) {

            $post_arr = array();
            $post_arr['records'] = array();

            // $students["records"] = array();
            while($row = $data->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                array_push($post_arr["records"], array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'mobile' => $row['mobile'],
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
        
    } else {
        
        http_response_code(503);
        echo json_encode([
            "status" => false,
            "message" => 'Access Denied'
        ]);

    }

?>
