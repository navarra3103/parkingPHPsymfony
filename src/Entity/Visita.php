<?php

namespace App\Entity;
use Doctrine\DBAL\Types\Types;
use App\Repository\VisitaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VisitaRepository::class)
 */
class Visita
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $IdVisita = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $entrada = null;
    
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $salida = null;

    #[ORM\ManyToOne(targetEntity: Estado::class)]
    #[ORM\JoinColumn(name: "Estado_IdEstado", referencedColumnName: "IdEstado", nullable: false)]
    private ?Estado $Estado = null;

    #[ORM\ManyToOne(targetEntity: Coche::class)]
    #[ORM\JoinColumn(name: "Coche_IdCoche", referencedColumnName: "IdCoche", nullable: false)]
    private ?Coche $Coche = null;

    #[ORM\ManyToOne(targetEntity: Plaza::class)]
    #[ORM\JoinColumn(name: "Plaza_IdPlaza", referencedColumnName: "IdPlaza", nullable: false)]
    private ?Plaza $Plaza = null;
    // Métodos getter y setter para los atributos

    public function getIdVisita(): ?int
    {
        return $this->IdVisita;
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
        return $this->Estado;
    }

    public function setEstado(?Estado $Estado): self
    {
        $this->Estado = $Estado;
        return $this;
    }

    public function getCoche(): ?Coche
    {
        return $this->Coche;
    }

    public function setCoche(?Coche $Coche): self
    {
        $this->Coche = $Coche;
        return $this;
    }

    public function getPlaza(): ?Plaza
    {
        return $this->Plaza;
    }

    public function setPlaza(?Plaza $Plaza): self
    {
        $this->Plaza = $Plaza;
        return $this;
    }
}
