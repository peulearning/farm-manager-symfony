<?php

namespace App\Entity;

use App\Repository\GadoRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GadoRepository::class)]
#[ORM\Table(name: 'gados')]
class Gado
{
    // Atributos da classe

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:100)]
    #[Assert\NotBlank]
    private int $codigo;

    #[ORM\Column(type:"float")]
    #[Assert\NotNull]
    private float $leite;

    #[ORM\Column(type:"float")]
    #[Assert\NotNull]
    private float $racao;

    #[ORM\Column(type:"float")]
    #[Assert\NotNull]
    private float $peso;

    #[ORM\Column(type:"date")]
    #[ORM\Column(type:"date")]
    #[Assert\NotNull]
    private \DateTimeInterface $nascimentoo;
    #[ORM\ManyToOne(targetEntity: Fazenda::class, inversedBy: 'gados')]
    #[ORM\JoinColumn(nullable: false)]
    private Fazenda $fazenda;

    #[ORM\Column(type:"boolean")]
    #[ORM\Column(type:"boolean")]
    private bool $vivo = true;

    private ?\DateTimeInterface $abate = null;



    // Metodo Construtor

    public function __construct()
    {
        $this->vivo = true;
    }

    //Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigo(): int
    {
        return $this->codigo;
    }

    public function setCodigo(int $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function isVivo(): bool
    {
        return $this->vivo;
    }

    public function setVivo(bool $vivo): self
    {
        $this->vivo = $vivo;

        return $this;
    }

    public function getFazenda(): Fazenda
    {
        return $this->fazenda;
    }

    public function setFazenda(Fazenda $fazenda): self
    {
        $this->fazenda = $fazenda;

        return $this;
    }

}
