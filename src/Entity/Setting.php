<?php

namespace App\Entity;

use App\Repository\SettingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SettingRepository::class)]
class Setting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'boolean')]
    private bool $login = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isLogin(): bool
    {
        return $this->login;
    }

    public function setLogin(bool $login): self
    {
        $this->login = $login;

        return $this;
    }
}
