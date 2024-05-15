<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json");

include_once '../database.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === "GET") {

    if (!empty($_GET['bid'])) {

        $bid = $_GET['bid'];

        $selectQuery =  "SELECT bid FROM Branch WHERE bid='$bid'";    // check  table bid number match it

        $stmt = $con->query($selectQuery);

        if (mysqli_num_rows($stmt) > 0) {

            http_response_code(200);
            echo json_encode(array("message" => 400, "data" => "Data Found Sucessfully"));
        } else {
            http_response_code(201);
            echo json_encode(array("message" => 201, "data" => "Data Not Found"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("data" => "unavailable input field"));
    }
} else {
    http_response_code(401);
    echo json_encode(array("message" => "Bad Request Method"));
}
