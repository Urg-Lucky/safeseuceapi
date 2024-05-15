<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$requestMethod = $_SERVER["REQUEST_METHOD"];

include_once 'database.php';

if ($requestMethod === 'GET') {
    if (isset($_GET['username']) && isset($_GET['password'])) {
        $username = $_GET['username'];
        $password = $_GET['password'];

        // Using prepared statement to prevent SQL injection
        $selectQuery = "SELECT * FROM Login WHERE Login=? AND Pwd=?";
        $stmt = $con->prepare($selectQuery);
        if ($stmt === false) {
            // Show error if preparation fails
            die(json_encode(array("status" => "500", "message" => "Database error: " . $con->error)));
        }
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = array();

        if ($result === false) {
            // Show error if execution fails
            die(json_encode(array("status" => "500", "message" => "Database error: " . $stmt->error)));
        }

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $status = 200;
            http_response_code(200);
            echo json_encode(array("status" => $status, "data" => $data));
        } else {
            http_response_code(201);
            echo json_encode(array("status" => "201", "message" => "invalid credentials"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("status" => "400", "message" => "missing username or password"));
    }
} else {
    http_response_code(405);
    echo json_encode(array("status" => "405", "message" => "Method Not Allowed"));
}
