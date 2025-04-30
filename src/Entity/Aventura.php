<?php

namespace App\Entity;

use App\Repository\AventuraRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AventuraRepository::class)]
class Aventura
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descripcion = null;

    #[ORM\Column(length: 255)]
    private ?string $imagen_portada = null;

    #[ORM\Column(nullable: true)]
    private ?int $numero_de_retos = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creado_en = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?string $actualizado_en = null;

    /**
     * @var Collection<int, Reto>
     */
    #[ORM\OneToMany(targetEntity: Reto::class, mappedBy: 'id_aventura')]
    private Collection $retos;

    /**
     * @var Collection<int, Resultado>
     */
    #[ORM\OneToMany(targetEntity: Resultado::class, mappedBy: 'id_aventura')]
    private Collection $resultados;

    public function __construct()
    {
        $this->retos = new ArrayCollection();
        $this->resultados = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getImagenPortada(): ?string
    {
        return $this->imagen_portada;
    }

    public function setImagenPortada(string $imagen_portada): static
    {
        $this->imagen_portada = $imagen_portada;

        return $this;
    }

    public function getNumeroDeRetos(): ?int
    {
        return $this->numero_de_retos;
    }

    public function setNumeroDeRetos(int $numero_de_retos): static
    {
        $this->numero_de_retos = $numero_de_retos;

        return $this;
    }

    public function getCreadoEn(): ?\DateTimeInterface
    {
        return $this->creado_en;
    }

    public function setCreadoEn(\DateTimeInterface $creado_en): static
    {
        $this->creado_en = $creado_en;

        return $this;
    }

    public function getActualizadoEn(): ?string
    {
        return $this->actualizado_en;
    }

    public function setActualizadoEn(?string $actualizado_en): static
    {
        $this->actualizado_en = $actualizado_en;

        return $this;
    }

    /**
     * @return Collection<int, Reto>
     */
    public function getRetos(): Collection
    {
        return $this->retos;
    }

    public function addReto(Reto $reto): static
    {
        if (!$this->retos->contains($reto)) {
            $this->retos->add($reto);
            $reto->setIdAventura($this);
        }

        return $this;
    }

    public function removeReto(Reto $reto): static
    {
        if ($this->retos->removeElement($reto)) {
            // set the owning side to null (unless already changed)
            if ($reto->getIdAventura() === $this) {
                $reto->setIdAventura(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Resultado>
     */
    public function getResultados(): Collection
    {
        return $this->resultados;
    }

    public function addResultado(Resultado $resultado): static
    {
        if (!$this->resultados->contains($resultado)) {
            $this->resultados->add($resultado);
            $resultado->setIdAventura($this);
        }

        return $this;
    }

    public function removeResultado(Resultado $resultado): static
    {
        if ($this->resultados->removeElement($resultado)) {
            // set the owning side to null (unless already changed)
            if ($resultado->getIdAventura() === $this) {
                $resultado->setIdAventura(null);
            }
        }

        return $this;
    }
}
