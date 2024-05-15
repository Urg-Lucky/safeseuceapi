<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];

include_once '../database.php';

if ($requestMethod === 'GET') {
    $selectQuery = "SELECT * FROM Head"; // SQL query to select all data from the "Head" table

    // Prepare and execute the SQL query
    $stmt = $con->prepare($selectQuery);
    $stmt->execute();

    // Get result set
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
        // Return JSON response with status and data
        echo json_encode(array("status" => $status, "data" => $data));
    } else {
        // If no rows are found, return HTTP response code 404 and error message
        http_response_code(404);
        echo json_encode(array("status" => "404", "message" => "No data found"));
    }
} else {
    // If the request method is not GET, return HTTP response code 405 (Method Not Allowed)
    http_response_code(405);
    echo json_encode(array("status" => "405", "message" => "Method Not Allowed"));
}
