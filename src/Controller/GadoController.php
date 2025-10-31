<?php

namespace App\Controller;

use App\Entity\Gado;
use App\Form\GadoType;
use App\Repository\GadoRepository;
use App\Repository\FazendaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gados')]
class GadoController extends AbstractController
{
    #[Route('/', name: 'gados_index', methods: ['GET'])]
    public function index(GadoRepository $repo): Response
    {
        $gados = $repo->findBy([], ['id' => 'DESC']);
        return $this->render('gados/index.html.twig', compact('gados'));
    }

    #[Route('/new', name: 'gados_new', methods: ['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $em, GadoRepository $repo, FazendaRepository $fazendaRepo): Response
    {
        $gado = new Gado();
        $form = $this->createForm(GadoType::class, $gado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 1) data nascimento não pode ser futura
            if ($gado->getDataNascimento() > new \DateTime()) {
                $this->addFlash('error', 'A data de nascimento não pode ser no futuro.');
                return $this->redirectToRoute('gados_new');
            }

            // 2) único codigo entre vivos
            if ($repo->existsCodigoVivo($gado->getCodigo())) {
                $this->addFlash('error', 'Já existe um animal vivo com este código.');
                return $this->redirectToRoute('gados_new');
            }

            // 3) limite de animais por fazenda (18 por hectare)
            $fazenda = $gado->getFazenda();
            $limite = (int)($fazenda->getTamanho() * 18);
            $totalAtuais = $fazenda->getGados()->count();
            if ($totalAtuais >= $limite) {
                $this->addFlash('error', "A fazenda '{$fazenda->getNome()}' atingiu o limite de {$limite} animais.");
                return $this->redirectToRoute('gados_new');
            }

            $em->persist($gado);
            $em->flush();

            $this->addFlash('success', 'Gado criado com sucesso.');
            return $this->redirectToRoute('gados_index');
        }

        return $this->renderForm('gados/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'gados_show', methods: ['GET'])]
    public function show(Gado $gado): Response
    {
        return $this->render('gados/show.html.twig', compact('gado'));
    }

    #[Route('/{id}/edit', name: 'gados_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Gado $gado, GadoRepository $repo, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(GadoType::class, $gado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // validações idem ao create, lembrando de ignorar o próprio id na verificação do codigo
            if ($repo->existsCodigoVivo($gado->getCodigo(), $gado->getId())) {
                $this->addFlash('error', 'Já existe outro animal vivo com este código.');
                return $this->redirectToRoute('gados_edit', ['id' => $gado->getId()]);
            }

            if ($gado->getDataNascimento() > new \DateTime()) {
                $this->addFlash('error', 'A data de nascimento não pode ser no futuro.');
                return $this->redirectToRoute('gados_edit', ['id' => $gado->getId()]);
            }

            // Se mudou de fazenda, verificar limite da nova fazenda
            $fazenda = $gado->getFazenda();
            $limite = (int)($fazenda->getTamanho() * 18);
            $totalAtuais = $fazenda->getGados()->count();
            // se o gado já pertencia à mesma fazenda, o count já inclui o próprio registro,
            // então comparamos apenas se o total > limite (não bloquear atualização simples)
            if ($totalAtuais > $limite) {
                $this->addFlash('error', "A fazenda '{$fazenda->getNome()}' excede o limite de {$limite} animais.");
                return $this->redirectToRoute('gados_edit', ['id' => $gado->getId()]);
            }

            $em->flush();
            $this->addFlash('success', 'Gado atualizado com sucesso.');
            return $this->redirectToRoute('gados_index');
        }

        return $this->renderForm('gados/edit.html.twig', [
            'form' => $form,
            'gado' => $gado,
        ]);
    }

    #[Route('/{id}', name: 'gados_delete', methods: ['POST'])]
    public function delete(Request $request, Gado $gado, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gado->getId(), $request->request->get('_token'))) {
            $em->remove($gado);
            $em->flush();
            $this->addFlash('success', 'Gado removido.');
        }
        return $this->redirectToRoute('gados_index');
    }

    #[Route('/{id}/abater', name: 'gados_abater', methods: ['POST'])]
    public function abater(Gado $gado, EntityManagerInterface $em): Response
    {
        try {
            if (! $gado->podeSerAbatido()) {
                $this->addFlash('error', 'O animal não se enquadra nas condições de abate.');
                return $this->redirectToRoute('gados_index');
            }

            $gado->setVivo(false);
            $gado->setDataAbate(new \DateTimeImmutable());
            $em->flush();

            $this->addFlash('success', 'Animal abatido com sucesso.');
        } catch (\Throwable $e) {
            $this->addFlash('error', 'Erro ao abater: ' . $e->getMessage());
        }

        return $this->redirectToRoute('gados_index');
    }
}
