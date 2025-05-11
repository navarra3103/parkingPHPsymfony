<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use App\Repository\VisitaRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Estado;

#[ORM\Entity(repositoryClass: VisitaRepository::class)]
class Visita
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $entrada = null;
    
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]    
    private ?\DateTimeInterface $salida = null;

    #[ORM\ManyToOne(targetEntity: Estado::class)]
    #[ORM\JoinColumn(name: "Estado_IdEstado", referencedColumnName: "IdEstado", nullable: false)]
    private ?Estado $estado = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Coche::class)]
    #[ORM\JoinColumn(name: "Coche_IdCoche", referencedColumnName: "IdCoche", nullable: false)]
    private ?Coche $coche = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Plaza::class)]
    #[ORM\JoinColumn(name: "Plaza_IdPlaza", referencedColumnName: "IdPlaza", nullable: false)]
    private ?Plaza $plaza = null;

    // Métodos getter y setter

    public function getEntrada(): ?\DateTimeInterface
    {
        return $this->entrada;
    }

    public function setEntrada(?\DateTimeInterface $entrada): self
    {
        $this->entrada = $entrada;
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

    public function getEstado(): ?Estado
    {
        return $this->estado;
    }

    public function setEstado(?Estado $estado): self
    {
        $this->estado = $estado;
        return $this;
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
}