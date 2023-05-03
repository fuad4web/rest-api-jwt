<?php

    //headers
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
        $student->id = $student->checkInput(isset($_GET['id']) ? $_GET['id'] : die());

        // SUBMIT DATA
        $student->name = $data->name;
        $student->email = $data->email;
        $student->mobile = $data->mobile;

        if(!empty($student->name) && !empty($student->email) && !empty($student->mobile)) {

            $name = $student->checkInput($student->name);
            $email = $student->checkInput($student->email);
            $mobile = $student->checkInput($student->mobile);

            if($student->check_not_exist_one_col($email, 'id', $student->id) == true) {

                echo json_encode(
                    ["status" => false,
                    "message" => "Email Existing for another User"]
                );

            } else {

                if($student->check_not_exist_one_col($mobile, 'id', $student->id) == true) {

                    echo json_encode(
                        ["status" => false,
                        "message" => "Phone Number Existing for another User"]
                    );
    
                } else {

                    $update_row = $student->update('id', $student->id, array('name'=>$name, 'email'=>$email, 'mobile' => $mobile));

                    if($update_row) {
                        echo json_encode([
                            "status" => true,
                            "message" => "Update Succesfull"
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
