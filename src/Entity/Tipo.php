<?php

namespace App\Entity;

use App\Repository\TipoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TipoRepository::class)]
class Tipo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "IdTipo", type: "integer")]
    private ?int $idTipo = null;

    #[ORM\Column(name: "Nombre", type: "string", length: 255)]
    private ?string $nombre = null;

    public function getIdTipo(): ?int
    {
        return $this->idTipo;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }
}
