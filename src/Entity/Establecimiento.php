<?php

namespace App\Entity;

use App\Repository\EstablecimientoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EstablecimientoRepository::class)
 */
class Establecimiento
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cueanexo;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCueanexo(): ?int
    {
        return $this->cueanexo;
    }

    public function setCueanexo(?int $cueanexo): self
    {
        $this->cueanexo = $cueanexo;

        return $this;
    }
}
