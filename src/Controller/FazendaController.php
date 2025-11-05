<?php

namespace App\Controller;

use App\Entity\Fazenda;
use App\Form\FazendaType;
use App\Repository\FazendaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fazendas')]
class FazendaController extends AbstractController
{
    #[Route('/', name: 'fazendas_index', methods: ['GET'])]
    public function index(FazendaRepository $repo): Response
    {
        $fazendas = $repo->findBy([], ['nome' => 'ASC']);
        return $this->render('fazendas/index.html.twig', compact('fazendas'));
    }

    #[Route('/new', name: 'fazendas_new', methods: ['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $em, FazendaRepository $repo): Response
    {
        $fazenda = new Fazenda();
        $form = $this->createForm(FazendaType::class, $fazenda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // RN: nome único
            if ($repo->existsByNome($fazenda->getNome())) {
                $this->addFlash('error', 'Já existe uma fazenda com este nome.');
                return $this->redirectToRoute('fazendas_new');
            }

            $em->persist($fazenda);
            $em->flush();
            $this->addFlash('success', 'Fazenda criada com sucesso.');
            return $this->redirectToRoute('fazendas_index');
        }

        return $this->render('fazendas/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'fazendas_show', methods: ['GET'])]
    public function show(Fazenda $fazenda): Response
    {
        return $this->render('fazendas/show.html.twig', compact('fazenda'));
    }

    #[Route('/{id}/edit', name: 'fazendas_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Fazenda $fazenda, FazendaRepository $repo, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(FazendaType::class, $fazenda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // validação de nome único (exceto essa fazenda)
            if ($repo->existsByNome($fazenda->getNome(), $fazenda->getId())) {
                $this->addFlash('error', 'Já existe outra fazenda com este nome.');
                return $this->redirectToRoute('fazendas_edit', ['id' => $fazenda->getId()]);
            }

            $em->flush();
            $this->addFlash('success', 'Fazenda atualizada.');
            return $this->redirectToRoute('fazendas_index');
        }

        return $this->renderForm('fazendas/edit.html.twig', [
            'form' => $form,
            'fazenda' => $fazenda,
        ]);
    }

    #[Route('/{id}', name: 'fazendas_delete', methods: ['POST'])]
    public function delete(Request $request, Fazenda $fazenda, EntityManagerInterface $em, FazendaRepository $repo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fazenda->getId(), $request->request->get('_token'))) {
            // opcionalmente, impedir remoção se houver gados
            if ($fazenda->getGados()->count() > 0) {
                $this->addFlash('error', 'Não é possível excluir a fazenda pois há gados vinculados.');
                return $this->redirectToRoute('fazendas_index');
            }

            $em->remove($fazenda);
            $em->flush();
            $this->addFlash('success', 'Fazenda removida.');
        }

        return $this->redirectToRoute('fazendas_index');
    }
}
