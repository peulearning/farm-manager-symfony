<?php

namespace App\Entity;

use App\Repository\GadoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Carbon\Carbon;


#[ORM\Entity(repositoryClass: GadoRepository::class)]
#[ORM\Table(name: 'gados')]
#[UniqueEntity(fields: ['codigo'], message: 'Já existe um animal vivo com este código.')]
class Gado
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    #[Assert\NotBlank(message: 'O código é obrigatório.')]
    private string $codigo;

    #[ORM\Column(type: 'float')]
    #[Assert\PositiveOrZero]
    private float $leite;

    #[ORM\Column(type: 'float')]
    #[Assert\PositiveOrZero]
    private float $racao;

    #[ORM\Column(type: 'float')]
    #[Assert\Positive]
    private float $peso;

    #[ORM\Column(type: 'date')]
    #[Assert\NotBlank]
    private \DateTimeInterface $dataNascimento;

    #[ORM\Column(type: 'boolean')]
    private bool $vivo = true;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dataAbate = null;

    #[ORM\ManyToOne(targetEntity: Fazenda::class, inversedBy: 'gados')]
    #[ORM\JoinColumn(nullable: false)]
    private Fazenda $fazenda;

    // === Métodos auxiliares ===

    public function getId(): ?int { return $this->id; }
    public function getCodigo(): string { return $this->codigo; }
    public function setCodigo(string $codigo): self { $this->codigo = $codigo; return $this; }
    public function getLeite(): float { return $this->leite; }
    public function setLeite(float $leite): self { $this->leite = $leite; return $this; }
    public function getRacao(): float { return $this->racao; }
    public function setRacao(float $racao): self { $this->racao = $racao; return $this; }
    public function getPeso(): float { return $this->peso; }
    public function setPeso(float $peso): self { $this->peso = $peso; return $this; }
    public function getDataNascimento(): \DateTimeInterface { return $this->dataNascimento; }
    public function setDataNascimento(\DateTimeInterface $dataNascimento): self { $this->dataNascimento = $dataNascimento; return $this; }
    public function isVivo(): bool { return $this->vivo; }
    public function setVivo(bool $vivo): self { $this->vivo = $vivo; return $this; }
    public function getDataAbate(): ?\DateTimeInterface { return $this->dataAbate; }
    public function setDataAbate(?\DateTimeInterface $dataAbate): self { $this->dataAbate = $dataAbate; return $this; }
    public function getFazenda(): Fazenda { return $this->fazenda; }
    public function setFazenda(Fazenda $fazenda): self { $this->fazenda = $fazenda; return $this; }

    // === Lógica de negócio ===

    public function getIdadeAnos(): int
    {
        return Carbon::parse($this->dataNascimento->format('Y-m-d'))->age;
    }

    public function getPesoArroba(): float
    {
        return $this->peso / 15;
    }

    public function getLeiteSemana(): float
    {
        return $this->leite * 7;
    }

    public function getRacaoSemana(): float
    {
        return $this->racao * 7;
    }

    public function podeSerAbatido(): bool
    {
        $idade = $this->getIdadeAnos();
        $racaoPorDia = $this->racao / 7;
        $pesoArroba = $this->peso / 15;

        return (
            $idade > 5 ||
            $this->leite < 40 ||
            ($this->leite < 70 && $racaoPorDia > 50) ||
            $pesoArroba > 18
        );
    }
}
