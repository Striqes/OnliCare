<?php
include 'core/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM user WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['Password'])) {
        $_SESSION['email'] = $email;
        $_SESSION['user_id'] = $user['UserID'];
        $_SESSION['UserType'] = $user['UserType'];

        // Check user type and redirect accordingly
        if ($user['UserType'] == 'Admin'){
            header("Location: $adminIndex");
            exit;
        } else if ($user['UserType'] == 'Doctor'){
            header("Location: $doctorIndex");
        }
        else if ($user['UserType'] == 'Patient'){
            header("Location: $patientIndex");
        }
        exit();
    } else {
        // Password is incorrect
        echo "The password you entered was not valid.";
    }
}


$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="..\output.css">
    <title>Log in</title>
</head>

<body>
  <div class="bg-gray-200 w-full min-h-screen flex items-center justify-center">
    <div class="w-full py-4">
        <div class= "flex items-center justify-center space-x-2" >
          <img src="..\..\assets\onlicarelogo.svg" class="h-8" alt="Logo">
            <h1 class="text-5xl font-bold text-yellow-500 tracking-wider">Onli</h1>
            <h1 class="text-5xl font-bold text-green-700 tracking-wider">Care</h1>
        </div>
        <div class="bg-white w-5/6 md:w-3/4 lg:w-2/3 xl:w-[500px] 2xl:w-[500] mt-8 mx-auto px-16 py-8 rounded-lg shadow-2xl">
            <h2 class="text-center text-2xl font-bold tracking-wide text-gray-800">Log in</h2>
            <form class="my-8 text-sm" method = "POST">

                <div class="flex items-center justify-between">
                    <div class="w-full h-[1px] bg-gray-300"></div>
                    <div class="w-full h-[1px] bg-gray-300"></div>
                </div>

                <br/>

                <div class="flex flex-col my-4">
                    <label for="email" class="text-gray-700">Email </label>
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
                <div class="my-4 flex items-center justify-end space-x-4">
                    <button class="bg-blue-600 hover:bg-blue-700 rounded-lg px-8 py-2 text-gray-100 hover:shadow-xl transition duration-150 uppercase">Log In</button>
                </div>
                <p class="text-base">
                    <strong class="text-lg mb-2">First Time Here?<br/></strong>
                    For <strong >Patients and Users:<br/></strong>
                    &ensp;Continue at <a href="signup.php" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Sign Up</a><br/><br/> 
                    For <strong >Doctors and Staff:<br/></strong>
                    &ensp;Request for credential via Admin<br/>
                </p>
               
                <br>
                <br>
                <br>
                <div class="flex items-center justify-between">
                  <div class="w-full h-[1px] bg-gray-300"></div>
                  <div class="w-full h-[1px] bg-gray-300"></div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>