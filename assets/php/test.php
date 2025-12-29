<?php

include __DIR__ . "/class/Doctor.php";
include __DIR__ . "/class/Patient.php";
require_once __DIR__ . "/class/config/DataBase.php";

function printTable(array $headers, array $rows = []): void
{
    // hadi Ai

    // 1) Compute column widths (max of header/data)
    $widths = [];
    $colCount = count($headers);

    // Initialize from headers
    foreach ($headers as $i => $header) {
        $widths[$i] = strlen((string)$header);
    }

    // Update with data
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

    // 2) Build horizontal border: all '-' with exact width per column
    // For each column: 1 space + content width + 1 space
    $border = '-';
    foreach ($widths as $w) {
        $border .= str_repeat('-', $w + 3);
    }

    // 3) Print header
    echo $border . PHP_EOL;

    $headerLine = '|';
    foreach ($headers as $i => $header) {
        // one leading space, padded content, one trailing space
        $headerLine .= ' ' . str_pad((string)$header, $widths[$i], ' ') . ' |';
    }
    echo $headerLine . PHP_EOL;
    echo $border . PHP_EOL;

    // 4) Print rows
    foreach ($rows as $row) {
        $line = '|';
        $i = 0;
        foreach ($row as $cell) {
            $line .= ' ' . str_pad((string)$cell, $widths[$i], ' ') . ' |';
            $i++;
        }
        echo $line . PHP_EOL;
    }
    if (!empty($row)) echo $border . PHP_EOL;
}





$db = DataBase::getTheOnlyDB();
$pdo = $db->getPdo();

// $doctor = new Doctor($pdo, depId: 10);

// $isItNull = $doctor->getDepId() == null;

// echo $isItNull . PHP_EOL;

// $sql = <<<SQL
// INSERT INTO `patients` ( first_name, last_name, gender, date_of_birth, phone, email, address )
// VALUES ("test", "test", "Other", "2025-01-01", "0611223344", "test@test.com", "test")
// SQL;
// $pdo->query($sql);
// $id = (int) $pdo->lastInsertId();

// $id = 31;

$patient = new Patient($pdo);

// echo "$id\n";
// echo $patient->delete($id) . "\n";
// var_dump($patient->getHeaders());

// $assocArr = Patient::getAll($pdo);
// printTable($patient->getHeaders(), $assocArr);
printTable($patient->getHeaders(), $patient->getById(2));
// var_dump($assocArr);

// printTable($patient->getHeaders());
// echo implode(" | ", $patient->getHeaders());
