<?php

include '../../user/core/connection.php';
include '../../user/core/sessiontimeout.php';

function updateName() {
    // Your PHP code here

    global $conn;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = $_POST['action'];
        $data = $_POST['data'];
    
        if ($action === 'updateName') {
            if (isset($data['fname']) && isset($data['MiddleInitial']) && isset($data['lname'])) {
                $user_id = $_SESSION['user_id'];
    
                $fname = $data['fname'];
                $MI = $data['MiddleInitial'];
                $lname = $data['lname'];
    
                $sql = "UPDATE user SET First_Name = ?, Middle_Initial = ?, Last_Name = ? WHERE UserID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $fname, $MI, $lname, $user_id);
                $stmt->execute();
               
                if ($stmt->affected_rows === 0) {
                    echo 'Failed to make an update name.';
                } else {
                    echo 'Name successfully updated.';
                }
            } else {
                exit('All form fields are required.');
            }
        } else {
            echo  $_POST['fname'];
        }
    }
    
}

function updateEmail() {
    global $conn;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = $_POST['action'];
        $data = $_POST['data'];

        $email = $data['email'];
    
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

        if ($action === 'updateEmail') {
            if (isset($data['email'])) {
                $user_id = $_SESSION['user_id'];
                
                $sql = "UPDATE user SET Email = ? WHERE UserID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $email, $user_id);
                $stmt->execute();
               
                if ($stmt->affected_rows === 0) {
                    echo 'Failed to make an update Email.';
                } else {
                    echo 'Email successfully updated.';
                }
            } else {
                exit('All form fields are required.');
            }
        } else {
            echo  $_POST['fname'];
        }
    }

}

function updateAddress() {
    // global $conn;

    // if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //     $action = $_POST['action'];
    //     $data = $_POST['data'];

    //     $user_id = $_SESSION['user_id'];
    //     $searchAddress = "SELECT Address_ID FROM user WHERE UserID = ?";
    //     $stmt = $conn->prepare($searchAddress);
    //     $stmt->bind_param("i", $user_id);
    //     $stmt->execute();
    //     $result = $stmt->get_result();

    //     if($result->num_rows() > 0){

    //         if (isset($data['Country'] && $data['Province'] && $data['City'] && $data['Baranggay'] && $data['ZipCode'])) {
    //             $country = $data['Country'];
    //             $province = $data['Province'];
    //             $city = $data['City'];
    //             $barangay = $data['Baranggay'];
    //             $zipcode = $data['ZipCode'];

    //             $addressID = $result['Address_ID'];

    //             $sql = "UPDATE address SET Country = ?, Province = ?, City = ?, Baranggay = ? WHERE Address_ID = ?";
    //             $stmt = $conn->prepare($sql);
    //             $stmt->bind_param("ssssi", $country, $province, $city, $barangay, $addressID);
    //             $stmt->execute();
                
    //             if ($stmt->affected_rows === 0) {
    //                 echo 'Failed to make an update Email.';
    //             } else {
    //                 echo 'Email successfully updated.';
    //             }
    //         } else {
    //             exit('All form fields are required.');
    //         }

    //     } else {
    //         createAddress();
    //     }
    // }

    global $conn;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = $_POST['action'];
        $data = $_POST['data'];

        $user_id = $_SESSION['user_id'];
        $searchAddress = "SELECT Address_ID FROM user WHERE UserID = ?";
        $stmt = $conn->prepare($searchAddress);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $addressData = $result->fetch_assoc();

        if (!is_null($addressData['Address_ID'])) {
            if (isset($data['Country']) && isset($data['Province']) && isset($data['City']) && isset($data['Baranggay']) && isset($data['ZipCode'])) {
                $country = $data['Country'];
                $province = $data['Province'];
                $city = $data['City'];
                $barangay = $data['Baranggay'];
                $zipcode = $data['ZipCode'];

                $addressID = $addressData['Address_ID'];

                $sql = "UPDATE address SET Country = ?, Province = ?, City = ?, Baranggay = ?, Zip_Code = ? WHERE Address_ID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssi", $country, $province, $city, $barangay, $zipcode, $addressID);
                $stmt->execute();
                
                if ($stmt->affected_rows === 0) {
                    echo 'Failed to update address.';
                } else {
                    echo 'Address successfully updated.';
                }
            } else {
                exit('All form fields are required.');
            }

        } else {
            createAddress();
        }
    }

}

function createAddress(){
    global $conn;
    $user_id = $_SESSION['user_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = $_POST['action'];
        $data = $_POST['data'];

        $country = $data['Country'];
        $province = $data['Province'];
        $city = $data['City'];
        $barangay = $data['Baranggay'];
        $zipcode = $data['ZipCode'];

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
        $sql_user = "UPDATE user SET Address_ID = ? WHERE UserID = ?";
        $stmt_user = $conn->prepare($sql_user);
        $stmt_user->bind_param("ii", $address_id, $user_id);

        if (!$stmt_user->execute()) {
            echo "Error inserting user: " . $stmt_user->error;
            $delSql = "DELETE FROM address WHERE Address_ID = ?;";
            $stmtdel = $conn->prepare($delSql);
            $stmtdel->bind_param("i", $address_id);
            $stmtdel->execute();
            exit();
        }
    }
}



// Check if the AJAX request was made
if(isset($_POST['action']) && $_POST['action'] == 'updateName') {
    updateName();
} else if(isset($_POST['action']) && $_POST['action'] == 'updateEmail') {
    updateEmail();
} else if(isset($_POST['action']) && $_POST['action'] == 'updateAddress') {
    updateAddress();
}

?>
