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

                <h2 class="text-center text-2xl font-bold tracking-wide text-gray-800">Create a Doctor's Account</h2>

                <form id="doctorForm" class="my-8 text-sm" method = "post">
                    <div class="flex flex-col my-4">
                        <label for="name" class="text-gray-700">First Name</label>
                        <input type="text" name="name" id="name" class="mt-2 p-2 border border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 rounded text-sm text-gray-900" placeholder="Enter Doctor's name" required>
                    </div>
                    <div class="flex flex-col my-4">
                        <label for="middleName" class="text-gray-700">Middle Initial</label>
                        <input type="text" name="middleName" id="middleName" maxlength="1" class="mt-2 p-2 border border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 rounded text-sm text-gray-900" placeholder="Enter Doctor's Middle Initial" required>
                    </div>
                    <div class="flex flex-col my-4">
                        <label for="LastName" class="text-gray-700">LastName</label>
                        <input type="text" name="LastName" id="LastName" class="mt-2 p-2 border border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 rounded text-sm text-gray-900" placeholder="Enter Doctor's Last Name" required>


                    <div class="flex flex-col my-4">
                        <label for="email" class="text-gray-700">Email Address</label>
                        <input type="email" name="email" id="email" class="mt-2 p-2 border border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 rounded text-sm text-gray-900" placeholder="Enter Doctor's email" required>
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

                    <div class="flex flex-col my-4">
                        <label for="password" class="text-gray-700">Password</label>
                        <div x-data="{ show: false }" class="relative flex items-center mt-2">
                            <input :type=" show ? 'text': 'password' " name="password" id="password" class="flex-1 p-2 pr-10 border border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 rounded text-sm text-gray-900" placeholder="Enter Doctor's password" type="password" required>
                            <button @click="show = !show" type="button" class="absolute right-2 bg-transparent flex items-center justify-center text-gray-700">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>

                                <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col my-4">
                        <label for="password_confirmation" class="text-gray-700">Password Confirmation</label>
                        <div x-data="{ show: false }" class="relative flex items-center mt-2">
                            <input :type=" show ? 'text': 'password' " name="password_confirmation" id="password_confirmation" class="flex-1 p-2 pr-10 border border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 rounded text-sm text-gray-900" placeholder="Enter Doctor's password again" type="password" required>
                            <button @click="show = !show" type="button" class="absolute right-2 bg-transparent flex items-center justify-center text-gray-700">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>

                                <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                    </div>
                
                    <div class="my-4 flex items-center justify-end space-x-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 rounded-lg px-8 py-2 text-gray-100 hover:shadow-xl transition duration-150 uppercase">Create Account</button>    
                    </div>
                </form>
            </div>
        </div>
    </div>

    

    <script>
        $(document).ready(function() {
            $('#doctorForm').submit(function(event) {
                // Prevent default form submission
                event.preventDefault();

                // Call the validation function
                if (!validateForm()) {
                    return; // Stop further processing if validation fails
                }

                // Serialize form data
                var formData = $(this).serialize();

                // Perform AJAX request
                $.ajax({
                    type: 'POST',
                    url: 'backend/CreateDoctor.php',
                    data: formData,
                    success: function(response) {
                        // Handle successful response here
                        alert(response);

                        if(response == "Account has been Created"){
                            $('#doctorForm')[0].reset();
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

        // Validation function
        function validateForm() {
            var password = document.getElementById("password").value;
            var password_confirmation = document.getElementById("password_confirmation").value;

            if (password !== password_confirmation) {
                alert("Passwords do not match");
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>

</body>
</html>