<?php

namespace App\Entity;

use App\Repository\EstadoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EstadoRepository::class)]
#[ORM\Table(name: "Estado")]
class Estado
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "IdEstado", type: 'integer')]
    private ?int $IdEstado = null;

    #[ORM\Column(name: "Nombre", type: 'string', length: 255)]
    private string $Nombre;

    public function getIdEstado(): ?int
    {
        return $this->IdEstado;
    }

    public function getNombre(): string
    {
        return $this->Nombre;
    }

    public function setNombre(string $Nombre): self
    {
        $this->Nombre = $Nombre;
        return $this;
    }
}
