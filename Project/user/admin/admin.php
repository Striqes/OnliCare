<?php

include '../core/connection.php';
include '../core/sessiontimeout.php';


if($_SESSION['UserType'] != 'Admin'){
    header("Location: $login");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-300 flex flex-col items-center justify-center min-h-screen">

    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h1 class="text-3xl text-center mb-8">Admin Panel</h1>
        <div class="grid grid-cols-1 gap-4">
            <a href="doctoraccount.php" class="block py-3 px-4 bg-green-900 text-yellow-400 text-center rounded-md hover:bg-green-600">Create a Doctor's Account</a>
            <a href="modification.php" class="block py-3 px-4 bg-green-900 text-yellow-400 text-center rounded-md hover:bg-green-600">Modify Patient Information</a>
        </div>
    </div>

</body>

</html>
