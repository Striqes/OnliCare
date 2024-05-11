  -- phpMyAdmin SQL Dump
  -- version 5.2.1
  -- https://www.phpmyadmin.net/
  --
  -- Host: 127.0.0.1
  -- Generation Time: May 10, 2024 at 02:58 PM
  -- Server version: 10.4.32-MariaDB
  -- PHP Version: 8.2.12

  SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
  START TRANSACTION;
  SET time_zone = "+00:00";


  /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
  /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
  /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
  /*!40101 SET NAMES utf8mb4 */;

  --
  -- Database: `onlicare`
  --

  -- --------------------------------------------------------

  --
  -- Table structure for table `address`
  --

  CREATE TABLE `address` (
    `Address_ID` int(11) NOT NULL,
    `County` varchar(255) NOT NULL,
    `Province` varchar(255) NOT NULL,
    `City` varchar(255) NOT NULL,
    `Baranggay` varchar(255) NOT NULL,
    `Zip_Code` varchar(20) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  -- --------------------------------------------------------

  --
  -- Table structure for table `admin`
  --

  CREATE TABLE `admin` (
    `Admin_ID` int(11) NOT NULL,
    `User_ID` int(11) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  -- --------------------------------------------------------

  --
  -- Table structure for table `appointment`
  --

  CREATE TABLE `appointment` (
    `AppointmentID` int(11) NOT NULL,
    `Patient_ID` int(11) NOT NULL,
    `Doctor_ID` int(11) NOT NULL,
    `AppointmentDateTime` datetime NOT NULL,
    `Status` varchar(50) DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  -- --------------------------------------------------------

  --
  -- Table structure for table `contact_info`
  --

  CREATE TABLE `contact_info` (
    `Contact_Info_ID` int(11) NOT NULL,
    `Phone_Number` varchar(20) DEFAULT NULL,
    `Email` varchar(255) DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  -- --------------------------------------------------------

  --
  -- Table structure for table `department`
  --

  CREATE TABLE `department` (
    `Department_ID` int(11) NOT NULL,
    `Specialization_Name` varchar(255) NOT NULL,
    `Specialization_Description` text NOT NULL,
    `department_name` enum('Department of Family Medicine','Department Ophthalmology','Acute Psychiatric Unit','Oncology Unit','Orthopaedic Department','Department of Pediatrics','Department of Surgery','Dental Department','Department of Obstetrics & Gynecology','Department of Internal Medicine') NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  --
  -- Dumping data for table `department`
  --

  INSERT INTO `department` (`Department_ID`, `Specialization_Name`, `Specialization_Description`, `department`) VALUES
  (1, '', '', 'Department of Family Medicine');

  -- --------------------------------------------------------

  --
  -- Table structure for table `doctor`
  --

  CREATE TABLE `doctor` (
    `Doctor_ID` int(11) NOT NULL,
    `User_ID` int(11) NOT NULL,
    `Department_ID` int(11) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  --
  -- Dumping data for table `doctor`
  --

  INSERT INTO `doctor` (`Doctor_ID`, `User_ID`, `Department_ID`) VALUES
  (2, 11, 1);

  -- --------------------------------------------------------

  --
  -- Table structure for table `doctor_available`
  --

  CREATE TABLE `doctor_available` (
    `available_ID` int(11) NOT NULL,
    `date` date NOT NULL,
    `time` time NOT NULL,
    `doctor_ID` int(11) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  --
  -- Dumping data for table `doctor_available`
  --

  INSERT INTO `doctor_available` (`available_ID`, `date`, `time`, `doctor_ID`) VALUES
  (1, '2024-05-11', '20:49:26', 2);

  -- --------------------------------------------------------

  --
  -- Table structure for table `patient`
  --

  CREATE TABLE `patient` (
    `Patient_ID` int(11) NOT NULL,
    `User_ID` int(11) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  -- --------------------------------------------------------

  --
  -- Table structure for table `records`
  --

  CREATE TABLE `records` (
    `RecordID` int(11) NOT NULL,
    `Patient_ID` int(11) NOT NULL,
    `Doctor_ID` int(11) NOT NULL,
    `Diagnosis` text NOT NULL,
    `Feedback` text NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  -- --------------------------------------------------------

  --
  -- Table structure for table `user`
  --

  CREATE TABLE `user` (
    `UserID` int(11) NOT NULL,
    `First_Name` varchar(255) NOT NULL,
    `Middle_Initial` char(1) NOT NULL,
    `Last_Name` varchar(255) NOT NULL,
    `UserType` enum('Patient','Doctor','Admin') DEFAULT NULL,
    `Address_ID` int(11) DEFAULT NULL,
    `Contact_Info_ID` int(11) DEFAULT NULL,
    `Email` varchar(255) NOT NULL,
    `Password` varchar(255) DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  --
  -- Dumping data for table `user`
  --

  INSERT INTO `user` (`UserID`, `First_Name`, `Middle_Initial`, `Last_Name`, `UserType`, `Address_ID`, `Contact_Info_ID`, `Email`, `Password`) VALUES
  (1, 'zion', 'p', 'panitan', 'Patient', NULL, NULL, 'zion@gmail.com', '$2y$10$ztrqY1dSRf55COiCI9tw8u7XcvFHFBeIAZXZjgmZVxa0EHzrzIdkq'),
  (2, 'emman', 'e', 'asun', 'Doctor', NULL, NULL, 'emman@gmail.com', '$2y$10$76TzMdGKzarpV/EaWB5OQOfjokVduJTXyv8oErZfx6J/JwGf4v1TO');

  --
  -- Indexes for dumped tables
  --

  --
  -- Indexes for table `address`
  --
  ALTER TABLE `address`
    ADD PRIMARY KEY (`Address_ID`);

  --
  -- Indexes for table `admin`
  --
  ALTER TABLE `admin`
    ADD PRIMARY KEY (`Admin_ID`),
    ADD UNIQUE KEY `User_ID` (`User_ID`);

  --
  -- Indexes for table `appointment`
  --
  ALTER TABLE `appointment`
    ADD PRIMARY KEY (`AppointmentID`),
    ADD KEY `Patient_ID` (`Patient_ID`),
    ADD KEY `Doctor_ID` (`Doctor_ID`),
    ADD KEY `AppointmentDateTime` (`AppointmentDateTime`);

  --
  -- Indexes for table `contact_info`
  --
  ALTER TABLE `contact_info`
    ADD PRIMARY KEY (`Contact_Info_ID`);

  --
  -- Indexes for table `department`
  --
  ALTER TABLE `department`
    ADD PRIMARY KEY (`Department_ID`);

  --
  -- Indexes for table `doctor`
  --
  ALTER TABLE `doctor`
    ADD PRIMARY KEY (`Doctor_ID`),
    ADD UNIQUE KEY `User_ID` (`User_ID`),
    ADD UNIQUE KEY `Specialization_ID` (`Department_ID`);

  --
  -- Indexes for table `doctor_available`
  --
  ALTER TABLE `doctor_available`
    ADD PRIMARY KEY (`available_ID`),
    ADD KEY `doctor_available_ibfk_1` (`doctor_ID`);

  --
  -- Indexes for table `patient`
  --
  ALTER TABLE `patient`
    ADD PRIMARY KEY (`Patient_ID`),
    ADD UNIQUE KEY `User_ID` (`User_ID`);

  --
  -- Indexes for table `records`
  --
  ALTER TABLE `records`
    ADD PRIMARY KEY (`RecordID`),
    ADD UNIQUE KEY `Patient_ID` (`Patient_ID`),
    ADD UNIQUE KEY `Doctor_ID` (`Doctor_ID`);

  --
  -- Indexes for table `user`
  --
  ALTER TABLE `user`
    ADD PRIMARY KEY (`UserID`),
    ADD KEY `Address_ID` (`Address_ID`),
    ADD KEY `Contact_Info_ID` (`Contact_Info_ID`);

  --
  -- AUTO_INCREMENT for dumped tables
  --

  --
  -- AUTO_INCREMENT for table `department`
  --
  ALTER TABLE `department`
    MODIFY `Department_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

  --
  -- AUTO_INCREMENT for table `doctor`
  --
  ALTER TABLE `doctor`
    MODIFY `Doctor_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

  --
  -- AUTO_INCREMENT for table `doctor_available`
  --
  ALTER TABLE `doctor_available`
    MODIFY `available_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

  --
  -- AUTO_INCREMENT for table `user`
  --
  ALTER TABLE `user`
    MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

  --
  -- Constraints for dumped tables
  --

  --
  -- Constraints for table `admin`
  --
  ALTER TABLE `admin`
    ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`UserID`);

  --
  -- Constraints for table `appointment`
  --
  ALTER TABLE `appointment`
    ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`Patient_ID`) REFERENCES `patient` (`Patient_ID`),
    ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`Doctor_ID`) REFERENCES `doctor` (`Doctor_ID`);

  --
  -- Constraints for table `doctor`
  --
  ALTER TABLE `doctor`
    ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`UserID`),
    ADD CONSTRAINT `doctor_ibfk_2` FOREIGN KEY (`Department_ID`) REFERENCES `department` (`Department_ID`);

  --
  -- Constraints for table `doctor_available`
  --
  ALTER TABLE `doctor_available`
    ADD CONSTRAINT `doctor_available_ibfk_1` FOREIGN KEY (`doctor_ID`) REFERENCES `doctor` (`Doctor_ID`);

  --
  -- Constraints for table `patient`
  --
  ALTER TABLE `patient`
    ADD CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`UserID`);

  --
  -- Constraints for table `records`
  --
  ALTER TABLE `records`
    ADD CONSTRAINT `records_ibfk_1` FOREIGN KEY (`Patient_ID`) REFERENCES `patient` (`Patient_ID`),
    ADD CONSTRAINT `records_ibfk_2` FOREIGN KEY (`Doctor_ID`) REFERENCES `doctor` (`Doctor_ID`);

  --
  -- Constraints for table `user`
  --
  ALTER TABLE `user`
    ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`Address_ID`) REFERENCES `address` (`Address_ID`),
    ADD CONSTRAINT `user_ibfk_2` FOREIGN KEY (`Contact_Info_ID`) REFERENCES `contact_info` (`Contact_Info_ID`);
  COMMIT;

  /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
  /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
  /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
