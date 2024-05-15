<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];

include_once '../database.php';

if ($requestMethod === 'GET') {
    // Extract "hname" parameter from the URL
    $hname = isset($_GET['hname']) ? $_GET['hname'] : '';

    // Check if hname is provided
    if (!empty($hname)) {
        // SQL query to select data based on hname
        $selectQuery = "SELECT sname FROM Head WHERE hname = ?";

        // Prepare and execute the SQL query
        $stmt = $con->prepare($selectQuery);
        $stmt->bind_param('s', $hname); // Bind parameter
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
            echo json_encode(array("status" => "404", "message" => "No data found for the provided hname"));
        }
    } else {
        // If hname parameter is not provided, return HTTP response code 400 (Bad Request)
        http_response_code(400);
        echo json_encode(array("status" => "400", "message" => "hname parameter is required"));
    }
} else {
    // If the request method is not GET, return HTTP response code 405 (Method Not Allowed)
    http_response_code(405);
    echo json_encode(array("status" => "405", "message" => "Method Not Allowed"));
}
