<?php
class Animal
{
    private int $id;
    private string $name;
    private int $age;
    private string $type;
    private string $weight;
    private string $height;
    private bool $isHungry;
    private bool $isClean;




    public function __construct(int $id, string $name, int $age, string $type, string $weight, string $height, bool $isHungry = false, bool $isClean = false)
    {
        $this->id = $id;
        $this->name = $name;
        $this->age = $age;
        $this->type = $type;
        $this->weight = $weight;
        $this->height = $height;
        $this->isHungry = $isHungry;    
        $this->isClean = $isClean;
    }

    public function getId(): int
    {
        return $this->id;
    }

      public function getName(): string
    {
        return $this->name;
    }

      public function getAge(): int
    {
        return $this->age;
    }

      public function getType(): string
    {
        return $this->type;
    }

      public function getWeight(): string
    {
        return $this->weight;
    }

      public function getHeight(): string
    {
        return $this->height;
    }

     public function isHungry(): bool 
    {
        return $this->isHungry;
    }

       public function isClean(): bool 
    {
        return $this->isClean;
    }
    
           public function clean(): void 
    {
        $this->isClean = true;
    }

     public function feed(): void
    {
        $this->isHungry = false;
    }
       public function grandit(): void
    {
        $this->age = ++$this->age;
    }


}
