<?php

namespace App\Entity;

use App\Repository\PlazaRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Tipo;

#[ORM\Entity(repositoryClass: PlazaRepository::class)]
class Plaza
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "IdPlaza", type: "integer")]
    private ?int $idPlaza = null;

    #[ORM\ManyToOne(targetEntity: Tipo::class)]
    #[ORM\JoinColumn(name: "IdTipo", referencedColumnName: "IdTipo", nullable: false)]
    private ?Tipo $tipo = null;

    public function getIdPlaza(): ?int
    {
        return $this->idPlaza;
    }

    public function getTipo(): ?Tipo
    {
        return $this->tipo;
    }

    public function setTipo(?Tipo $tipo): self
    {
        $this->tipo = $tipo;
        return $this;
    }
}
