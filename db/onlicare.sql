SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE address (
  Address_ID int(11) NOT NULL,
  Country varchar(255) NOT NULL,
  Province varchar(255) NOT NULL,
  City varchar(255) NOT NULL,
  Baranggay varchar(255) NOT NULL,
  Zip_Code varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `admin` (
  Admin_ID int(11) NOT NULL,
  User_ID int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE appointment (
  AppointmentID int(11) NOT NULL,
  Patient_ID int(11) NOT NULL,
  Doctor_ID int(11) NOT NULL,
  date date NOT NULL,
  Status varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE contact_info (
  Contact_Info_ID int(11) NOT NULL,
  Phone_Number varchar(20) DEFAULT NULL,
  Email varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE department (
  Department_ID int(11) NOT NULL,
  department_name varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE doctor (
  Doctor_ID int(11) NOT NULL,
  User_ID int(11) NOT NULL,
  Department_ID int(11) NOT NULL,
  Specialization_ID int(11) NOT NULL,
  is_available tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE patient (
  Patient_ID int(11) NOT NULL,
  User_ID int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE records (
  RecordID int(11) NOT NULL,
  Patient_ID int(11) NOT NULL,
  Doctor_ID int(11) NOT NULL,
  Diagnosis text NOT NULL,
  Feedback text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE specialization (
  specialization_id int(11) NOT NULL,
  specialization_name varchar(255) NOT NULL,
  specialization_desc text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `user` (
  UserID int(11) NOT NULL,
  First_Name varchar(255) NOT NULL,
  Middle_Initial char(1) NOT NULL,
  Last_Name varchar(255) NOT NULL,
  UserType enum('Patient','Doctor','Admin') DEFAULT NULL,
  Address_ID int(11) DEFAULT NULL,
  Contact_Info_ID int(11) DEFAULT NULL,
  Email varchar(255) NOT NULL,
  Password varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE address
  ADD PRIMARY KEY (Address_ID);

ALTER TABLE admin
  ADD PRIMARY KEY (Admin_ID),
  ADD UNIQUE KEY User_ID (User_ID);

ALTER TABLE appointment
  ADD PRIMARY KEY (AppointmentID),
  ADD KEY Patient_ID (Patient_ID),
  ADD KEY Doctor_ID (Doctor_ID),
  ADD KEY AppointmentDateTime (date);

ALTER TABLE contact_info
  ADD PRIMARY KEY (Contact_Info_ID);

ALTER TABLE department
  ADD PRIMARY KEY (Department_ID);

ALTER TABLE doctor
  ADD PRIMARY KEY (Doctor_ID),
  ADD UNIQUE KEY doctor_ibfk_1 (User_ID) USING BTREE,
  ADD KEY Specialization_ID_2 (Specialization_ID),
  ADD KEY doctor_ibfk_2 (Department_ID);

ALTER TABLE patient
  ADD PRIMARY KEY (Patient_ID),
  ADD UNIQUE KEY User_ID (User_ID);

ALTER TABLE records
  ADD PRIMARY KEY (RecordID),
  ADD KEY records_ibfk_1 (Patient_ID),
  ADD KEY records_ibfk_2 (Doctor_ID);

ALTER TABLE specialization
  ADD PRIMARY KEY (specialization_id);

ALTER TABLE user
  ADD PRIMARY KEY (UserID),
  ADD UNIQUE KEY Address_ID (Address_ID) USING BTREE,
  ADD UNIQUE KEY Contact_Info_ID (Contact_Info_ID) USING BTREE;


ALTER TABLE address
  MODIFY Address_ID int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE admin
  MODIFY Admin_ID int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE appointment
  MODIFY AppointmentID int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE department
  MODIFY Department_ID int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE doctor
  MODIFY Doctor_ID int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE patient
  MODIFY Patient_ID int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE specialization
  MODIFY specialization_id int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE user
  MODIFY UserID int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE admin
  ADD CONSTRAINT admin_ibfk_1 FOREIGN KEY (User_ID) REFERENCES `user` (UserID);

ALTER TABLE appointment
  ADD CONSTRAINT appointment_ibfk_1 FOREIGN KEY (Patient_ID) REFERENCES patient (Patient_ID),
  ADD CONSTRAINT appointment_ibfk_2 FOREIGN KEY (Doctor_ID) REFERENCES doctor (Doctor_ID);

ALTER TABLE doctor
  ADD CONSTRAINT doctor_ibfk_1 FOREIGN KEY (User_ID) REFERENCES `user` (UserID),
  ADD CONSTRAINT doctor_ibfk_2 FOREIGN KEY (Department_ID) REFERENCES department (Department_ID),
  ADD CONSTRAINT doctor_ibfk_3 FOREIGN KEY (Specialization_ID) REFERENCES specialization (specialization_id);

ALTER TABLE patient
  ADD CONSTRAINT patient_ibfk_1 FOREIGN KEY (User_ID) REFERENCES `user` (UserID);

ALTER TABLE records
  ADD CONSTRAINT records_ibfk_1 FOREIGN KEY (Patient_ID) REFERENCES patient (Patient_ID),
  ADD CONSTRAINT records_ibfk_2 FOREIGN KEY (Doctor_ID) REFERENCES doctor (Doctor_ID);

ALTER TABLE user
  ADD CONSTRAINT user_ibfk_1 FOREIGN KEY (Address_ID) REFERENCES address (Address_ID),
  ADD CONSTRAINT user_ibfk_2 FOREIGN KEY (Contact_Info_ID) REFERENCES contact_info (Contact_Info_ID);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
