<?php

require_once __DIR__ . "/parent/abstract/Person.php";

enum Genders: string
{
    case MALE = "Male";
    case FEMALE = "Female";
    case OTHER = "Other";
    case UNSET = "";
}

class Patient extends Person
{
    private Genders $gender;
    private DateTime $dOB;
    private string $address;

    public function __construct(
        PDO $pdo,
        ?int $id = null,
        string $fName = "",
        string $lName = "",
        string $phone = "",
        string $email = "",
        Genders $gender = Genders::UNSET,
        DateTime $dOB = new DateTime(),
        string $address = ""
    ) {
        parent::__construct($pdo, $id, $fName, $lName, $phone, $email);
        $this->gender = $gender;
        $this->dOB = $dOB;
        $this->address = $address;
    }

    public function getGender(): Genders
    {
        return $this->gender;
    }
    public function getDOB(): DateTime
    {
        return $this->dOB;
    }
    public function getAddress(): string
    {
        return $this->address;
    }

    public function setGender(Genders $gender): void
    {
        $this->gender = $gender;
    }
    public function setDOB(DateTime $dOB): void
    {
        $this->dOB = $dOB;
    }
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getTableName(): string
    {
        return 'patients';
    }
}

// gender ENUM('Male', 'Female', 'Other'),
// date_of_birth DATE,
// address VARCHAR(255)