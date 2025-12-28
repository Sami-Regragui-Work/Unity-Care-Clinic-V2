<?php

require_once __DIR__ . "/parent/abstract/Person.php";
require_once __DIR__ . "/Department.php";

class Doctor extends Person
{
    private string $spec;
    private ?int $depId;

    public function __construct(
        PDO $pdo,
        ?int $id = null,
        string $fName = "",
        string $lName = "",
        string $phone = "",
        string $email = "",
        string $spec = "",
        ?int $depId = null
    ) {
        parent::__construct($pdo, $id, $fName, $lName, $phone, $email);
        $this->spec = $spec;
        $this->setForeignId($pdo, $depId);
    }

    public function getSpec(): string
    {
        return $this->spec;
    }
    public function getDepId(): ?int
    {
        return $this->depId;
    }

    public function setSpec(string $spec): void
    {
        $this->spec = $spec;
    }
    public function setDepId(?int $depId): void
    {
        $this->depId = $depId;
    }

    public function getTableName(): string
    {
        return 'doctors';
    }

    private function setForeignId(PDO $pdo, ?int $depId): void
    {
        $this->depId = in_array($depId, Department::getIds($pdo)) ? $depId : null;
    }
}

// specialization VARCHAR(50),
// department_id INT NULL