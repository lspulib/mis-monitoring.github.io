<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $a = mysqli_real_escape_string($conn, $_POST['item']);
    $b = mysqli_real_escape_string($conn, $_POST['devicename']);
    $c = mysqli_real_escape_string($conn, $_POST['serial']);
    $d = mysqli_real_escape_string($conn, $_POST['plant']);
    $e = mysqli_real_escape_string($conn, $_POST['area']);
    $f = mysqli_real_escape_string($conn, $_POST['pullout']);
    $g = mysqli_real_escape_string($conn, $_POST['shift']);
    $h = mysqli_real_escape_string($conn, $_POST['remarks']);
    $i = mysqli_real_escape_string($conn, $_POST['mis_name']);
    $j = mysqli_real_escape_string($conn, $_POST['curr_status']);

    $check_sql = "SELECT * FROM replacements WHERE item='$a' AND device_name='$b' AND serial_number='$c' AND plant='$d' AND area='$e' AND pull_out_date='$f' AND shift='$g' AND remarks='$h' AND mis_name='$i'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {

        $response = array("status" => "exists");
        echo json_encode($response);
    } else {

        $sql = "INSERT INTO `replacements` (`item`, `device_name`, `serial_number`, `plant`, `area`, `pull_out_date`, `shift`, `remarks`, `mis_name`, `curr_status`) VALUES ('$a', '$b', '$c', '$d', '$e', '$f', '$g', '$h', '$i', '$j')";
        $conn->query($sql);

        $response = array("status" => "success", "table" => "");

        $table_html = "<table id='dataTable' class='table nowrap' style='width:100%'>";

        $sql = "SELECT id, item, device_name, serial_number, plant, area, pull_out_date, shift, remarks, mis_name, curr_status, date_replaced FROM replacements";
        $result = $conn->query($sql);

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
                                    <td><button id='replacedButton_" . $row["id"] . "' class='" . ($row["curr_status"] == 0 ? "bg-red" : "bg-green") . "' onclick='updateStatus(" . $row["id"] . ")'>" . ($row["curr_status"] == 0 ? "Not Replaced" : "Replaced on " . $row['date_replaced']) . "</button></td>
                                </tr>";
            }
            $table_html .= "</tbody>";
        } else {
            $table_html .= "<tr><td colspan='11'>No results</td></tr>";
        }
        $table_html .= "</table>";

        $response['table'] = $table_html;
        

        echo json_encode($response);
    }
}
$conn->close();
?>
