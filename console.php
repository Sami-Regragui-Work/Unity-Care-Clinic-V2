<?php

require_once "assets/php/class/Patient.php";
require_once "assets/php/class/Doctor.php";
require_once "assets/php/class/Department.php";
require_once "assets/php/class/config/DataBase.php";

$db = DataBase::getTheOnlyDB();
$pdo = $db->getPdo();

do {
    system("clear");
    echo <<<BASH
    === Unity Care CLI ===
    1. Manage patients  
    2. Manage doctors  
    3. Manage departments  
    4. Statistics  
    5. Exit
    BASH . "\n\n";

    $choice0 = trim(readline("Enter choice: "));

    $repeat0 = match ($choice0) {
        "1" => patientMenu($pdo),
        "2" => doctorMenu($pdo),
        "3" => departmentMenu($pdo),
        "4" => statisticsMenu($pdo),
        "5" => exitApp(),
        default => 1,
    };
} while ($repeat0 !== -1);

function exitApp(): int
{
    echo "Thank you for using the Hospital Management System. Goodbye!\n\n";
    return -1;
}

function patientMenu(PDO $pdo): int
{
    do {
        system("clear");
        echo <<<BASH
        === Patient Management ===
        1. List all patients
        2. Search for a patient
        3. Add a patient
        4. Edit a patient
        5. Delete a patient
        6. Back
        BASH . "\n\n";

        $choice = trim(readline("Enter choice: "));

        $repeat = match ($choice) {
            "1" => listAllPatients($pdo),
            "2" => searchPatient($pdo),
            "3" => addPatient($pdo),
            "4" => editPatient($pdo),
            "5" => deletePatient($pdo),
            "6" => -1,
            default => 1,
        };
    } while ($repeat !== -1);

    return 0;
}

function doctorMenu(PDO $pdo): int
{
    do {
        system("clear");
        echo <<<BASH
        === Doctor Management ===
        1. List all doctors
        2. Search for a doctor
        3. Add a doctor
        4. Edit a doctor
        5. Delete a doctor
        6. Back
        BASH . "\n\n";

        $choice = trim(readline("Enter choice: "));

        $repeat = match ($choice) {
            "1" => listAllDoctors($pdo),
            "2" => searchDoctor($pdo),
            "3" => addDoctor($pdo),
            "4" => editDoctor($pdo),
            "5" => deleteDoctor($pdo),
            "6" => -1,
            default => 1,
        };
    } while ($repeat !== -1);

    return 0;
}

function departmentMenu(PDO $pdo): int
{
    do {
        system("clear");
        echo <<<BASH
        === Department Management ===
        1. List all departments
        2. Search for a department
        3. Add a department
        4. Edit a department
        5. Delete a department
        6. Back
        BASH . "\n\n";

        $choice = trim(readline("Enter choice: "));

        $repeat = match ($choice) {
            "1" => listAllDepartments($pdo),
            "2" => searchDepartment($pdo),
            "3" => addDepartment($pdo),
            "4" => editDepartment($pdo),
            "5" => deleteDepartment($pdo),
            "6" => -1,
            default => 1,
        };
    } while ($repeat !== -1);

    return 0;
}

function statisticsMenu(PDO $pdo): int
{
    do {
        system("clear");
        echo <<<BASH
        === Statistics ===
        1. Patient statistics
        2. Doctor statistics
        3. Department statistics
        4. Overall statistics
        5. Back
        BASH . "\n\n";

        $choice = trim(readline("Enter choice: "));

        $repeat = match ($choice) {
            "1" => showPatientStats($pdo),
            "2" => showDoctorStats($pdo),
            "3" => showDepartmentStats($pdo),
            "4" => showOverallStats($pdo),
            "5" => -1,
            default => 1,
        };
    } while ($repeat !== -1);

    return 0;
}

function listAllPatients(PDO $pdo): int
{
    echo "\n=== All Patients ===\n\n";
    $patient = new Patient($pdo);
    $rows = Patient::getAll($pdo);
    printTable($patient->getHeaders(), $rows);
    readline("\nPress Enter to continue...");
    return 1;
}

