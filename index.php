<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.0/css/responsive.bootstrap5.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
        <div id="alert" class="alert alert-primary editAlert" role="alert">
        This is a sample alert.
        </div>
    
        <header class="main-header p-3 text-bg-dark">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                    <img class="logo" src="logo.png" alt="shin-etsu-logo">
                    <p class="title text-white">For Replacement Items</p>
                
                </div>

                
            </div>
        </header>
        <!--modal -->
        <div id="statusModal" class="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Select Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label>
                            <input  type="radio" name="status" value="replaced"> Replaced
                        </label>
                        <label>
                            <input class="stat" type="radio" name="status" value="repaired"> Repaired<br>  
                        </label>
                        <button type="button" class="confirm btn btn-primary" onclick="updateStatus()">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
        <!--modal for status -->
        <div class="side-table">
            <div class="form-container" id="form-container">
                <div class="form-toggle" onclick="toggleFormContainer()">
                    <img src="arrow.png" class="arrow">
                    <button class="form-button"></button>
                </div>
                <form action="submit.php" method="post" onsubmit="return validateForm()" class="form" id="reloadable-form">
                    <label class="item">Item:</label>
                    <input class="dataIn" type="text" name="item" required>
                    Device Name: <br>
                    <input class="dataIn" type="text" name="devicename" required>
                    Serial Number: <br>
                    <input class="dataIn" type="text" name="serial" onkeyup="this.value=this.value.replace(/[^a-zA-Z0-9\-/]/g, '')">
                    Plant: <br>
                    <input class="dataIn" type="text" name="plant" required>  
                    Area: <br>
                    <input class="dataIn" type="text" name="area" required>
                    Pull-out date: <br>
                    <input class="dataIn" type="date" name="pullout" required>

                    <label>Shift:</label>

                        <select name="shift" class="dataIn" required>
                            <option value="" disabled selected>Shift (e.g. E,F,G)</option>
                            <option value="E SHIFT">E</option>
                            <option value="F SHIFT">F</option>
                            <option value="G SHIFT">G</option>
                        </select>
                    
                    Remarks: <br>
                    <input class="dataIn" type="text" name="remarks">
                    MIS Staff in Charge: <br>
                    <input class="dataIn" type="text" name="mis_name" onkeyup="this.value=this.value.replace(/[^a-zA-ZÑñ]/g, '')" required>
                   
                    <input class = "dataIn" type="hidden" name ="curr_status"><!--need -->
                    <input class="dataIn" type="hidden" name="id" > <!--need -->
                    
                    <input class="submit bt" type="submit" value="Submit">
                    
                </form>
                <div>
                <button class="edit bt" id="editBt" onclick="saveEditedDetails(id); showAlert()">Edit</button><!--need -->
                <button class="refresh bt"  onclick="refreshForm()">Clear All</button><!--need -->

                </div>  
                    
                    
            </div>
            

            <div class="main-content">
                <table id="dataTable" class="table nowrap table-hover" style="width:100%">

                <thead >
                    <tr>
                        <th style="color: white">ID</th>
                        <th style="color: white">Item</th>
                        <th style="color: white">Device Name</th>
                        <th style="color: white">Serial #</th>
                        <th style="color: white">Plant</th>
                        <th style="color: white">Area</th>
                        <th style="color: white">P. O. Date</th>
                        <th style="color: white">Shift</th>
                        <th style="color: white">Remarks</th>
                        <th style="color: white">Staff</th>
                        <th style="color: white">Status</th>
                    </tr>
                </thead>
                <?php 
include "connection.php";

