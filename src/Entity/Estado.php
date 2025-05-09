<?php

namespace App\Entity;

use App\Repository\EstadoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EstadoRepository::class)]
class Estado
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $IdEstado = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $nombre;

    public function getIdEstado(): ?int
    {
        return $this->IdEstado;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }
}
