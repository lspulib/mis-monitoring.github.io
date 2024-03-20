<?php
include "connection.php";
if (isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];
    if (isset($_POST['status'])) {
        $status = $_POST['status'];

        $updateQuery = "";

        if ($status == "replaced") {
            
            $updateQuery = "UPDATE replacements SET curr_status = 1, date_replaced = NOW() WHERE id = $id";
        } elseif ($status == "repaired") {
            
            $updateQuery = "UPDATE replacements SET curr_status = 2, date_replaced = NOW() WHERE id = $id";
        }

        if ($conn->query($updateQuery) === TRUE) {

            echo json_encode(['success' => true, 'curr_status' => ($status == "replaced" ? 1 : 2), 'date_updated' => date("Y-m-d")]);
        } else {
            echo json_encode(['success' => false, 'error' => "Error updating status: " . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => "Status parameter is missing or empty"]);
    }
} else {
    echo json_encode(['success' => false, 'error' => "Invalid request"]);
}
$conn->close();
?>