$sql = "SELECT id, item, device_name, serial_number, plant, area, pull_out_date, shift, remarks, mis_name, curr_status, date_replaced FROM replacements";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<tbody class="dataTableBody">';
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
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
    echo "</tbody>";
} else {
    echo "no results";
}
?>
                </table>
            </div>
        </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/4.0.1/js/dataTables.fixedHeader.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/4.0.1/js/fixedHeader.bootstrap5.js"></script> 
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.0/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.0/js/responsive.bootstrap5.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>



    <script>

        // Get the button and alert element
     
        
 




        function refreshForm() {
            var form = document.getElementById('reloadable-form');
         
            location.reload();
        }

        function loadFormContainerState() {
            const formContainer = document.getElementById('form-container');
            const isOpen = localStorage.getItem('formContainerOpen') === 'true';

            if (isOpen) {
                formContainer.classList.add('open');
                document.querySelector('.main-content').classList.add('shifted');
                document.querySelector('.arrow').classList.add('flipped');
            }
        }

            // Call loadFormContainerState() when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadFormContainerState();
        });

        function toggleFormContainer() {
            const formContainer = document.getElementById('form-container');
            const mainContent = document.querySelector('.main-content');
            const arrow = document.querySelector('.arrow');
            const isOpen = formContainer.classList.toggle('open');

            // Store the state in localStorage
            localStorage.setItem('formContainerOpen', isOpen);

            mainContent.classList.toggle('shifted');
            arrow.classList.toggle('flipped');
        }
