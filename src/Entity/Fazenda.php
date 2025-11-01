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

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank]
    private string $nome;

    #[ORM\Column(type: 'float')]
    #[Assert\Positive]
    private float $tamanho; // hectares

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private string $responsavel;

    #[ORM\ManyToMany(targetEntity: Veterinario::class, inversedBy: 'fazendas')]
    #[ORM\JoinTable(name: 'fazenda_veterinarios')]
    private Collection $veterinarios;

    #[ORM\OneToMany(mappedBy: 'fazenda', targetEntity: Gado::class, cascade: ['persist', 'remove'])]
    private Collection $gados;

    public function __construct()
    {
        $this->veterinarios = new ArrayCollection();
        $this->gados = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;
        return $this;
    }

    public function getTamanho(): float
    {
        return $this->tamanho;
    }

    public function setTamanho(float $tamanho): self
    {
        $this->tamanho = $tamanho;
        return $this;
    }

    public function getResponsavel(): string
    {
        return $this->responsavel;
    }

    public function setResponsavel(string $responsavel): self
    {
        $this->responsavel = $responsavel;
        return $this;
    }

    /**
     * @return Collection<int, Veterinario>
     */
    public function getVeterinarios(): Collection
    {
        return $this->veterinarios;
    }

    public function addVeterinario(Veterinario $veterinario): self
    {
        if (!$this->veterinarios->contains($veterinario)) {
            $this->veterinarios->add($veterinario);
        }
        return $this;
    }

    public function removeVeterinario(Veterinario $veterinario): self
    {
        $this->veterinarios->removeElement($veterinario);
        return $this;
    }

    /**
     * @return Collection<int, Gado>
     */
    public function getGados(): Collection
    {
        return $this->gados;
    }

    public function addGado(Gado $gado): self
    {
        if (!$this->gados->contains($gado)) {
            $this->gados->add($gado);
            $gado->setFazenda($this);
        }
        return $this;
    }

    public function removeGado(Gado $gado): self
    {
        if ($this->gados->removeElement($gado)) {
            if ($gado->getFazenda() === $this) {
                $gado->setFazenda(null);
            }
        }
        return $this;
    }
}
