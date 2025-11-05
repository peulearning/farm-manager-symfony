<?php

namespace App\Controller;

use App\Repository\GadoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RelatorioController extends AbstractController
{
    #[Route('/', name: 'relatorios_inicial')]
    public function index(GadoRepository $gadoRepository): Response
    {
        $gados = $gadoRepository->findAll();

        // Relatório 1: Animais abatidos
        $animaisAbatidos = array_filter($gados, fn($g) => !$g->isVivo());

        // Relatório 2: Total de leite produzido por semana
        $totalLeiteSemana = array_reduce($gados, fn($soma, $g) => $soma + $g->getLeite() * 7, 0);

        // Relatório 3: Total de ração necessária por semana
        $totalRacaoSemana = array_reduce($gados, fn($soma, $g) => $soma + $g->getRacao() * 7, 0);

        // Relatório 4: Animais até 1 ano e com mais de 500kg de ração/semana
        $animaisAteUmAno = array_filter($gados, function ($g) {
            return $g->getIdadeAnos() <= 1 && $g->getRacaoSemana() > 500;
        });

        return $this->render('relatorios/inicial.html.twig', [
            'animaisAbatidos' => $animaisAbatidos,
            'totalLeiteSemana' => $totalLeiteSemana,
            'totalRacaoSemana' => $totalRacaoSemana,
            'animaisAteUmAno' => $animaisAteUmAno,
        ]);
    }
}
