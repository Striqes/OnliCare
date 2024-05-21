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
            <div class="flex items-center justify-center space-x-2">
                <img src="..\..\assets\onlicarelogo.svg" class="h-8" alt="Logo">
                <h1 class="text-5xl font-bold text-yellow-500 tracking-wider">Onli</h1>
                <h1 class="text-5xl font-bold text-green-700 tracking-wider">Care</h1>
            </div>
            <div class="bg-white w-5/6 md:w-3/4 lg:w-2/3 xl:w-[500px] 2xl:w-[500] mt-8 mx-auto px-16 py-8 rounded-lg shadow-2xl">
                <h2 class="text-center text-2xl font-bold tracking-wide text-gray-800">Log in</h2>
                <form class="my-8 text-sm" id="loginForm">
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
                        <div class="relative flex items-center mt-2">
                            <input type="password" name="password" id="password" class="flex-1 p-2 pr-10 border border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 rounded text-sm text-gray-900" placeholder="Enter your password">
                        </div>
                    </div>
                    <div class="my-4 flex items-center justify-end space-x-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 rounded-lg px-8 py-2 text-gray-100 hover:shadow-xl transition duration-150 uppercase">Log In</button>
                    </div>
                    <p class="text-base">
                        <strong class="text-lg mb-2">First Time Here?<br/></strong>
                        For <strong>Patients and Users:<br/></strong>
                        &ensp;Continue at <a href="signup.php" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Sign Up</a><br/><br/> 
                        For <strong>Doctors and Staff:<br/></strong>
                        &ensp;Request for credential via Admin<br/>
                    </p>
                    <br><br><br>
                    <div class="flex items-center justify-between">
                        <div class="w-full h-[1px] bg-gray-300"></div>
                        <div class="w-full h-[1px] bg-gray-300"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#loginForm').submit(function(event) {
                event.preventDefault(); // Prevent the form from submitting normally

                // Get form data
                var formData = $(this).serialize();

                // Send AJAX request
                $.ajax({
                    type: 'POST',
                    url: 'loginBackend.php',
                    data: formData,
                    success: function(response) {
                        console.log(response); // Log the response from the server

                        // Handle success or error response here
                        if (response.status === 'success') {
                            // Redirect to the appropriate page
                            window.location.href = response.redirect;
                        } else {
                            // Display an error message
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle AJAX error
                        console.error(xhr.responseText);
                        alert('An error occurred while processing your request.');
                    }
                });
            });
        });

    </script>
</body>
</html>
