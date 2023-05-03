<?php

    //include headers
    header('Access-Control-Allow-Origin: *'); // it allows all origin, any dfomain or subdomain whether localhost or real serve url
    header('Content-Type: application/json; charset=UTF-8');  // data we are getting inside request
    header('Access-Control-Allow-Methods: POST');  // method type
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../config/database.php');
    include_once('../classes/student.php');

    // create object for database
    $db = new Database();

    $con = $db->connect();

    // create object for students
    $student = new Student($con);

    if($_SERVER['REQUEST_METHOD'] === "POST") {
        
        // if(isset[$_POST])
        $data = json_decode(file_get_contents('php://input'));

        // SUBMIT DATA
        $student->name = $data->name;
        $student->email = $data->email;
        $student->mobile = $data->mobile;

        if(!empty($student->name) && !empty($student->email) && !empty($student->mobile)) {

            $name = $student->checkInput($student->name);
            $email = $student->checkInput($student->email);
            $mobile = $student->checkInput($student->mobile);

            if($student->check_exist_one_col('email', $email) == true) {

                echo json_encode(
                    ["status" => false,
                    "message" => "Email Existing"]
                );

            } else {

                if($student->check_exist_one_col('mobile', $mobile) == true) {

                    echo json_encode(
                        ["status" => false,
                        "message" => "Phone Number Existing"]
                    );
    
                } else {

                    $create_row = $student->create(array('name'=>$name, 'email'=>$email, 'mobile' => $mobile));

                    if($create_row) {
                        echo json_encode([
                            "status" => true,
                            "message" => "Student Created Succesfully"
                        ]);
                    } else {
                        echo json_encode(
                            ["status" => false,
                            "message" => "Couldn't Process Request"]
                        );
                    }
        
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
