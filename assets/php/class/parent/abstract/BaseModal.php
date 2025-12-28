<?php

abstract class BaseModal
{
    // private readonly string $tableName;
    private readonly array $headers;
    private readonly string $idColName;

    private readonly PDO $pdo;
    private ?int $id;

    public function __construct(PDO $pdo, ?int $id = null)
    {
        $this->pdo = $pdo;
        $this->id = $id;
        // $this->tableName = $this->getValidTableName();
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
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":tableName", $tableName, PDO::PARAM_STR);
        if ($stmt->execute()) {
            return array_values($stmt->fetchAll(PDO::FETCH_COLUMN));
        } else {
            throw new Exception("\n" . $stmt->errorInfo()[2] . "\n");
        }
    }

    // public function save()
    // {
    //     return; //success?
    // }
    // public function delete()
    // {
    //     return; //success?
    // }
    // public function findById($headers)
    // {
    //     return; //row or null
    // }
}
