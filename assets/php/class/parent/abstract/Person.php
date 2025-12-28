<?php
include __DIR__ . "/BaseModal.php";

abstract class Person extends BaseModal
{
    private string $fName;
    private string $lName;
    private string $phone;
    private string $email;

    public function __construct(
        PDO $pdo,
        ?int $id = null,
        string $fName = "",
        string $lName = "",
        string $phone = "",
        string $email = ""
    ) {
        parent::__construct($pdo, $id);
        $this->fName = $fName;
        $this->lName = $lName;
        $this->phone = $phone;
        $this->email = $email;
    }

    public function getFName(): string
    {
        return $this->fName;
    }
    public function getLName(): string
    {
        return $this->lName;
    }
    public function getPhone(): string
    {
        return $this->phone;
    }
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setFName(string $fName): void
    {
        $this->fName = $fName;
    }
    public function setLName(string $lName): void
    {
        $this->lName = $lName;
    }
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}


// id INT PRIMARY KEY AUTO_INCREMENT,
// first_name VARCHAR(50) NOT NULL,
// last_name VARCHAR(50) NOT NULL,
// gender ENUM('Male', 'Female', 'Other'),
// date_of_birth DATE,
// phone VARCHAR(15) UNIQUE NOT NULL,
// email VARCHAR(100) UNIQUE,
// address VARCHAR(255)

// id INT PRIMARY KEY AUTO_INCREMENT,
// first_name VARCHAR(50) NOT NULL,
// last_name VARCHAR(50) NOT NULL,
// specialization VARCHAR(50),
// phone VARCHAR(15) UNIQUE NOT NULL,
// email VARCHAR(100) UNIQUE NOT NULL,
// department_id INT NULL
