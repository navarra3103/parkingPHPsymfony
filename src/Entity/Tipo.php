<?php

namespace App\Entity;

use App\Repository\TipoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TipoRepository::class)]
class Tipo
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')] 
    #[ORM\Column(type: 'integer')]
    private ?int $IdTipo = null;

    #[ORM\Column(type: 'string', length: 255)] 
    private string $nombre;

    public function getIdTipo(): ?int
    {
        return $this->IdTipo;
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
