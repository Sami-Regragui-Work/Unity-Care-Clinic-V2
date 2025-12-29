-- headers
SELECT COLUMN_NAME
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'UCCV2'
AND TABLE_NAME = :tableName
ORDER BY ORDINAL_POSITION

-- delete
DELETE FROM {$tableName} 
WHERE {$idColName} = :id

-- insert a patient
INSERT INTO `patients` ( first_name, last_name, gender, date_of_birth, phone, email, address )
VALUES ("test", "test", "Other", "2025-01-01", "0611223344", "test@test.com", "test")