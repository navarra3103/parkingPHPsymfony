<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\HistoricoRepository;
use App\Entity\Coche;
use App\Entity\Estado;
use App\Entity\Plaza;

#[ORM\Entity(repositoryClass: HistoricoRepository::class)]
#[ORM\Table(name: "Historico")]
class Historico
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Coche::class)]
    #[ORM\JoinColumn(name: "Coche", referencedColumnName: "Matricula", nullable: false)]
    private ?Coche $coche = null;

    #[ORM\ManyToOne(targetEntity: Plaza::class)]
    #[ORM\JoinColumn(name: "Plaza", referencedColumnName: "IdPlaza", nullable: false)]
    private ?Plaza $plaza = null;

    #[ORM\Column(name: "Salida", type: Types::DATETIME_MUTABLE, nullable: false)]
    private ?\DateTimeInterface $salida = null;

    #[ORM\Column(name: "Entrada", type: Types::DATETIME_MUTABLE, nullable: false)]
    private ?\DateTimeInterface $entrada = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoche(): ?Coche
    {
        return $this->coche;
    }

    public function setCoche(?Coche $coche): self
    {
        $this->coche = $coche;
        return $this;
    }

    public function getPlaza(): ?Plaza
    {
        return $this->plaza;
    }

    public function setPlaza(?Plaza $plaza): self
    {
        $this->plaza = $plaza;
        return $this;
    }

    public function getEstado(): ?Estado
    {
        return $this->estado;
    }

    public function setEstado(?Estado $estado): self
    {
        $this->estado = $estado;
        return $this;
    }

    public function getSalida(): ?\DateTimeInterface
    {
        return $this->salida;
    }

    public function setSalida(?\DateTimeInterface $salida): self
    {
        $this->salida = $salida;
        return $this;
    }

    public function getEntrada(): ?\DateTimeInterface
    {
        return $this->entrada;
    }

    public function setEntrada(?\DateTimeInterface $entrada): self
    {
        $this->entrada = $entrada;
        return $this;
    }
}
