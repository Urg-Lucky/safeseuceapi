<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Origin, Authorization");
header("HTTP/1.1 200 OK");

include_once '../database.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod === "POST") {
    $data = json_decode(file_get_contents("php://input"));

    if (
        !empty($data->bid) && !empty($data->name) && !empty($data->address)
        && !empty($data->mobile) && !empty($data->login) && !empty($data->Pwd) && isset($_GET['id'])
    ) {
        $id = $_GET['id'];
        $bid = $data->bid;
        $name = $data->name;
        $address = $data->address;
        $mobile = $data->mobile;
        $login = $data->login;
        $Pwd = $data->Pwd;

        // Prepare SQL query with placeholders to avoid SQL injection
        $updateQuery = "UPDATE Branch SET bid=?, name=?, address=?, mobile=?, login=?, Pwd=? WHERE id=?";

        // Prepare and execute the statement
        $stmt = $con->prepare($updateQuery);
        $stmt->bind_param("ssssssi", $bid, $name, $address, $mobile, $login, $Pwd, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            http_response_code(200);
            echo json_encode(array("message" => "Data Updated Successfully"));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Failed to update data"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Missing or invalid input data or ID"));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method Not Allowed"));
}
