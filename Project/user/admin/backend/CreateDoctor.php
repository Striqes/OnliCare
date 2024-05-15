<?php

include '../../core/connection.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $first_name = $_POST["name"];
    $middle_initial = $_POST["middleName"];
    $last_name = $_POST["LastName"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $password_confirmation = $_POST["password_confirmation"];
    $user_type = "Doctor"; 

    $department_id = $_POST["department_id"];
    $specialization_id = $_POST["specialization_id"];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $sql_check_email = "SELECT * FROM user WHERE Email = ?";
    $stmt_check_email = $conn->prepare($sql_check_email);
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $result_check_email = $stmt_check_email->get_result();
    if ($result_check_email->num_rows > 0) {
        echo "Email already exists.";
        exit();
    }
    $stmt_check_email->close();

    // Prepare and execute user insertion
    $sql_insert_user = "INSERT INTO user (First_Name, Middle_Initial, Last_Name, Email, UserType, Password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_insert_user = $conn->prepare($sql_insert_user);
    $stmt_insert_user->bind_param("ssssss", $first_name, $middle_initial, $last_name, $email, $user_type, $hashed_password);

    // Execute user insertion
    if (!$stmt_insert_user->execute()) {
        echo "Error inserting user: " . $stmt_insert_user->error;
        exit();
    }

    // Get the ID of the inserted user
    $user_id = $stmt_insert_user->insert_id;

    // Prepare and execute doctor insertion
    $sql_insert_doctor = "INSERT INTO doctor (User_ID, Department_ID, Specialization_ID) VALUES (?, ?, ?)";
    $stmt_insert_doctor = $conn->prepare($sql_insert_doctor);
    $stmt_insert_doctor->bind_param("iii", $user_id, $department_id, $specialization_id);

    // Execute doctor insertion
    if ($stmt_insert_doctor->execute()) {
        echo 'Account has been Created';
    } else {
        echo "Error inserting doctor: " . $stmt_insert_doctor->error;
        exit();
    }

    // Close prepared statements
    $stmt_insert_user->close();
    $stmt_insert_doctor->close();
}

?>
