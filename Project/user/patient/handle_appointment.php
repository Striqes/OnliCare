 <?php
include '../core/connection.php';
include '../core/sessiontimeout.php';

    if (isset($_POST['select_doctor']) && isset($_POST['date']) && isset($_POST['textarea'])) {
        
        $user_id = $_SESSION['user_id'];
        $doctor_id = $_POST['select_doctor'];
        $date = $_POST['date'];
        $message = $_POST['textarea'];

        $currentDate = date('Y-m-d');
        $currentTime = date('H:i:s');
        $noonTime = "12:00:00";

        if ($date < $currentDate) {
            echo '<script>alert("Cannot book an appointment in the past."); window.location.href = "appointment.php";</script>';
            exit();
        }

        // Check if the date is today and the current time is past 12 PM
        if ($date == $currentDate && $currentTime > $noonTime) {
            echo '<script>alert("Cannot book an appointment for today please select another day."); window.location.href = "appointment.php"; </script>';
            exit();
        }

        // Check if the date is more than a month in the future
        $oneMonthFromNow = date('Y-m-d', strtotime('+1 month'));
        if ($date > $oneMonthFromNow) {
            echo '<script>alert("Cannot book an appointment more than a month in advance."); window.location.href = "appointment.php"; </script>';
            exit();
        }


        if (empty($doctor_id) || empty($date) || empty($message)) {
            echo '<script>alert("All form fields are required."); window.history.back();</script>';
        }

        // Get the Patient_ID using the User_ID
        $sql_get_patient_id = "SELECT Patient_ID FROM patient WHERE User_ID = ?";
        $stmt_get_patient_id = $conn->prepare($sql_get_patient_id);
        $stmt_get_patient_id->bind_param("i", $user_id);
        $stmt_get_patient_id->execute();
        $result_get_patient_id = $stmt_get_patient_id->get_result();
        if ($result_get_patient_id->num_rows === 0) {
            exit('User is not a patient.');
        }                                                                           
        $row = $result_get_patient_id->fetch_assoc();
        $patient_id = $row['Patient_ID'];
        $stmt_get_patient_id->close();
    
        $sql = "INSERT INTO appointment (Doctor_ID, Patient_ID, date, Message) VALUES (?, ?, ?,? )";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $doctor_id, $patient_id, $date, $message);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            exit('Failed to make an appointment.');
        } else {
            $_SESSION['message'] = "Appointment successfully made. Please Wait for approval of Doctor.";
            header("Location: appointment.php"); 
            exit();
        }
        
}









?>