<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - OnliCare</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> <!-- AJAX CDN -->
    <link rel="stylesheet" href="../output.css">
</head>

<body class="bg-black">

    <!-- Navigation -->
    <header>
        <nav class="bg-white border-b border-gray-200 dark:bg-green-900">
            <div class="max-w-screen-xl mx-auto px-4 py-6 flex items-center justify-between">
                <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="..\..\assets\onlicarelogo.svg" class="h-10" alt="Logo" />
                    <span class="text-2xl font-semibold whitespace-nowrap dark:text-yellow-400">OnliCare</span>
                </a>

                <!-- MENU BAR -->
                <div class="gap-8 nav-links duration-500 md:static absolute md:min-h-fit bg-green-900 max-h-[15vh] left-0 top-[-100%] md:w-auto w-full flex items-center p-5">
                    <ul class="mx-auto md:ml-0 flex flex-col items-center justify-center gap-8 md:flex-row md:gap-[2vw]">
                        <li>
                            <a href="#" onclick="onToggleMenu(this); scrollToContent('home')" class="block py-2 px-3 text-white rounded hover:bg-transparent hover:text-green-700 dark:text-white dark:hover:text-yellow-300">Home</a>
                        </li>
                        <li>
                            <a href="#" onclick="onToggleMenu(this); scrollToContent('about-us')" class="block py-2 px-3 rounded hover:text-green-700 dark:text-white dark:hover:text-yellow-500">About</a>
                        </li>
                        <li>
                            <a href="#" onclick="onToggleMenu(this); scrollToContent('services')" class="block py-2 px-3 text-yellow-900 rounded hover:text-green-700 dark:text-white dark:hover:text-yellow-500">Services</a>
                        </li>
                    </ul>
                    <ul id="loginButtons" class="mx-auto md:ml-0 flex flex-col items-center justify-center gap-8 md:flex-row md:gap-[2vw]">
                        <li>
                            <a href="user/login.php" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">Log in</a>
                        </li>
                        <li>
                            <a href="user/signup.php" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">Sign up</a>
                        </li>
                    </ul>
                </div>

                
                <ion-icon onclick="onToggleMenu(this)" name="menu" class="text-3xl cursor-pointer md:hidden"></ion-icon>
            </div>
        </nav>
    </header>
    <!-- Profile Content -->
    <main class="container mx-auto py-8">

        <div class="max-w-3xl mx-auto bg-white shadow-md rounded-md overflow-hidden">

            <!-- Profile Header -->
            <div class="bg-gray-100 px-6 py-4">
                <h1 class="text-3xl font-semibold text-gray-800">User Profile</h1>
            </div>

            <!-- Profile Information -->
            <div class="p-6">

                <!-- PHP dynamic content -->
                <!-- Your PHP code to fetch and display user information goes here -->

                <div class="flex items-center space-x-4">
                    <!-- User Details -->
                    <div>
                        <!-- Display user details fetched from PHP -->
                        <h2 class="text-xl font-semibold text-gray-800">User's Name</h2>
                        <!-- Example: Display user's full name -->
                        <p class="text-gray-600">Full Name</p>
                        <!-- Example: Display user's email -->
                        <p class="text-gray-600">user@example.com</p>
                    </div>
                </div>

                <!-- Additional Profile Information -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-800">Additional Information</h3>
                    <!-- Example: Display additional profile information -->
                    <p class="mt-2 text-gray-600">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam
                        aliquet id nibh eu tempus.</p>
                </div>

                <!-- Edit Profile Button -->
                <div class="mt-6">
                    <a href="#" class="text-blue-600 hover:underline">Edit Profile</a>
                </div>

            </div>

        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-4">
        <div class="container mx-auto text-center text-gray-600">
            <!-- Footer content -->
        </div>
    </footer>

</body>

</html>
