<?php
include 'user/core/connection.php';
include 'user/core/sessiontimeout.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnliCare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- HUMBRUGER -->
    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> <!-- AJAX CDN -->
    <link rel="stylesheet" href="output.css">

</head>
<body class="bg-white">

    <!-- NAV BAR -->
    <header>
        <nav class="bg-white border-b border-gray-200 dark:bg-green-900">
            <div class="max-w-screen-xl mx-auto px-4 py-6 flex items-center justify-between">
                <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="..\assets\onlicarelogo.svg" class="h-10" alt="Logo" />
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

    <?php 
        if(!isset($_SESSION["user_id"])){
            // nothing happens
        } else {
            $user_id = $_SESSION['user_id']; 
            $query = "SELECT First_Name, Middle_Initial, Last_Name FROM user WHERE UserID = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);

            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            echo '<script>changeLoginButtonsContent();</script>';
        }

    ?>
    
    <!-- CONTENT -->
 <main class="bg-gray-100 p-20 pl-24 min-h-screen">


        <!-- HOME -->
        <div class="container mx-auto grid md:grid-cols-2 min-h-2" id="home">
            <div class="max-w-screen-xl mx-auto flex flex-col justify-center">
                <div class="text-green-950 text-4xl md:text-5xl font-bold leading-tight">OnliCare Commitment to Quality <br class="hidden md:inline">and Best Service</div>
                <div class="mt-2 md:mt-0 text-green-800 text-lg md:text-2xl font-bold">Discover our dedication to providing exceptional<br class="hidden md:inline"> healthcare across all aspects of patient care.</div>
        
                <div class="grid md:grid-cols-2 gap-8 mt-8">
                    <div class="p-4 bg-gray-300 rounded-lg">
                        <div class="text-center font-bold text-black">Affordable Consultation</div>
                        <div class="text-center mt-2">Get answers to your health related questions with our free sessions with healthcare experts.</div>
                    </div>
                    <div class="p-4 bg-gray-300 rounded-lg">
                        <div class="text-center font-bold text-black">Affordable Service</div>
                        <div class="text-center mt-2">Access quality health care at an affordable cost with our Affordable Medical Services.</div>
                    </div>
                </div>
            </div>
            <div class="md:flex md:justify-center md:pl-8 lg:pl-0">
                <img src="..\assets\Medical Clinic (Facebook Post).png" alt="Doctors image" class="">
            </div>
        </div>
        
        <hr class="h-px my-8 bg-gray-200 border-2 dark:bg-gray-700">
        
        <!-- ABOUT US -->
        <div class="container mx-auto grid md:grid-cols-2" id="about-us">
            <div class="md:flex md:justify-center md:pl-8 lg:pl-0">
                <img src="..\assets\Blue Simple Online Doctor Consultations Instagram Post (1).png" alt="" class="scale-x-[-1]">
            </div>
            <div class="md:pt-24 md:pl-6 lg:pl-0 flex flex-col items-center justify-center h-full">
  
                <div>
                  <div class="text-green-950 text-2xl md:text-5xl font-bold leading-tight">About Us</div>
                  <div class="mt-2 md:mt-4 text-green-800 text-lg md:text-xl lg:text-2xl font-bold">OnliCare is an intuitive and easy-to-use medical hospital management system ideal for healthcare professionals at hospitals and private practices.</div>
                </div>
              
              </div>
        </div>
        
        <hr class="h-px my-8 bg-gray-200 border-2 dark:bg-gray-700">

        <!-- SERVICES -->
        <service class="container mx-auto grid md:grid-cols-2 gap-8" id="services">
            <div class="md:pt-24 md:pl-6 lg:pl-0">
                <div class="text-green-950 text-2xl md:text-5xl font-bold leading-tight">Our Services</div>
                <div class="mt-2 md:mt-4 text-green-800 text-lg md:text-xl lg:text-2xl font-bold">We offer a range of healthcare services to meet your needs.</div>
            </div>
            <div class="md:flex md:justify-center md:pl-8 lg:pl-0">
                <ul class="grid md:grid-cols-2 gap-8 mt-8">
                    <li class="p-4 bg-gray-300 rounded-lg">
                        <div class="text-center font-bold text-black">Consultation</div>
                        <div class="text-center mt-2">Get answers to your health related questions with our free sessions with healthcare experts.</div>
                    </li>
                    <li class="p-4 bg-gray-300 rounded-lg">
                        <div class="text-center font-bold text-black">Medical Services</div>
                        <div class="text-center mt-2">Access quality health care at an affordable cost with our Affordable Medical Services.</div>
                    </li>
                    <li class="p-4 bg-gray-300 rounded-lg">
                        <div class="text-center font-bold text-black">Emergency Services</div>
                        <div class="text-center mt-2">Get immediate medical attention with our Emergency Services.</div>
                    </li>
                    <li class="p-4 bg-gray-300 rounded-lg">
                        <div class="text-center font-bold text-black">Healthcare Products</div>
                        <div class="text-center mt-2">Get quality healthcare products at an affordable cost with our Healthcare Products.</div>
                    </li>
                </ul>
            </div>
        </service>

        <hr class="h-px my-8 bg-gray-200 border-2 dark:bg-gray-700">

        <!-- CONTACT US -->
        <div class="container mx-auto grid md:grid-cols-2 gap-8" id="contact-us">
            <div class="md:pt-24 md:pl-6 lg:pl-0">
                <div class="text-green-950 text-2xl md:text-5xl font-bold leading-tight">Contact Us</div>
                <div class="mt-2 md:mt-4 text-green-800 text-lg md:text-xl lg:text-2xl font-bold">We'd love to hear from you. Reach out to us from the form below.</div>
                <div class="flex justify-center space-x-5 mt-5">
                    <a href="https://www.facebook.com" target="_blank"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="mailto:example@gmail.com"><i class="fas fa-envelope fa-lg"></i></a>
                    <a href="https://www.twitter.com" target="_blank"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram fa-lg"></i></a>
                </div>
            </div>
            <div class="md:flex md:justify-center md:pl-8 lg:pl-0">
                <form class="w-full max-w-lg border-gray-300 border-2 p-5 rounded-lg">
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                                First Name
                            </label>
                            <input class="border-gray-300 appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="grid-first-name" type="text" placeholder="Emman">
                        </div>
                        <div class="w-full md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                                Last Name
                            </label>
                            <input class="border-gray-300 appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="grid-last-name" type="text" placeholder="manloloko">
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-email">
                                Email
                            </label>
                            <input class="border-gray-300 appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="grid-email" type="email" placeholder="Emmmanloloko@example.com">
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-message">
                                Message
                            </label>
                            <textarea class="border-gray-300 border-2 no-resize appearance-none block w-full bg-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white h-48 resize-none" id="grid-message"></textarea>
                        </div>
                    </div>
                    <div class="md:flex md:items-center">
                        <div class="md:w-1/3">
                            <button class="shadow bg-green-500 hover:bg-green-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="button">
                                Send
                            </button>
                        </div>
                        <div class="md:w-2/3"></div>
                    </div>
                </form>
                
            </div>
        </div>

        <hr class="h-px my-8 bg-gray-200 border-2 dark:bg-gray-700">

    </main>
   


    <!-- FOOTER -->
    <footer class="bg-white shadow-md dark:bg-green-900">
        <div class="max-w-screen-xl mx-auto p-4 md:p-8 flex flex-col md:flex-row items-center justify-between">
            <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse mb-4 md:mb-0">
                <img src="..\assets\onlicarelogo.svg" class="h-8" alt="Logo">
                <span class="text-2xl font-semibold whitespace-nowrap dark:text-white">OnliCare</span>
            </a>
            <ul class="flex flex-wrap justify-center md:justify-end items-center space-x-4">
                <li>
                    <a href="#" onclick="onToggleMenu(this); scrollToContent('about-us')" class="hover:underline text-yellow-300">About</a>
                </li>
            </ul>
        </div>
        <hr class="my-6 border-yellow-200 dark:border-yellow-700">
        <span class="block text-sm text-gray-500 text-center dark:text-gray-400">© 2023 <a href="#" class="hover:underline">OnliCare™</a>. All Rights Reserved.</span>
    </footer>

    <!-- ScrollDown Script -->
    <script>
        function scrollToContent(id) {
            const element = document.getElementById(id);
            if (element) {
                element.scrollIntoView({ behavior: "smooth" });
            }
        }

        // Check for section ID in URL and scroll to it
        window.addEventListener('DOMContentLoaded', (event) => {
            const hash = window.location.hash.substring(1); // Remove the '#' from the hash
            if (hash) {
                scrollToContent(hash);
            }
        });

        // Function for Menu Button on smaller dimension devices
        const navLinks = document.querySelector('.nav-links')
        function onToggleMenu(e){
            e.name = e.name === 'menu' ? 'close' : 'menu'
            navLinks.classList.toggle('top-[9%]')
            // navLinks.classList.toggle('hidden');
        }

        function changeLoginButtonsContent() {
            var loginButtons = document.getElementById("loginButtons");
            if (loginButtons) {
                loginButtons.innerHTML = `


                    <?php 
                    
                        if(!isset($_SESSION['user_id'])){
                            exit();
                        } else {
                            if($_SESSION['UserType'] == 'Patient'){
                                echo
                                '<li>
                                    <a href="'. $appointment .'" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">Make an Appointment</a>
                                </li>';
                                if($_SESSION['UserType'] == 'Patient'){
                                    echo
                                    '<li>
                                        <a href="'. $ViewAppointment .'" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">View Appointments</a>
                                    </li>';
                                }
                            }else if($_SESSION['UserType'] == 'Doctor'){
                                echo
                                '<li>
                                    <a href="'. $doctorIndex .'" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">Dashboard</a>
                                </li>
                                
                                <li>
                                    <a href="'. $doctorProfile .'" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">Profile</a>
                                </li>
                                ';
                            }else if($_SESSION['UserType'] == 'Admin'){
                                echo
                                '<li>
                                    <a href="'. $adminIndex .'" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">Admin Panel</a>
                                </li>';
                            } else {
                                exit('Unknown User Type');
                            }

                            if($_SESSION['UserType'] == 'Doctor'){

                            } else {
                                echo
                                '<li>
                                    <a href="'. $defProfile .'" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">Profile</a>
                                </li>';
                            }
                        }

                    ?>

                    <li>
                        <button type="submit" onclick="logout()" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">Log out</button>
                    </li>
                `;
            }
        }

        function logout() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "user/core/logout.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText);
                }
            };
            xhr.send("action=logout");
            location.reload();
        }

    </script>

<?php 
    if(!$_SESSION["user_id"]){
        // nothing happens
    } else {
        $user_id = $_SESSION['user_id']; 
        $query = "SELECT First_Name, Middle_Initial, Last_Name FROM user WHERE UserID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);

        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        echo '<script>changeLoginButtonsContent();</script>';
    }

?>

</body>
</html>
