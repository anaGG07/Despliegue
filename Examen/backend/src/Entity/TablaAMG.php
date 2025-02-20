<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "secretosAMG")]
class TablaAMG
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(name: "frase_amg", type: "string", length: 255)]
    private string $fraseAmg; 
    
    public function getId(): int
    {
        return $this->id;
    }

    public function getFraseAmg(): string
    {
        return $this->fraseAmg;
    }

    public function setFraseAmg(string $fraseAmg): self
    {
        $this->fraseAmg = $fraseAmg;
        return $this;
    }
}
