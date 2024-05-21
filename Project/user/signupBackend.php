<?php
include 'core/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["name"];
    
    $middle_initial = $_POST["middleName"];
    $last_name = $_POST["LastName"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $password_confirmation = $_POST["password_confirmation"];
    $user_type = "Patient"; 
    $country = $_POST["country"];
    $province = $_POST["province"];
    $city = $_POST["city"];
    $barangay = $_POST["barangay"];
    $zipcode = $_POST["zipcode"];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $sql = "SELECT * FROM user WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "Email already exists.";
        exit();
    }
    $stmt->close();

    // Prepare and execute address insertion
    $sql_address = "INSERT INTO address (Country, Province, City, Baranggay, Zip_Code) VALUES (?, ?, ?, ?, ?)";
    $stmt_address = $conn->prepare($sql_address);
    $stmt_address->bind_param("sssss", $country, $province, $city, $barangay, $zipcode);

    // Execute address insertion
    if (!$stmt_address->execute()) {
        echo "Error inserting address: " . $stmt_address->error;
        exit();
    }

    // Get the ID of the inserted address
    $address_id = $stmt_address->insert_id;

    // Prepare and execute user insertion
    $sql_user = "INSERT INTO user (First_Name, Middle_Initial, Last_Name, Email, Address_ID, UserType, Password) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("ssssiss", $first_name, $middle_initial, $last_name, $email, $address_id, $user_type, $hashed_password);

    // Execute user insertion
    if (!$stmt_user->execute()) {
        echo "Error inserting user: " . $stmt_user->error;
        exit();
    }

    // Insert patient
    $user_id = $stmt_user->insert_id; // Get the ID of the inserted user

    $sql_patient = "INSERT INTO patient (User_ID) VALUES (?)";
    $stmt_patient = $conn->prepare($sql_patient);
    $stmt_patient->bind_param("i", $user_id); // Bind the user ID for the patient

    // Execute patient insertion
    if ($stmt_patient->execute()) {
        echo "Account Successfully Created";
        exit();
    } else {
        echo "Error inserting Patient: " . $stmt_patient->error;
        exit();
    }

    $stmt_address->close();
    $stmt_user->close();
    $stmt_patient->close();

}

$conn->close();
?>
