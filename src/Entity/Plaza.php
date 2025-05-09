<?php

namespace App\Entity;

use App\Repository\PlazaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlazaRepository::class)]
class Plaza
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $idPlaza = null;

    #[ORM\ManyToOne(targetEntity: Tipo::class)]
    #[ORM\JoinColumn(name: 'IdTipo', referencedColumnName: 'IdTipo', nullable: false)]
    private ?Tipo $IdTipo = null;

    public function getIdPlaza(): ?int
    {
        return $this->idPlaza;
    }

    public function getIdTipo(): ?Tipo
    {
        return $this->IdTipo;
    }

    public function setIdTipo(?Tipo $IdTipo): self
    {
        $this->IdTipo = $IdTipo;
        return $this;
    }
}
