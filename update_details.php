<?php
include 'connection.php';


$stmt = $conn->prepare("UPDATE replacements SET item=?, device_name=?, serial_number=?, plant=?, area=?, pull_out_date=?, shift=?, remarks=?, mis_name=?, curr_status=? WHERE id=?");
$stmt->bind_param("sssssssssii", $item, $device_name, $serial_number, $plant, $area, $pull_out_date, $shift, $remarks, $mis_name, $curr_status, $id);


$item = $_POST["item"];
$device_name = $_POST["devicename"];
$serial_number = $_POST["serial"];
$plant = $_POST["plant"];
$area = $_POST["area"];
$pull_out_date = $_POST["pullout"];
$shift = $_POST["shift"];
$remarks = $_POST["remarks"];
$mis_name = $_POST["mis_name"];
$curr_status = $_POST["curr_status"]; 
$id = $_POST["id"];


// Execute the update query
if ($stmt->execute()) {
    // If the update is successful, construct the response
    $response = array("success" => true);

    // Construct the table HTML
    $table_html = "<table id='dataTable' class='table nowrap' style='width:100%'>";

    // Query data for the table
    $sql = "SELECT id, item, device_name, serial_number, plant, area, pull_out_date, shift, remarks, mis_name, curr_status, date_replaced FROM replacements";
    $result = $conn->query($sql);

    // If there are results, construct the table rows
    if ($result->num_rows > 0) {
        $table_html .= "<tbody>";
            while ($row = $result->fetch_assoc()) {
            $table_html .= "<tr>
                                <td>" . $row["id"] . "</td>
                                <td>" . $row["item"] . "</td>
                                <td>" . $row["device_name"] . "</td>
                                <td>" . $row["serial_number"] . "</td>
                                <td>" . $row["plant"] . "</td>
                                <td>" . $row["area"] . "</td>
                                <td>" . $row["pull_out_date"] . "</td>
                                <td>" . $row["shift"] . "</td>
                                <td>" . $row["remarks"] . "</td>
                                <td>" . $row["mis_name"] . "</td>
                                <td>
                                <button id='replacedButton_" . $row["id"] . "' class='" . 
                                    ($row["curr_status"] == 0 ? "bg-red" : ($row["curr_status"] == 1 ? "bg-green" : "bg-blue")) . "' 
                                    onclick=\"openStatusModal(" . $row["id"] . ", " . $row["curr_status"] . ")\">" . 
                                    ($row["curr_status"] == 0 ? "Not Replaced" : ($row["curr_status"] == 1 ? "Replaced on " . $row['date_replaced'] : "Repaired on ". $row['date_replaced'])) . 
                                "</button>
                                </td>
                            </tr>";
        }
        $table_html .= "</tbody>";
    } else {
        $table_html .= "<tr><td colspan='11'>No results</td></tr>";
    }
    $table_html .= "</table>";

    // Add the constructed table HTML to the response
    $response['table'] = $table_html;

    // Encode the response as JSON and echo it
    echo json_encode($response);
} else {
    // If the update fails, construct an error response
    $response = array("success" => false, "error" => "Failed to update details: " . $conn->error);
    echo json_encode($response); // Return the error response as JSON
}


$stmt->close();
$conn->close();
?>
