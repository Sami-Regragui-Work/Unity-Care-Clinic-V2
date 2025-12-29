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
    public function getStatistics(): array
    {
        $pdo = $this->getPdo();

        $sql = <<<SQL
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN department_id IS NOT NULL THEN 1 ELSE 0 END) as with_department,
            SUM(CASE WHEN department_id IS NULL THEN 1 ELSE 0 END) as without_department
        FROM doctors
        SQL;

        $stmt = $pdo->query($sql);
        $result = $stmt->fetch();

        $sqlSpec = <<<SQL
        SELECT specialization, COUNT(*) as count
        FROM doctors
        WHERE specialization IS NOT NULL AND specialization != ''
        GROUP BY specialization
        ORDER BY count DESC
        SQL;

        $stmtSpec = $pdo->query($sqlSpec);
        $bySpec = [];
        while ($row = $stmtSpec->fetch()) {
            $bySpec[$row['specialization']] = (int) $row['count'];
        }

        return [
            'total' => (int) $result['total'],
            'with_department' => (int) $result['with_department'],
            'without_department' => (int) $result['without_department'],
            'by_specialization' => $bySpec
        ];
    }
}

// specialization VARCHAR(50),
// department_id INT NULL