function searchPatient(PDO $pdo): int
{
    echo "\n=== Search Patient ===\n";
    $id = (int) trim(readline("Enter patient ID: "));

    $patient = new Patient($pdo);
    $result = $patient->getById($id);

    if (empty($result)) {
        echo "Patient not found.\n";
    } else {
        printTable($patient->getHeaders(), $result);
    }

    readline("\nPress Enter to continue...");
    return 1;
}

function addPatient(PDO $pdo): int
{
    echo "\n=== Add New Patient ===\n";

    $fName = trim(readline("First name: "));
    $lName = trim(readline("Last name: "));
    $phone = trim(readline("Phone: "));
    $email = trim(readline("Email: "));
    $genderInput = trim(readline("Gender (Male/Female/Other): "));
    $dob = trim(readline("Date of birth (YYYY-MM-DD): "));
    $address = trim(readline("Address: "));

    $gender = match (strtolower($genderInput)) {
        'male' => Genders::MALE,
        'female' => Genders::FEMALE,
        'other' => Genders::OTHER,
        default => Genders::UNSET
    };

    $patient = new Patient($pdo, null, $fName, $lName, $phone, $email, $gender, new DateTime($dob), $address);

    $data = $patient->toArray();
    unset($data['id']);
    $data['date_of_birth'] = $patient->getDOB()->format('Y-m-d');
    $data['gender'] = $patient->getGender()->value;

    $newId = $patient->add($data);

    if ($newId) {
        echo "\nPatient added successfully with ID: $newId\n";
    } else {
        echo "\nFailed to add patient.\n";
    }

    readline("\nPress Enter to continue...");
    return 1;
}

function editPatient(PDO $pdo): int
{
    echo "\n=== Edit Patient ===\n";
    $id = (int) trim(readline("Enter patient ID to edit: "));

    $patient = new Patient($pdo);
    $result = $patient->getById($id);

    if (empty($result)) {
        echo "Patient not found.\n";
        readline("\nPress Enter to continue...");
        return 1;
    }

    echo "\nCurrent patient data:\n";
    printTable($patient->getHeaders(), $result);

    echo "\nEnter new values (press Enter to keep current value):\n";

    $fName = trim(readline("First name [{$result[0][1]}]: ")) ?: $result[0][1];
    $lName = trim(readline("Last name [{$result[0][2]}]: ")) ?: $result[0][2];
    $genderInput = trim(readline("Gender [{$result[0][3]}]: ")) ?: $result[0][3];
    $dob = trim(readline("Date of birth [{$result[0][4]}]: ")) ?: $result[0][4];
    $phone = trim(readline("Phone [{$result[0][5]}]: ")) ?: $result[0][5];
    $email = trim(readline("Email [{$result[0][6]}]: ")) ?: $result[0][6];
    $address = trim(readline("Address [{$result[0][7]}]: ")) ?: $result[0][7];

    $gender = match (strtolower($genderInput)) {
        'male' => Genders::MALE,
        'female' => Genders::FEMALE,
        'other' => Genders::OTHER,
        default => Genders::UNSET
    };

    $updatedPatient = new Patient($pdo, $id, $fName, $lName, $phone, $email, $gender, new DateTime($dob), $address);

    $data = $updatedPatient->toArray();
    unset($data['id']);
    $data['date_of_birth'] = $updatedPatient->getDOB()->format('Y-m-d');
    $data['gender'] = $updatedPatient->getGender()->value;

    $success = $updatedPatient->update($id, $data);

    if ($success) {
        echo "\nPatient updated successfully.\n";
    } else {
        echo "\nFailed to update patient.\n";
    }

    readline("\nPress Enter to continue...");
    return 1;
}

