<?php
session_start();
include 'core/connection.php';
$conn = new mysqli("localhost", "root", "", "onlicare");



// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["name"];
    $middle_initial = $_POST["middleName"];
    $last_name = $_POST["LastName"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $password_confirmation = $_POST["password_confirmation"];
    $user_type = "Patient"; 

    // Check if passwords match
    if ($password !== $password_confirmation) {
        echo "Passwords do not match.";
        exit();
    }

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

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO user (First_Name, Middle_Initial, Last_Name, Email, UserType, Password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $first_name, $middle_initial, $last_name, $email, $user_type, $hashed_password);

    if ($stmt->execute()) {
        header('Location: ..\index.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="..\output.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    <div class="bg-gray-200 w-full min-h-screen flex items-center justify-center">
            <div class="w-full py-8">
                <div class="flex items-center justify-center space-x-2">
                    <img src="..\..\assets\onlicarelogo.svg" class="h-8" alt="Logo">
                    <h1 class="text-5xl font-bold text-yellow-500 tracking-wider">Onli</h1>
                    <h1 class="text-5xl font-bold text-green-700 tracking-wider">Care</h1>
                </div>
                <div class="bg-white w-5/6 md:w-3/4 lg:w-2/3 xl:w-[500px] 2xl:w-[550px] mt-8 mx-auto px-16 py-8 rounded-lg shadow-2xl">

                    <h2 class="text-center text-2xl font-bold tracking-wide text-gray-800">Sign Up</h2>
                    <p class="text-center text-sm text-gray-600 mt-2">Already have an account? <a href="login.html" class="text-blue-600 hover:text-blue-700 hover:underline" title="Sign In">Sign in here</a></p>

                    <form class="my-8 text-sm" method = "post">
                        <div class="flex flex-col my-4">
                            <label for="name" class="text-gray-700">Name</label>
                            <input type="text" name="name" id="name" class="mt-2 p-2 border border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 rounded text-sm text-gray-900" placeholder="Enter your name">
                        </div>
                        <div class="flex flex-col my-4">
                            <label for="middleName" class="text-gray-700">middleName</label>
                            <input type="text" name="middleName" id="middleName" class="mt-2 p-2 border border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 rounded text-sm text-gray-900" placeholder="Enter your middleName">
                        </div>
                        <div class="flex flex-col my-4">
                            <label for="LastName" class="text-gray-700">LastName</label>
                            <input type="text" name="LastName" id="LastName" class="mt-2 p-2 border border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 rounded text-sm text-gray-900" placeholder="Enter your Last Name">


                        <div class="flex flex-col my-4">
                            <label for="email" class="text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" class="mt-2 p-2 border border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 rounded text-sm text-gray-900" placeholder="Enter your email">
                        </div>
                        
                        <div class="flex flex-col my-4">
                            <label for="password" class="text-gray-700">Password</label>
                            <div x-data="{ show: false }" class="relative flex items-center mt-2">
                                <input :type=" show ? 'text': 'password' " name="password" id="password" class="flex-1 p-2 pr-10 border border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 rounded text-sm text-gray-900" placeholder="Enter your password" type="password">
                                <button @click="show = !show" type="button" class="absolute right-2 bg-transparent flex items-center justify-center text-gray-700">
                                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>

                                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex flex-col my-4">
                            <label for="password_confirmation" class="text-gray-700">Password Confirmation</label>
                            <div x-data="{ show: false }" class="relative flex items-center mt-2">
                                <input :type=" show ? 'text': 'password' " name="password_confirmation" id="password_confirmation" class="flex-1 p-2 pr-10 border border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 rounded text-sm text-gray-900" placeholder="Enter your password again" type="password">
                                <button @click="show = !show" type="button" class="absolute right-2 bg-transparent flex items-center justify-center text-gray-700">
                                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>

                                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="remember_me" id="remember_me" class="mr-2 focus:ring-0 rounded">
                            <label for="remember_me" class="text-gray-700">I accept the <a href="#" class="text-blue-600 hover:text-blue-700 hover:underline">terms</a> and <a href="#" class="text-blue-600 hover:text-blue-700 hover:underline">privacy policy</a></label>
                        </div>
                    
                        <div class="my-4 flex items-center justify-end space-x-4">
                            <button class="bg-blue-600 hover:bg-blue-700 rounded-lg px-8 py-2 text-gray-100 hover:shadow-xl transition duration-150 uppercase">Sign Up</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</body>
</html>