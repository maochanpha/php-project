<?php
include 'db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data safely
    $EmpID      = $_POST['EmpID'];
    $FirstName  = $_POST['FirstName'];
    $LastName   = $_POST['LastName'];
    $Gender     = $_POST['Gender'];
    $DOB        = $_POST['DOB'];
    $PstID      = $_POST['PstID'];

    // Prepare update query
    $sql = "UPDATE employee 
            SET FirstName=?, LastName=?, Gender=?, DOB=?, PstID=? 
            WHERE EmpID=?";

    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $FirstName, $LastName, $Gender, $DOB, $PstID, $EmpID);

    // Execute
    if ($stmt->execute()) {
        echo "<script>
                alert('✅ Employee updated successfully!');
                window.location.href='employees.php';
              </script>";
    } else {
        echo "<script>
                alert('❌ Error updating employee: " . $stmt->error . "');
                window.history.back();
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
