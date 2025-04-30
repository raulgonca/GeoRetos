<?php

namespace App\Entity;

use App\Repository\ResultadoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResultadoRepository::class)]
class Resultado
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'resultados')]
    private ?User $id_usuario = null;

    #[ORM\ManyToOne(inversedBy: 'resultados')]
    private ?Aventura $id_aventura = null;

    #[ORM\Column]
    private ?int $puntos = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column]
    private ?bool $nombre_publico = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUsuario(): ?User
    {
        return $this->id_usuario;
    }

    public function setIdUsuario(?User $id_usuario): static
    {
        $this->id_usuario = $id_usuario;

        return $this;
    }

    public function getIdAventura(): ?Aventura
    {
        return $this->id_aventura;
    }

    public function setIdAventura(?Aventura $id_aventura): static
    {
        $this->id_aventura = $id_aventura;

        return $this;
    }

    public function getPuntos(): ?int
    {
        return $this->puntos;
    }

    public function setPuntos(int $puntos): static
    {
        $this->puntos = $puntos;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function isNombrePublico(): ?bool
    {
        return $this->nombre_publico;
    }

    public function setNombrePublico(bool $nombre_publico): static
    {
        $this->nombre_publico = $nombre_publico;

        return $this;
    }
}