function deletePatient(PDO $pdo): int
{
    echo "\n=== Delete Patient ===\n";
    $id = (int) trim(readline("Enter patient ID to delete: "));

    $patient = new Patient($pdo);
    $result = $patient->getById($id);

    if (empty($result)) {
        echo "Patient not found.\n";
        readline("\nPress Enter to continue...");
        return 1;
    }

    echo "\nPatient to delete:\n";
    printTable($patient->getHeaders(), $result);

    $confirm = trim(readline("\nAre you sure? (yes/no): "));

    if (strtolower($confirm) === 'yes') {
        $success = $patient->delete($id);

        if ($success) {
            echo "\nPatient deleted successfully.\n";
        } else {
            echo "\nFailed to delete patient.\n";
        }
    } else {
        echo "\nDeletion cancelled.\n";
    }

    readline("\nPress Enter to continue...");
    return 1;
}

function listAllDoctors(PDO $pdo): int
{
    echo "\n=== All Doctors ===\n\n";
    $doctor = new Doctor($pdo);
    $rows = Doctor::getAll($pdo);
    printTable($doctor->getHeaders(), $rows);
    readline("\nPress Enter to continue...");
    return 1;
}

function searchDoctor(PDO $pdo): int
{
    echo "\n=== Search Doctor ===\n";
    $id = (int) trim(readline("Enter doctor ID: "));

    $doctor = new Doctor($pdo);
    $result = $doctor->getById($id);

    if (empty($result)) {
        echo "Doctor not found.\n";
    } else {
        printTable($doctor->getHeaders(), $result);
    }

    readline("\nPress Enter to continue...");
    return 1;
}

function addDoctor(PDO $pdo): int
{
    echo "\n=== Add New Doctor ===\n";

    $fName = trim(readline("First name: "));
    $lName = trim(readline("Last name: "));
    $phone = trim(readline("Phone: "));
    $email = trim(readline("Email: "));
    $spec = trim(readline("Specialization: "));
    $depId = trim(readline("Department ID (or leave empty): "));
    $depId = $depId === '' ? null : (int) $depId;

    $doctor = new Doctor($pdo, null, $fName, $lName, $phone, $email, $spec, $depId);

    $data = $doctor->toArray();
    unset($data['id']);

    $newId = $doctor->add($data);

    if ($newId) {
        echo "\nDoctor added successfully with ID: $newId\n";
    } else {
        echo "\nFailed to add doctor.\n";
    }

    readline("\nPress Enter to continue...");
    return 1;
}

function editDoctor(PDO $pdo): int
{
    echo "\n=== Edit Doctor ===\n";
    $id = (int) trim(readline("Enter doctor ID to edit: "));

    $doctor = new Doctor($pdo);
    $result = $doctor->getById($id);

    if (empty($result)) {
        echo "Doctor not found.\n";
        readline("\nPress Enter to continue...");
        return 1;
    }

    echo "\nCurrent doctor data:\n";
    printTable($doctor->getHeaders(), $result);

    echo "\nEnter new values (press Enter to keep current value):\n";

    $fName = trim(readline("First name [{$result[0][1]}]: ")) ?: $result[0][1];
    $lName = trim(readline("Last name [{$result[0][2]}]: ")) ?: $result[0][2];
    $spec = trim(readline("Specialization [{$result[0][3]}]: ")) ?: $result[0][3];
    $phone = trim(readline("Phone [{$result[0][4]}]: ")) ?: $result[0][4];
    $email = trim(readline("Email [{$result[0][5]}]: ")) ?: $result[0][5];
    $depIdInput = trim(readline("Department ID [{$result[0][6]}]: "));
    $depId = $depIdInput === '' ? $result[0][6] : ($depIdInput === 'null' ? null : (int) $depIdInput);

    $updatedDoctor = new Doctor($pdo, $id, $fName, $lName, $phone, $email, $spec, $depId);

    $data = $updatedDoctor->toArray();
    unset($data['id']);

    $success = $updatedDoctor->update($id, $data);

    if ($success) {
        echo "\nDoctor updated successfully.\n";
    } else {
        echo "\nFailed to update doctor.\n";
    }

    readline("\nPress Enter to continue...");
    return 1;
}

