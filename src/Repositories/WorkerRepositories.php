<?php
class WorkerRepositories
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Worker $worker): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO workers (name, age, gender, LV, XP) VALUES (:name, :age, :gender, :LV, :XP)"
        );
        $stmt->execute([
            ':name' => $worker->getName(),
            ':age'   => $worker->getAge(),
            ':gender'   => $worker->getGender(),
            ':LV' => $worker->getLV(),
            ':XP' => $worker->getXP(),
        ]);
    }

    public function find(int $id): ?Worker
    {
        $stmt = $this->pdo->prepare("SELECT * FROM workers WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $name = $row['name'] ?? $row['NAME'] ?? '';
            $age = (int)($row['age'] ?? $row['AGE'] ?? 0);
            $id  = (int)($row['id'] ?? $row['ID'] ?? 0);
            $gender = $row['gender'] ?? $row['GENDER'] ?? '';
            $LV = (int)($row['LV'] ?? $row['lv'] ?? 0);
            $XP = (int)($row['XP'] ?? $row['xp'] ?? 0);

            return new Worker($name, $age, $id, $gender, $LV, $XP);
        }

        return null;
    }

    public function update(int $id, Worker $worker): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE workers SET name = :name, age = :age, gender = :gender, LV= :LV, XP = :XP WHERE id = :id"
        );

        $stmt->execute([
            ':name' => $worker->getName(),
            ':age'   => $worker->getAge(),
            ':gender' => $worker->getGender(),
            ':LV' => $worker->getLV(),
            ':XP' => $worker->getXP(),
            ':id' => $id
        ]);
    }
}
