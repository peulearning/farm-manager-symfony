<?php

namespace App\Entity;

use App\Repository\FazendaRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FazendaRepository::class)]
#[ORM\Table(name: 'fazendas')]
class Fazenda
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private string $nome;

    #[ORM\Column(type: 'float')]
    #[Assert\Positive]
    private float $tamanho; // hectares

    #[ORM\OneToMany(mappedBy: 'fazenda', targetEntity: Gado::class, cascade: ['remove'])]
    private Collection $gados;

    #[ORM\ManyToMany(targetEntity: Veterinario::class, inversedBy: 'fazendas')]
    #[ORM\JoinTable(name: 'fazenda_veterinario')]
    private Collection $veterinarios;

    public function __construct()
    {
        $this->gados = new ArrayCollection();
        $this->veterinarios = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getNome(): string { return $this->nome; }
    public function setNome(string $nome): self { $this->nome = $nome; return $this; }

    public function getTamanho(): float { return $this->tamanho; }
    public function setTamanho(float $tamanho): self { $this->tamanho = $tamanho; return $this; }

    /** @return Collection<int, Gado> */
    public function getGados(): Collection { return $this->gados; }

    /** @return Collection<int, Veterinario> */
    public function getVeterinarios(): Collection { return $this->veterinarios; }

    // Regra: cada fazenda suporta no máximo 18 animais por hectare
    public function limiteAnimais(): int
    {
        return (int)($this->tamanho * 18);
    }

    public function atingiuLimite(): bool
    {
        return $this->gados->count() >= $this->limiteAnimais();
    }
}