// new repair/replacement
        function openStatusModal(id) {
            $('#statusModal').modal('show');
            $('#statusModal').data('id', id);
        }
        
        function updateStatus() {
            var status = $("input[name='status']:checked").val();
            var id = $('#statusModal').data('id');

            $.ajax({
                type: "POST",
                url: "update_stat.php",
                data: { id: id, status: status },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        var button = $("#replacedButton_" + id);
                        button.removeClass('bg-red').addClass('bg-green');
                        button.text(status.charAt(0).toUpperCase() + status.slice(1) + ' on ' + response.date_replaced);
                        $('#statusModal').modal('hide');
                        alert("Status Updated Successfully "+ id);
                    } else {
                        alert("Error: " + response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
            window.location.reload();
        }
//------------------------------------------------------------------------------------------------
        function validateForm() {
            var item = document.forms["form"]["item"].value;
            var deviceName = document.forms["form"]["devicename"].value;
            var plant = document.forms["form"]["plant"].value;
            var area = document.forms["form"]["area"].value;
            var pullOutDate = document.forms["form"]["pullout"].value;
            var shift = document.forms["form"]["shift"].value;
            var misName = document.forms["form"]["mis_name"].value;

            if (item == "" || deviceName == "" || plant == "" || area == "" || pullOutDate == "" || shift == "" || misName == "") {
                alert("Please fill in all required fields");
                return false;
            }
            location.reload();
        }

        function countUnreplacedEntries() {
            var checkbox = document.querySelector('input[type="checkbox"]');
            if (checkbox.checked) {
                intervalId = setInterval(sendEmail, 5000); 
            } else {
                clearInterval(intervalId); 
            }
        }

        // Function to send email
        function sendEmail() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "count_unreplaced.php?notify=on", true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText);
                    var response = JSON.parse(xhr.responseText);
                    alert("Number of entries without replacement: " + response.count);
                }
            }
            xhr.send();
        }

        // Function to save checkbox states to Local Storage
        function saveCheckboxStates() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach((checkbox) => {
                localStorage.setItem(checkbox.id, checkbox.defaultValue);
            });
        }

          // Function to populate text fields with row details and enable editing
          function editRow(row) {
            var cells = row.cells;
            document.getElementsByName("item")[0].value = cells[1].textContent;
            document.getElementsByName("devicename")[0].value = cells[2].textContent;
            document.getElementsByName("serial")[0].value = cells[3].textContent;
            document.getElementsByName("plant")[0].value = cells[4].textContent;
            document.getElementsByName("area")[0].value = cells[5].textContent;
            document.getElementsByName("pullout")[0].value = cells[6].textContent;
            document.getElementsByName("shift")[0].value = cells[7].textContent;
            document.getElementsByName("remarks")[0].value = cells[8].textContent;
            document.getElementsByName("mis_name")[0].value = cells[9].textContent;
            document.getElementsByName("id")[0].value = cells[0].textContent;

            document.getElementsByName("curr_status")[0].value = 0;

            var submitButton = document.getElementsByClassName("submit")[0];
            submitButton.setAttribute('disabled', 'disabled');
            submitButton.style.display = "none"; 
            

            var editButton = document.getElementsByClassName("edit bt")[0];
            editButton.removeAttribute('disabled'); 
            editButton.style.display = "block"; // Show the edit button

            editButton.onclick = function() { saveEditedDetails(cells[0].textContent); };
        }

            // Function to bind event listeners to rows for editing
    function attachEditRowListeners() {
        var rows = document.querySelectorAll("#dataTable tbody tr");
        rows.forEach(function (row) {
            row.addEventListener("click", function () {
                editRow(row);
            });
        });
    }

    // Call the function to bind event listeners initially
    document.addEventListener('DOMContentLoaded', function () {
        attachEditRowListeners();
    });

    function showAlert() {
            const alertElement = document.getElementById('alert');

        // Add click event listener to the button
            // Show the alert
            alertElement.style.display = 'block';

            // Set timeout to hide the alert after 2 seconds
            setTimeout(function () {
            alertElement.style.opacity = '0';
            setTimeout(function () {
                alertElement.style.display = 'none';
                alertElement.style.opacity = '1'; // Reset opacity for future use
            }, 1000); // 1 second for fade out transition
            }, 2000); // 2 seconds delay before hiding the alert
        }

    // Function to save edited details and update status
    function saveEditedDetails(id) {
        var formData = new FormData(document.getElementsByClassName("form")[0]);
        formData.append("id", id);

        $.ajax({
            type: "POST",
            url: "update_details.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                var jsonResponse = JSON.parse(response);
                if (jsonResponse.success) {
                    $('.dataTableBody').html(jsonResponse.table);
                    attachEditRowListeners();
                    
                } else {
                    alert("Error updating details: " + jsonResponse.error);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error updating details:", error);
            }        
        });
    }


        // Attach event listeners to rows for editing
        document.addEventListener('DOMContentLoaded', function () {
            var rows = document.querySelectorAll("#dataTable tbody tr");
            rows.forEach(function (row) {
                row.addEventListener("click", function () {
                    editRow(row);
                });
            });
        });



        function clearFormInputs() {
            var inputs = document.querySelectorAll('.form input[type="text"], .form input[type="date"], .form select');
            inputs.forEach(function(input) {
                input.value = ''; // Clear input value
            });
        }

    </script>

    <script>
        

        $(document).ready(function(){
            $('.form').submit(function(event){
                event.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: 'submit.php',
                    data: formData,
                    success: function(response){
                        var jsonResponse = JSON.parse(response);
                        if (jsonResponse.status === 'exists') {
                            if (confirm('Similar entry already exists. Do you want to proceed?')) {
                                window.location.href = 'index.php';
                            } else {
                                window.location.href = 'index.php';
                            }
                        } else if (jsonResponse.status === 'success') {
                            $('.dataTableBody').html(jsonResponse.table);
                        }
                    },
                    error: function(xhr, status, error){
                        console.log(xhr.responseText);
                    }
                });
            });
        });

        $(document).ready(function() {
            var table = $('#dataTable').DataTable({
                fixedHeader: true,
                responsive: true,
                stateSave: true 
            });

            // Event listener for table row click
            $('#dataTable tbody').on('click', 'tr', function() {
                // Remove highlight class from previously highlighted rows
                $('#dataTable tbody tr').removeClass('table-active');

                // Add highlight class to the clicked row
                $(this).addClass('table-active');

                // Get the data of the clicked row
                var rowData = table.row(this).data();
                
                // Populate form fields with row data
                $('input[name="item"]').val(rowData[1]);
                $('input[name="devicename"]').val(rowData[2]);
                $('input[name="serial"]').val(rowData[3]);
                $('input[name="plant"]').val(rowData[4]);
                $('input[name="area"]').val(rowData[5]);
                $('input[name="pullout"]').val(rowData[6]);
                $('input[name="shift"]').val(rowData[7]);
                $('input[name="remarks"]').val(rowData[8]);
                $('input[name="mis_name"]').val(rowData[9]);
            });
        });

    </script>
   
</body>
</html>