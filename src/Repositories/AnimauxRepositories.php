<?php

class AnimauxRepositories {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    

    public function save(Animal $animal) {
        $stmt = $this->pdo->prepare("
            UPDATE animaux 
            SET name = ?, age = ?, type = ?, weight = ?, height = ?, hungry = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $animal->getName(),
            $animal->getAge(),
            $animal->getType(),
            $animal->getWeight(),
            $animal->getHeight(),
            $animal->isHungry() ? 1 : 0,
            $animal->getId()
        ]);
    }
}
