<?php

namespace App\Entity;
use Doctrine\DBAL\Types\Types;

use App\Repository\VisitaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Vista
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $IdVisita = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\ManyToOne(targetEntity: Estado::class)]
    #[ORM\JoinColumn(name: 'Estado_IdEstado', referencedColumnName: 'IdEstado', nullable: false)]
    private ?Estado $Estado = null;

    #[ORM\ManyToOne(targetEntity: Coche::class)]
    #[ORM\JoinColumn(name: 'Matricula', referencedColumnName: 'Matricula', nullable: false)]
    private ?Coche $Matricula = null;

    #[ORM\ManyToOne(targetEntity: Plaza::class)]
    #[ORM\JoinColumn(name: 'Plaza_IdPlaza', referencedColumnName: 'IdPlaza', nullable: false)]
    private ?Plaza $Plaza = null;

    public function getIdVisita(): ?int
    {
        return $this->IdVisita;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(?\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

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

    public function getMatricula(): ?Coche
    {
        return $this->Matricula;
    }

    public function setMatricula(?Coche $Matricula): self
    {
        $this->Matricula = $Matricula;

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
