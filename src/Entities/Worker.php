<?php
class Worker
{
    private string $name;
    private int $age;
    private int $id;
    private string $gender;
    private int $LV = 1;
    private int $XP = 0;


    private const XP_TO_LEVEL = 500;


    public function __construct(string $name, int $age, int $id, string $gender, int $LV = 1, int $XP = 0)
    {
        $this->name = $name;
        $this->age = $age;
        $this->id = $id;
        $this->gender = $gender;
        $this->LV = $LV;
        $this->XP = $XP;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAge(): int
    {
        return $this->age;
    }
    public function getGender(): string
    {
        return $this->gender;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function getLV(): int
    {
        return $this->LV;
    }

    public function getXP(): int
    {
        return $this->XP;
    }

      public function LVUp(): int
    {
       
        $this->LV++;
        $this->XP = 0; // reset XP after leveling up
        return $this->LV;
    }

    public function addXp(int $amount): int
{
    $levelsGained = 0;
    $this->XP += $amount;

    while ($this->XP >= self::XP_TO_LEVEL) {
        $this->XP -= self::XP_TO_LEVEL;
        $this->LV++;
        $levelsGained++;
    }

    return $levelsGained; // retourne le nb de niveaux gagnés (0 si pas de level up)
}

    
}
