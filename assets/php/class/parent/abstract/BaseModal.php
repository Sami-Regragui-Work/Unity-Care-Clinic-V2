<?php

abstract class BaseModal
{
    private readonly array $headers;
    private readonly string $idColName;

    private readonly PDO $pdo;
    private ?int $id;

    public function __construct(PDO $pdo, ?int $id = null)
    {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->headers = $this->fetchHeaders();
        $this->idColName = $this->getHeaders()[0];
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    protected function getIdColName(): string
    {
        return $this->idColName;
    }

    protected function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    protected function getPdo(): PDO
    {
        return $this->pdo;
    }

    abstract protected function getTableName(): string;

    private function getValidTableName(): string
    {
        $allowedTables = ['patients', 'doctors', 'departments'];
        $tableName = $this->getTableName();
        return in_array($tableName, $allowedTables) ? $tableName  : "";
    }

    private function fetchHeaders(): array
    {
        $tableName = $this->getValidTableName();
        $sql = <<<SQL
        SELECT COLUMN_NAME
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = 'UCCV2'
        AND TABLE_NAME = :tableName
        ORDER BY ORDINAL_POSITION
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":tableName", $tableName, PDO::PARAM_STR);
        if ($stmt->execute()) {
            return array_values($stmt->fetchAll(PDO::FETCH_COLUMN));
        } else {
            throw new Exception("\n" . $stmt->errorInfo()[2] . "\n");
        }
    }

    public function delete(int $id): bool
    {
        $tableName = $this->getValidTableName();
        $idColName = $this->getIdColName();
        $sql = <<<SQL
        DELETE FROM {$tableName}
        WHERE {$idColName} = :id
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        return $stmt->execute() && $stmt->rowCount() > 0;
    }

    public static function getAll(PDO $pdo): array
    {
        $obj = new static($pdo);
        $tableName = $obj->getValidTableName();

        $sql = <<<SQL
        SELECT *
        FROM {$tableName}
        SQL;
        $stmt = $pdo->query($sql);
        if ($stmt->execute()) {
            $arr = [];
            while ($row = $stmt->fetch()) {
                $arr[] = array_values($row);
            }
            return $arr;
        } else {
            throw new Exception("\n" . $stmt->errorInfo()[2] . "\n");
        }
    }

    public function getById(int $id): array
    {
        $tableName = $this->getValidTableName();
        $idColName = $this->getIdColName();

        $sql = <<<SQL
        SELECT *
        FROM {$tableName}
        WHERE {$idColName} = :id
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $result = $stmt->fetch();
            return $result ? [array_values($result)] : [];
        } else {
            throw new Exception("\n" . $stmt->errorInfo()[2] . "\n");
        }
    }

    public function add(array $assocRow): int | false
    {
        $tableName = $this->getValidTableName();

        $pHsArr = array_fill(0, count($assocRow), '?');
        $phsStr = implode(", ", $pHsArr);

        $headersStr = implode(", ", array_slice($this->getHeaders(), 1));

        $sql = <<<SQL
        INSERT INTO {$tableName} ($headersStr)
        VALUES ($phsStr)
        SQL;

        $stmt = $this->pdo->prepare($sql);
        if ($stmt->execute(array_values($assocRow)))
            return (int) $this->pdo->lastInsertId();
        return false;
    }

    public function update(int $id, array $assocRow): bool
    {
        $tableName = $this->getValidTableName();
        $idColName = $this->getIdColName();

        $headersArr = array_slice($this->getHeaders(), 1);

        $setArr = array_map(fn($header) => "$header = ?", $headersArr);
        $setStr = implode(", ", $setArr);

        $sql = <<<SQL
        UPDATE {$tableName}
        SET {$setStr}
        WHERE {$idColName} = ?
        SQL;

        $stmt = $this->pdo->prepare($sql);

        $values = array_values($assocRow);
        $values[] = $id;

        return $stmt->execute($values) && $stmt->rowCount() > 0;
    }

    public function toArray(): array
    {
        $data = [];
        $reflection = new ReflectionObject($this);

        foreach ($reflection->getProperties() as $property) {
            $propName = $property->getName();

            if (in_array($propName, ['headers', 'idColName', 'pdo'])) {
                continue;
            }

            $propName = match ($propName) {
                'fName' => 'first_name',
                'lName' => 'last_name',
                'dOB' => 'date_of_birth',
                'spec' => 'specialization',
                'depId' => 'department_id',
                default => $propName
            };

            $data[$propName] = $property->getValue($this);
        }

        return $data;
    }

    public function fromArray(array $data): self
    {
        $reflection = new ReflectionObject($this);

        foreach ($data as $header => $value) {

            $propName = match ($header) {
                'first_name' => 'fName',
                'last_name' => 'lName',
                'date_of_birth' => 'dOB',
                'specialization' => 'spec',
                'department_id' => 'depId',
                default => $header
            };

            if ($property = $reflection->getProperty($propName)) {
                $property->setValue($this, $value);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        $data = $this->toArray();
        $lines = [];

        foreach ($data as $key => $value) {
            if ($value instanceof DateTime) {
                $value = $value->format('Y-m-d');
            }
            $lines[] = ucfirst(str_replace('_', ' ', $key)) . ': ' . $value;
        }

        return implode("\n", $lines);
    }

    abstract public function getStatistics(): array;
}