function deleteDoctor(PDO $pdo): int
{
    echo "\n=== Delete Doctor ===\n";
    $id = (int) trim(readline("Enter doctor ID to delete: "));

    $doctor = new Doctor($pdo);
    $result = $doctor->getById($id);

    if (empty($result)) {
        echo "Doctor not found.\n";
        readline("\nPress Enter to continue...");
        return 1;
    }

    echo "\nDoctor to delete:\n";
    printTable($doctor->getHeaders(), $result);

    $confirm = trim(readline("\nAre you sure? (yes/no): "));

    if (strtolower($confirm) === 'yes') {
        $success = $doctor->delete($id);

        if ($success) {
            echo "\nDoctor deleted successfully.\n";
        } else {
            echo "\nFailed to delete doctor.\n";
        }
    } else {
        echo "\nDeletion cancelled.\n";
    }

    readline("\nPress Enter to continue...");
    return 1;
}

function listAllDepartments(PDO $pdo): int
{
    echo "\n=== All Departments ===\n\n";
    $department = new Department($pdo);
    $rows = Department::getAll($pdo);
    printTable($department->getHeaders(), $rows);
    readline("\nPress Enter to continue...");
    return 1;
}

function searchDepartment(PDO $pdo): int
{
    echo "\n=== Search Department ===\n";
    $id = (int) trim(readline("Enter department ID: "));

    $department = new Department($pdo);
    $result = $department->getById($id);

    if (empty($result)) {
        echo "Department not found.\n";
    } else {
        printTable($department->getHeaders(), $result);
    }

    readline("\nPress Enter to continue...");
    return 1;
}

function addDepartment(PDO $pdo): int
{
    echo "\n=== Add New Department ===\n";

    $name = trim(readline("Department name: "));
    $location = trim(readline("Location: "));

    $department = new Department($pdo, null, $name, $location);

    $data = $department->toArray();
    unset($data['id']);

    $newId = $department->add($data);

    if ($newId) {
        echo "\nDepartment added successfully with ID: $newId\n";
    } else {
        echo "\nFailed to add department.\n";
    }

    readline("\nPress Enter to continue...");
    return 1;
}

function editDepartment(PDO $pdo): int
{
    echo "\n=== Edit Department ===\n";
    $id = (int) trim(readline("Enter department ID to edit: "));

    $department = new Department($pdo);
    $result = $department->getById($id);

    if (empty($result)) {
        echo "Department not found.\n";
        readline("\nPress Enter to continue...");
        return 1;
    }

    echo "\nCurrent department data:\n";
    printTable($department->getHeaders(), $result);

    echo "\nEnter new values (press Enter to keep current value):\n";

    $name = trim(readline("Department name [{$result[0][1]}]: ")) ?: $result[0][1];
    $location = trim(readline("Location [{$result[0][2]}]: ")) ?: $result[0][2];

    $updatedDepartment = new Department($pdo, $id, $name, $location);

    $data = $updatedDepartment->toArray();
    unset($data['id']);

    $success = $updatedDepartment->update($id, $data);

    if ($success) {
        echo "\nDepartment updated successfully.\n";
    } else {
        echo "\nFailed to update department.\n";
    }

    readline("\nPress Enter to continue...");
    return 1;
}

function deleteDepartment(PDO $pdo): int
{
    echo "\n=== Delete Department ===\n";
    $id = (int) trim(readline("Enter department ID to delete: "));

    $department = new Department($pdo);
    $result = $department->getById($id);

    if (empty($result)) {
        echo "Department not found.\n";
        readline("\nPress Enter to continue...");
        return 1;
    }

    echo "\nDepartment to delete:\n";
    printTable($department->getHeaders(), $result);

    $confirm = trim(readline("\nAre you sure? (yes/no): "));

    if (strtolower($confirm) === 'yes') {
        $success = $department->delete($id);

        if ($success) {
            echo "\nDepartment deleted successfully.\n";
        } else {
            echo "\nFailed to delete department.\n";
        }
    } else {
        echo "\nDeletion cancelled.\n";
    }

    readline("\nPress Enter to continue...");
    return 1;
}

