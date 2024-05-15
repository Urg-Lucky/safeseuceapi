<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");
header("Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Origin, Authorization");

include_once '../database.php';

$response = array();
$targetDir = "../public_dir/CandidateImage/";

$id = $_GET['id'] ?? null; // Get id from URL

$name = $_POST['name'] ?? '';
$address = $_POST['address'] ?? '';
$mobile = $_POST['mobile'] ?? '';
$ad_num = $_POST['ad_num'] ?? '';
$pan_num = $_POST['pan_num'] ?? '';
$login = $_POST['login'] ?? '';
$Pwd = $_POST['Pwd'] ?? '';
$bid = $_POST['bid'] ?? '';
$hname = $_POST['hname'] ?? '';
$sname = $_POST['sname'] ?? '';
$Ac_num = $_POST['Ac_num'] ?? '';
$status = 'Active';

// Validate and move uploaded files
$c_img = $_FILES['c_img'] ?? null;
$ad_pdf = $_FILES['ad_pdf'] ?? null;
$pan_pdf = $_FILES['pan_pdf'] ?? null;

if ($id && $c_img && $ad_pdf && $pan_pdf) {
    $c_img_name = basename($c_img['name']);
    $ad_pdf_name = basename($ad_pdf['name']);
    $pan_pdf_name = basename($pan_pdf['name']);

    // Adjust target file paths
    $targetFile1 = $targetDir . $c_img_name;
    $targetFile2 = $targetDir . $ad_pdf_name;
    $targetFile3 = $targetDir . $pan_pdf_name;

    // Move uploaded files
    if (
        move_uploaded_file($c_img['tmp_name'], $targetFile1) &&
        move_uploaded_file($ad_pdf['tmp_name'], $targetFile2) &&
        move_uploaded_file($pan_pdf['tmp_name'], $targetFile3)
    ) {

        // Prepare and execute database query
        $updateQuery = $con->prepare("UPDATE Candidate SET name=?, address=?, mobile=?, ad_num=?, pan_num=?, login=?, c_img=?, ad_pdf=?, pan_pdf=?, status=?, Pwd=?, bid=?, hname=?, sname=?, Ac_num=? WHERE id=?");

        $updateQuery->bind_param("sssssssssssssssi", $name, $address, $mobile, $ad_num, $pan_num, $login, $targetFile1, $targetFile2, $targetFile3, $status, $Pwd, $bid, $hname, $sname, $Ac_num, $id);

        if ($updateQuery->execute()) {
            $response = array(
                "status" => "success",
                "message" => "Data Update & Files uploaded successfully",
            );
            http_response_code(201);
        } else {
            $response = array(
                "status" => "failure",
                "message" => "Error Update data into Candidate table: " . $updateQuery->error,
            );
            http_response_code(500);
        }

        $updateQuery->close();
    } else {
        $response = array(
            "status" => "failure",
            "message" => "Failed to move uploaded files to destination.",
        );
        http_response_code(500);
    }
} else {
    $response = array(
        "status" => "failure",
        "message" => "Required files not uploaded or id missing in URL.",
    );
    http_response_code(400);
}

echo json_encode($response);
