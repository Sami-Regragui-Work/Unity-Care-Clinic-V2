<?php

require_once __DIR__ . "/parent/abstract/BaseModal.php";

class Department extends BaseModal
{
    private string $name;
    private string $location;

    public function __construct(
        PDO $pdo,
        ?int $id = null,
        string $name = "",
        string $location = ""
    ) {
        parent::__construct($pdo, $id);
        $this->name = $name;
        $this->location = $location;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function getLocation(): string
    {
        return $this->location;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function getTableName(): string
    {
        return 'departments';
    }

    public static function getIds(PDO $pdo): array
    {
        static $ids = null;
        if (is_null($ids)) {
            $stmt = $pdo->prepare("SELECT id FROM departments");
            $stmt->execute();
            $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        return $ids;
    }

    public function getStatistics(): array
    {
        $pdo = $this->getPdo();

        $sql = <<<SQL
        SELECT COUNT(*) as total
        FROM departments
        SQL;

        $stmt = $pdo->query($sql);
        $result = $stmt->fetch();

        $sqlDoctors = <<<SQL
        SELECT d.name, COUNT(doc.id) as count
        FROM departments d
        LEFT JOIN doctors doc ON d.id = doc.department_id
        GROUP BY d.id, d.name
        ORDER BY count DESC
        SQL;

        $stmtDoctors = $pdo->query($sqlDoctors);
        $doctorsPerDept = [];
        while ($row = $stmtDoctors->fetch()) {
            $doctorsPerDept[] = [
                'name' => $row['name'],
                'count' => (int) $row['count']
            ];
        }

        return [
            'total' => (int) $result['total'],
            'doctors_per_department' => $doctorsPerDept
        ];
    }
}


// name VARCHAR(50) UNIQUE NOT NULL,
// location VARCHAR(100) NOT NULL