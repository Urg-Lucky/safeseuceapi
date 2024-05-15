<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,GET,OPTIONS,PUT,DELETE");
header("Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Origin, Authorization");
header("HTTP/1.1 200 OK");

include_once '../database.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod === "POST") {
    $data = json_decode(file_get_contents("php://input"));

    // Validate and sanitize input data
    if (
        !empty($data->name) && !empty($data->mobile) && !empty($data->address) && !empty($data->bid)
        && !empty($data->login) && !empty($data->Pwd)
    ) {
        $name = mysqli_real_escape_string($con, htmlspecialchars($data->name));
        $mobile = mysqli_real_escape_string($con, htmlspecialchars($data->mobile));
        $address = mysqli_real_escape_string($con, htmlspecialchars($data->address));
        $bid = mysqli_real_escape_string($con, htmlspecialchars($data->bid));
        $login = mysqli_real_escape_string($con, htmlspecialchars($data->login));
        $Pwd = mysqli_real_escape_string($con, htmlspecialchars($data->Pwd));
        $status = "Active";

        // Use prepared statement to insert data into database
        $insertQuery = "INSERT INTO Branch(name, mobile, address, bid, login, Pwd, status)
                        VALUES(?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($insertQuery);
        $stmt->bind_param("sssssss", $name, $mobile, $address, $bid, $login, $Pwd, $status);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            http_response_code(200);
            echo json_encode(array("message" => "Data inserted successfully"));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Failed to insert data"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Missing or invalid input fields"));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method Not Allowed"));
}
