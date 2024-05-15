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
        $queryParams = $_GET;

        // Check if ID is provided in the URL
        if (isset($queryParams['id'])) {
            $id = $queryParams['id'];
            $selectQuery = "SELECT * FROM Candidate WHERE id = ?";
            $stmt = $con->prepare($selectQuery);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                $status = "success";
                http_response_code(200);
                echo json_encode(array("message" => $status, "data" => $data));
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Data Not Found"));
            }
        } else {
            $selectQuery = "SELECT * FROM Candidate";
            $result = $con->query($selectQuery);

            if ($result !== false) {
                if ($result->num_rows > 0) {
                    $data = [];
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                    $status = "success";
                    http_response_code(200);
                    echo json_encode(array("message" => $status, "data" => $data));
                } else {
                    http_response_code(404);
                    echo json_encode(array("message" => "Data Not Found"));
                }
            } else {
                http_response_code(500);
                echo json_encode(array("message" => "Internal Server Error"));
            }
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("message" => "Internal Server Error: " . $e->getMessage()));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method Not Allowed"));
}
