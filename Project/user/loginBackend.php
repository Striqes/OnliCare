<?php
include 'core/connection.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM user WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if user exists
    if ($user) {
        // Verify password
        if (password_verify($password, $user['Password'])) {
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['UserType'] = $user['UserType'];

            $response = ['status' => 'success', 'redirect' => ''];

            // Check user type and set redirect accordingly
            if ($user['UserType'] == 'Admin') {
                $response['redirect'] = $adminIndex;
            } else if ($user['UserType'] == 'Doctor') {
                $response['redirect'] = $doctorIndex;
            } else if ($user['UserType'] == 'Patient') {
                $response['redirect'] = $indexPath;
            }

            echo json_encode($response);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'The password you entered was not valid.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'The email you entered does not exist.']);
    }
}

$conn->close();
?>
