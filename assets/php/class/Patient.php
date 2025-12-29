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

    public function getStatistics(): array
    {
        $pdo = $this->getPdo();

        $sql = <<<SQL
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN gender = 'Male' THEN 1 ELSE 0 END) as male,
            SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) as female,
            SUM(CASE WHEN gender = 'Other' THEN 1 ELSE 0 END) as other,
            ROUND(AVG(TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())), 1) as avg_age
        FROM patients
        SQL;

        $stmt = $pdo->query($sql);
        $result = $stmt->fetch();

        return [
            'total' => (int) $result['total'],
            'male' => (int) $result['male'],
            'female' => (int) $result['female'],
            'other' => (int) $result['other'],
            'avg_age' => (float) $result['avg_age']
        ];
    }
}

// gender ENUM('Male', 'Female', 'Other'),
// date_of_birth DATE,
// address VARCHAR(255)