<?php

namespace App\DataFixtures;

use App\Entity\Veterinario;
use App\Entity\Fazenda;
use App\Entity\Gado;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('pt_BR');

        // --- 1. Criar Veterinários ---
        $veterinarios = [];
        for ($i = 0; $i < 5; $i++) {
            $vet = new Veterinario();
            $vet->setNome($faker->name);
            $vet->setCrmv('CRMV-' . $faker->unique()->numerify('####'));
            $manager->persist($vet);
            $veterinarios[] = $vet;
        }

        // --- 2. Criar Fazendas ---
        $fazendas = [];
        for ($i = 0; $i < 3; $i++) {
            $fazenda = new Fazenda();
            $fazenda->setNome('Fazenda ' . $faker->company);
            $fazenda->setTamanho($faker->numberBetween(5, 50));
            $fazenda->setResponsavel($faker->name);

            // vincular veterinário aleatório
            $fazenda->addVeterinario($faker->randomElement($veterinarios));

            $manager->persist($fazenda);
            $fazendas[] = $fazenda;
        }

        // --- 3. Criar Gados ---
        foreach (range(1, 20) as $i) {
            $gado = new Gado();
            $gado->setCodigo('GADO-' . $faker->unique()->numerify('####'));
            $gado->setLeite($faker->randomFloat(2, 5, 40));
            $gado->setRacao($faker->randomFloat(2, 5, 25));
            $gado->setPeso($faker->randomFloat(2, 200, 800));
            $gado->setDataNascimento($faker->dateTimeBetween('-10 years', 'now'));
            $gado->setVivo(true);

            // 🚨 obrigatório: vincular fazenda existente
            $gado->setFazenda($faker->randomElement($fazendas));

            $manager->persist($gado);
        }

        $manager->flush();
    }
}
