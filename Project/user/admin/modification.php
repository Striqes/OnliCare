<?php
include '../core/connection.php';
include '../core/sessiontimeout.php';

if($_SESSION['UserType'] != 'Admin'){
    header("Location: $indexPath");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="..\..\output.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="bg-gray-200 w-full min-h-screen flex items-center justify-center">
        <div class="w-full py-8">
            <div class="flex items-center justify-center space-x-2">
                <img src="..\..\..\assets\onlicarelogo.svg" class="h-8" alt="Logo">
                <h1 class="text-5xl font-bold text-yellow-500 tracking-wider">Onli</h1>
                <h1 class="text-5xl font-bold text-green-700 tracking-wider">Care</h1>
            </div>
            <div class="bg-white w-5/6 md:w-3/4 lg:w-2/3 xl:w-[500px] 2xl:w-[550px] mt-8 mx-auto px-16 py-8 rounded-lg shadow-2xl">

                <h2 class="text-center text-2xl font-bold tracking-wide text-gray-800">Modify a Doctor Department and Specialization</h2>

                <form id="ModifyForm" class="my-8 text-sm" method = "post">
                    <div class="flex flex-col my-4">
                        <label for="doctorID" class="text-gray-700">Enter Doctor's ID:</label>
                        <input type="text" name="doctorID" id="name" class="mt-2 p-2 border border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 rounded text-sm text-gray-900" placeholder="Enter Doctor ID to be Modified" required>
                    </div>

                    <div class="flex flex-col my-4">
                        <select name="department_id" id="department_id" class="mt-2 p-2 border border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 rounded text-sm text-gray-900" placeholder="Enter Doctor's email" required>>
                            <option class="font-semibold" selected>Select Department</option>
                                <?php
                                    $sql = "SELECT * FROM department";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo '<option value="'.$row['Department_ID'].'">'.$row['department_name'].'</option>';
                                        }
                                    } else {
                                        echo '<option>No departments found</option>';
                                    }

                                ?>
                        </select>
                    </div>

                    <div class="flex flex-col my-4">
                        <select name="specialization_id" id="specialization_id" class="mt-2 p-2 border border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 rounded text-sm text-gray-900" placeholder="Enter Doctor's email" required>>
                            <option class="font-semibold" selected>Select Specialization</option>
                                <?php
                                    $sql = "SELECT * FROM specialization";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo '<option value="'.$row['specialization_id'].'">'.$row['specialization_name'].'</option>';
                                        }
                                    } else {
                                        echo '<option>No Specialization found</option>';
                                    }

                                ?>
                        </select>
                    </div>
                
                    <div class="my-4 flex items-center justify-end space-x-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 rounded-lg px-8 py-2 text-gray-100 hover:shadow-xl transition duration-150 uppercase">Update Account</button>    
                    </div>
                </form>
            </div>
        </div>
    </div>

    

    <script>
        $(document).ready(function() {
            $('#ModifyForm').submit(function(event) {
                // Prevent default form submission
                event.preventDefault();

                // Serialize form data
                var formData = $(this).serialize();

                // Perform AJAX request
                $.ajax({
                    type: 'POST',
                    url: 'backend/ModifyDoctor.php',
                    data: formData,
                    success: function(response) {
                        // Handle successful response here
                        alert(response);

                        if(response == "Account has been Modified"){
                            $('#ModifyForm')[0].reset();
                        } 
                    },
                    error: function(xhr, status, error) {
                        // Handle errors here
                        console.error(xhr.responseText);
                        // You can display an error message or log the error
                    }
                });
            });
        });

    </script>

</body>
</html>