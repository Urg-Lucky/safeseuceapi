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
    try {
        $selectQuery = "SELECT * FROM Branch";
        $stmt = $con->query($selectQuery);

        if ($stmt !== false && $stmt->num_rows > 0) {
            $data = [];

            while ($row = $stmt->fetch_assoc()) {
                $data[] = $row;
            }

            $status = "success";
            http_response_code(200);
            echo json_encode(array("message" => $status, "data" => $data));
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Data Not Found"));
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("message" => "Internal Server Error: " . $e->getMessage()));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method Not Allowed"));
}