function showPatientStats(PDO $pdo): int
{
    echo "\n=== Patient Statistics ===\n\n";

    $patient = new Patient($pdo);
    $stats = $patient->getStatistics();

    echo "Total Patients: {$stats['total']}\n";
    echo "Male: {$stats['male']}\n";
    echo "Female: {$stats['female']}\n";
    echo "Other: {$stats['other']}\n";
    echo "Average Age: {$stats['avg_age']} years\n";

    readline("\nPress Enter to continue...");
    return 1;
}

function showDoctorStats(PDO $pdo): int
{
    echo "\n=== Doctor Statistics ===\n\n";

    $doctor = new Doctor($pdo);
    $stats = $doctor->getStatistics();

    echo "Total Doctors: {$stats['total']}\n";
    echo "Doctors with Department: {$stats['with_department']}\n";
    echo "Doctors without Department: {$stats['without_department']}\n";

    if (!empty($stats['by_specialization'])) {
        echo "\nDoctors by Specialization:\n";
        foreach ($stats['by_specialization'] as $spec => $count) {
            echo "  - {$spec}: {$count}\n";
        }
    }

    readline("\nPress Enter to continue...");
    return 1;
}

function showDepartmentStats(PDO $pdo): int
{
    echo "\n=== Department Statistics ===\n\n";

    $department = new Department($pdo);
    $stats = $department->getStatistics();

    echo "Total Departments: {$stats['total']}\n";

    if (!empty($stats['doctors_per_department'])) {
        echo "\nDoctors per Department:\n";
        foreach ($stats['doctors_per_department'] as $dept) {
            echo "  - {$dept['name']}: {$dept['count']} doctor(s)\n";
        }
    }

    readline("\nPress Enter to continue...");
    return 1;
}

function showOverallStats(PDO $pdo): int
{
    echo "\n=== Overall System Statistics ===\n\n";

    $patient = new Patient($pdo);
    $doctor = new Doctor($pdo);
    $department = new Department($pdo);

    $patientStats = $patient->getStatistics();
    $doctorStats = $doctor->getStatistics();
    $departmentStats = $department->getStatistics();

    echo "PATIENTS:\n";
    echo "  Total: {$patientStats['total']}\n";
    echo "  Male: {$patientStats['male']}\n";
    echo "  Female: {$patientStats['female']}\n";
    echo "  Other: {$patientStats['other']}\n";
    echo "  Average Age: {$patientStats['avg_age']} years\n\n";

    echo "DOCTORS:\n";
    echo "  Total: {$doctorStats['total']}\n";
    echo "  With Department: {$doctorStats['with_department']}\n";
    echo "  Without Department: {$doctorStats['without_department']}\n\n";

    echo "DEPARTMENTS:\n";
    echo "  Total: {$departmentStats['total']}\n";

    readline("\nPress Enter to continue...");
    return 1;
}

function printTable(array $headers, array $rows = []): void
{
    $widths = [];
    $colCount = count($headers);

    foreach ($headers as $i => $header) {
        $widths[$i] = strlen((string)$header);
    }

    foreach ($rows as $row) {
        $i = 0;
        foreach ($row as $cell) {
            $len = strlen((string)$cell);
            if ($len > ($widths[$i] ?? 0)) {
                $widths[$i] = $len;
            }
            $i++;
        }
    }

    $border = '-';
    foreach ($widths as $w) {
        $border .= str_repeat('-', $w + 3);
    }

    echo $border . PHP_EOL;

    $headerLine = '|';
    foreach ($headers as $i => $header) {
        $headerLine .= ' ' . str_pad((string)$header, $widths[$i], ' ') . ' |';
    }
    echo $headerLine . PHP_EOL;
    echo $border . PHP_EOL;

    foreach ($rows as $row) {
        $line = '|';
        $i = 0;
        foreach ($row as $cell) {
            $line .= ' ' . str_pad((string)$cell, $widths[$i], ' ') . ' |';
            $i++;
        }
        echo $line . PHP_EOL;
    }
    if (!empty($rows)) echo $border . PHP_EOL;
}
