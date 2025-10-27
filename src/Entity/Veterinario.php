<?php

namespace App\Entity;

use App\Repository\VeterinarioRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: VeterinarioRepository::class)]
#[ORM\Table(name: 'veterinarios')]
#[UniqueEntity(fields: ['crmv'], message: 'Já existe um veterinário cadastrado com este CRMV.')]
class Veterinario
{

    // Atributos da classe
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $id = null;

     #[ORM\Column(type:"string", length:255, unique:true)]
    #[Assert\NotBlank]
    private string $crmv;

     #[ORM\Column(type:"string", length:255)]
    #[Assert\NotBlank]
    private string $nome;

     #[ORM\ManyToMany(targetEntity: Fazenda::class, mappedBy:"veterinarios")]
     private Collection $fazendas;


    // Metódo Construtor

    public function __construct()
    {
        $this->fazendas = new ArrayCollection();
    }


    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCrmv(): string
    {
        return $this->crmv;
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

    public function setCrmv(string $crmv): self
    {
        $this->crmv = $crmv;

        return $this;
    }

    /**
     * @return Collection<int, Fazenda>
     */
    public function getFazendas(): Collection
    {
        return $this->fazendas;

    }



}
