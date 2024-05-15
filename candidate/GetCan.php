<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];

include_once '../database.php';

if ($requestMethod === 'GET') {
    // Check if 'bid' parameter is provided in the request
    if (isset($_GET['bid'])) {
        // Sanitize and retrieve the 'bid' parameter from the request
        $bid = htmlspecialchars($_GET['bid']);

        // Prepare and execute the SELECT query
        $sql = "SELECT * FROM Candidate WHERE bid = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $bid);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if there are rows in the result set
        if ($result->num_rows > 0) {
            $data = array();
            // Fetch rows and store them in the data array
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            // Set success status and HTTP response code 200
            $status = 200;
            http_response_code(200);
            echo json_encode(array("status" => $status, "data" => $data));
        } else {
            // If no data found for the provided bid, return HTTP response code 404
            http_response_code(404);
            echo json_encode(array("status" => "404", "message" => "No data found for the provided bid"));
        }
    } else {
        // If 'bid' parameter is not provided, return HTTP response code 400 (Bad Request)
        http_response_code(400);
        echo json_encode(array("status" => "400", "message" => "bid parameter is required"));
    }
} else {
    // If the request method is not GET, return HTTP response code 405 (Method Not Allowed)
    http_response_code(405);
    echo json_encode(array("status" => "405", "message" => "Method Not Allowed"));
}
