<?php

namespace App\Entity;

use App\Repository\CocheRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CocheRepository::class)]
#[ORM\Table(name: "Coche")]
class Coche
{
    #[ORM\Id]
    #[ORM\Column(name: "Matricula", type: 'string', length: 20)]
    private string $matricula;

    #[ORM\Column(name: "Marca", type: 'string', length: 100, nullable: true)]
    private ?string $marca = null;

    #[ORM\Column(name: "Modelo", type: 'string', length: 100, nullable: true)]
    private ?string $modelo = null;

    #[ORM\Column(name: "Color", type: 'string', length: 50, nullable: true)]
    private ?string $color = null;

    public function getMatricula(): string
    {
        return $this->matricula;
    }

    public function setMatricula(string $matricula): self
    {
        $this->matricula = $matricula;
        return $this;
    }

    public function getMarca(): ?string
    {
        return $this->marca;
    }

    public function setMarca(?string $marca): self
    {
        $this->marca = $marca;
        return $this;
    }

    public function getModelo(): ?string
    {
        return $this->modelo;
    }

    public function setModelo(?string $modelo): self
    {
        $this->modelo = $modelo;
        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;
        return $this;
    }
}