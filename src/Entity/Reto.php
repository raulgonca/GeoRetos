<?php

namespace App\Entity;

use App\Repository\RetoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RetoRepository::class)]
class Reto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'retos')]
    private ?Aventura $id_aventura = null;

    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $instrucciones = null;

    #[ORM\Column(length: 255)]
    private ?string $tipo_reto = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagen_reto = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $respuestas = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $respuesta_correcta = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $latitud = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $longitud = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $margen_error_metros = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $puntos_fallo_0 = 100;

    #[ORM\Column(type: Types::INTEGER)]
    private int $puntos_fallo_1 = 50;

    #[ORM\Column(type: Types::INTEGER)]
    private int $puntos_fallo_2 = 25;

    #[ORM\Column(type: Types::INTEGER)]
    private int $puntos_fallo_3 = 10;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $es_obligatorio_superar = true;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $creado;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $actualizado;

    public function __construct()
    {
        $this->creado = new \DateTime();
        $this->actualizado = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function actualizarFecha()
    {
        $this->actualizado = new \DateTime();
    }

    // Getters y setters abajo

    public function getId(): ?int { return $this->id; }

    public function getIdAventura(): ?Aventura { return $this->id_aventura; }

    public function setIdAventura(?Aventura $id_aventura): static {
        $this->id_aventura = $id_aventura;
        return $this;
    }

    public function getTitulo(): ?string { return $this->titulo; }

    public function setTitulo(string $titulo): static {
        $this->titulo = $titulo;
        return $this;
    }

    public function getDescripcion(): ?string { return $this->descripcion; }

    public function setDescripcion(string $descripcion): static {
        $this->descripcion = $descripcion;
        return $this;
    }

    public function getInstrucciones(): ?string { return $this->instrucciones; }

    public function setInstrucciones(?string $instrucciones): static {
        $this->instrucciones = $instrucciones;
        return $this;
    }

    public function getTipoReto(): ?string { return $this->tipo_reto; }

    public function setTipoReto(string $tipo_reto): static {
        $this->tipo_reto = $tipo_reto;
        return $this;
    }

    public function getImagenReto(): ?string { return $this->imagen_reto; }

    public function setImagenReto(?string $imagen_reto): static {
        $this->imagen_reto = $imagen_reto;
        return $this;
    }

    public function getRespuestas(): ?array { return $this->respuestas; }

    public function setRespuestas(?array $respuestas): static {
        $this->respuestas = $respuestas;
        return $this;
    }

    public function getRespuestaCorrecta(): ?string { return $this->respuesta_correcta; }

    public function setRespuestaCorrecta(?string $respuesta_correcta): static {
        $this->respuesta_correcta = $respuesta_correcta;
        return $this;
    }

    public function getLatitud(): ?float { return $this->latitud; }

    public function setLatitud(?float $latitud): static {
        $this->latitud = $latitud;
        return $this;
    }

    public function getLongitud(): ?float { return $this->longitud; }

    public function setLongitud(?float $longitud): static {
        $this->longitud = $longitud;
        return $this;
    }

    public function getMargenErrorMetros(): ?float { return $this->margen_error_metros; }

    public function setMargenErrorMetros(?float $margen_error_metros): static {
        $this->margen_error_metros = $margen_error_metros;
        return $this;
    }

    public function getPuntosFallo0(): int { return $this->puntos_fallo_0; }

    public function setPuntosFallo0(int $puntos): static {
        $this->puntos_fallo_0 = $puntos;
        return $this;
    }

    public function getPuntosFallo1(): int { return $this->puntos_fallo_1; }

    public function setPuntosFallo1(int $puntos): static {
        $this->puntos_fallo_1 = $puntos;
        return $this;
    }

    public function getPuntosFallo2(): int { return $this->puntos_fallo_2; }

    public function setPuntosFallo2(int $puntos): static {
        $this->puntos_fallo_2 = $puntos;
        return $this;
    }

    public function getPuntosFallo3(): int { return $this->puntos_fallo_3; }

    public function setPuntosFallo3(int $puntos): static {
        $this->puntos_fallo_3 = $puntos;
        return $this;
    }

    public function isEsObligatorioSuperar(): bool { return $this->es_obligatorio_superar; }

    public function setEsObligatorioSuperar(bool $es_obligatorio): static {
        $this->es_obligatorio_superar = $es_obligatorio;
        return $this;
    }

    public function getCreado(): \DateTimeInterface { return $this->creado; }

    public function setCreado(\DateTimeInterface $creado): static {
        $this->creado = $creado;
        return $this;
    }

    public function getActualizado(): \DateTimeInterface { return $this->actualizado; }

    public function setActualizado(\DateTimeInterface $actualizado): static {
        $this->actualizado = $actualizado;
        return $this;
    }
}
