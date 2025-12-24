<?php

require_once "assets/php/dbLink.php";

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

    $choice0 = htmlspecialchars(readline(), ENT_QUOTES, "UTF-8");

    $repeat0 = match ($choice0) {
        "1" => patientMenu(),
        "2" => doctorMenu(),
        "3" => departmentMenu(),
        "4" => statisticsMenu(),
        "5" => -1,
        default => 1,
    };
} while ($repeat0 > 0);

echo "Thank you for using the Hospital Management System. Goodbye!\n\n";

// functions

function patientMenu()
{
    do {
        // system("clear");
        echo <<<BASH
=== Patient Management ===
1. List all patients
2. Search for a patient
3. Add a patient
4. Edit a patient
5. Delete a patient
6. Back
BASH;

        $choice = htmlspecialchars(readline(), ENT_QUOTES, "UTF-8");

        $repeat = match ($choice) {
            "1" => executePatientAction("1"),
            "2" => executePatientAction("2"),
            "3" => executePatientAction("3"),
            "4" => executePatientAction("4"),
            "5" => executePatientAction("5"),
            "6" => -1,
            default => 1,
        };
    } while ($repeat > 0);

    return 0;
}

function doctorMenu()
{
    do {
        // system("clear");
        echo <<<BASH
=== Doctor Management ===
1. List all doctors
2. Search for a doctor
3. Add a doctor
4. Edit a doctor
5. Delete a doctor
6. Back
BASH;

        $choice = htmlspecialchars(readline(), ENT_QUOTES, "UTF-8");

        $repeat = match ($choice) {
            "1" => executeDoctorAction("1"),
            "2" => executeDoctorAction("2"),
            "3" => executeDoctorAction("3"),
            "4" => executeDoctorAction("4"),
            "5" => executeDoctorAction("5"),
            "6" => -1,
            default => 1,
        };
    } while ($repeat > 0);

    return 0;
}

function departmentMenu()
{
    do {
        // system("clear");
        echo <<<BASH
=== Department Management ===
1. List all departments
2. Search for a department
3. Add a department
4. Edit a department
5. Delete a department
6. Back
BASH;

        $choice = htmlspecialchars(readline(), ENT_QUOTES, "UTF-8");

        $repeat = match ($choice) {
            "1" => executeDepartmentAction("1"),
            "2" => executeDepartmentAction("2"),
            "3" => executeDepartmentAction("3"),
            "4" => executeDepartmentAction("4"),
            "5" => executeDepartmentAction("5"),
            "6" => -1,
            default => 1,
        };
    } while ($repeat > 0);

    return 0;
}

function statisticsMenu()
{
    do {
        // system("clear");
        echo <<<BASH
=== Statistics ===
1. Patient statistics
2. Doctor statistics
3. Department statistics
4. Overall statistics
5. Back
BASH;

        $choice = htmlspecialchars(readline(), ENT_QUOTES, "UTF-8");

        $repeat = match ($choice) {
            "1" => executeStatisticsAction("1"),
            "2" => executeStatisticsAction("2"),
            "3" => executeStatisticsAction("3"),
            "4" => executeStatisticsAction("4"),
            "5" => -1,
            default => 1,
        };
    } while ($repeat > 0);

    return 0;
}

// Action stubs - replace with real implementations
function executePatientAction($action)
{
    echo match ($action) {
        "1" => "Listing all patients...\n",
        "2" => "Searching for a patient...\n",
        "3" => "Adding a new patient...\n",
        "4" => "Editing a patient...\n",
        "5" => "Deleting a patient...\n",
        default => "Patient action not implemented.\n",
    } . "\n\n";
    readline("Press Enter to continue...");  // Pause
    return 1;
}

function executeDoctorAction($action)
{
    echo match ($action) {
        "1" => "Listing all doctors...\n",
        "2" => "Searching for a doctor...\n",
        "3" => "Adding a new doctor...\n",
        "4" => "Editing a doctor...\n",
        "5" => "Deleting a doctor...\n",
        default => "Doctor action not implemented.\n",
    } . "\n\n";
    readline("Press Enter to continue...");  // Pause
    return 1;
}

function executeDepartmentAction($action)
{
    echo match ($action) {
        "1" => "Listing all departments...\n",
        "2" => "Searching for a department...\n",
        "3" => "Adding a new department...\n",
        "4" => "Editing a department...\n",
        "5" => "Deleting a department...\n",
        default => "Department action not implemented.\n",
    } . "\n\n";
    readline("Press Enter to continue...");  // Pause
    return 1;
}

function executeStatisticsAction($action)
{
    echo match ($action) {
        "1" => "Patient statistics...\n",
        "2" => "Doctor statistics...\n",
        "3" => "Department statistics...\n",
        "4" => "Overall statistics...\n",
        default => "Statistics not implemented.\n",
    } . "\n\n";
    readline("Press Enter to continue...");  // Pause
    return 1;
}
