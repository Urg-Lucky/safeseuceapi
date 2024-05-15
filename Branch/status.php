<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$requestMethod = $_SERVER["REQUEST_METHOD"];
include_once '../database.php';
if ($requestMethod === "GET") {
    $id = $_GET['id'];

    if ($_GET['status'] == 'Active') {
        $status = 'Inactive';
    } else {
        $status = 'Active';
    }
    if (!empty($id) && !empty($status)) {
        $updateQuery = "UPDATE  Branch SET status='$status' WHERE id='$id'";
        $stmt = $con->query($updateQuery);
        if ($stmt === TRUE) {
            $statu = "success";
            http_response_code(200);
            echo json_encode(array("status" => $statu, "data" => $status));
        } else {
            http_response_code(201);
            echo json_encode(array("status" => "201", "message" => "failure"));
        }
    } else {
        http_response_code(500);
        echo json_encode(array("status" => "500", "message" => "unavailbale input field"));
    }
} else {
    http_response_code(501);
    echo json_encode(array("status" => "501", "message" => "Bad Request Method"));
}
