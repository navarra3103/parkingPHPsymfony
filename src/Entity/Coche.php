<?php

namespace App\Entity;

use App\Repository\CocheRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CocheRepository::class)]
class Coche
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $IdCoche = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $Matricula;

    public function getIdCoche(): ?int
    {
        return $this->IdCoche;
    }

    public function getMatricula(): string
    {
        return $this->Matricula;
    }

    public function setMatricula(string $Matricula): self
    {
        $this->Matricula = $Matricula;

        return $this;
    }
}
