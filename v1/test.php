<?php


// $payload = [
//      'name' => 'Owadayo Samuel',
//      'email'=> 'owadayosamdam@gmail.com',
//      'password'=> 'Samuel12__@'
// ];

// $header = [
//      // 'Authorization: Bearer SECRET_KEY'
// ];
// $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://api.worthreadingapp.com/api/v1/login',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'POST',
//   CURLOPT_POSTFIELDS => $payload,
//   CURLOPT_HTTPHEADER => $header,
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// // echo $response['email'];
// $data = json_decode($response);
if(isset($_POST['email'])){
     // echo $_POST['password'];
     if(!empty($_POST['email']) && !empty($_POST['password'])){
          $payload = [
               'email' => $_POST['email'],
               'password' => $_POST['password'],
          ];
          $header = [
               'Authorization: Bearer SECRET_KEY'
          ];
          $curl = curl_init();
          
          curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.worthreadingapp.com/api/v1/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => $header,
          ));
          
          $response = curl_exec($curl);
          
          curl_close($curl);
          // echo $response['email'];
          $data = json_decode($response);
     }else{
          $response = [
               'status' => false,
               'message' => 'Fields are required',
          ];
          $data = json_encode($response);
     }
     
}

public function genRef()
{

}

public function makeCall($header, $payload, $method, $url)
{
     try{
          $curl = curl_init();
          
          curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => $header,
          ));
          
          $response = curl_exec($curl);
          
          curl_close($curl);
          // echo $response['email'];
          echo $response;
     }catch(\Exception $e){
          echo 'Exception Found'.$e->getMessage();
     }
}

return [
     'status' => false,
     'error' => $e->getMessage(),
     'message' => 'Something Went Wring'
]
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Document</title>
</head>
<body>
     <p style="color:red"><?php if($data != null) echo $data->status; ?></p>
     <p style="color:red"><?php if($data != null) echo $data->message; ?></p>
     <p style="color:red"><?php if($data != null) echo $data->token; ?></p>
     <p style="color:red"><?php if($data != null) echo $data->email; ?></p>

     <form action="test.php" method="POST">
          <input type="email" name="email" placeholder="email">
          <input type="password" name="password">
          <input type="submit" value="Submit/Login">

     </form>
</body>
</html>


<!-- // {"status":true,"message":"Registration Successful","token":"822|WzVUtfTVEgMSRtvlOZw5TcjL8YlJ75vKcLbVhpJe","user_id":43,"email":"owadayosamdam@gmail.com"}
// {"status":true,"message":"Login Successful","token":"823|Hx7TBhKcZTXaJ8Fj1YTmKiShPL5D7cSOfFWm2JAj","user_id":43,"email":"owadayosamdam@gmail.com"} -->