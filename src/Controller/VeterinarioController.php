<?php

namespace App\Controller;

use App\Entity\Veterinario;
use App\Form\VeterinarioType;
use App\Repository\VeterinarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/veterinarios')]
class VeterinarioController extends AbstractController
{
    #[Route('/', name: 'veterinario_index', methods: ['GET'])]
    public function index(VeterinarioRepository $veterinarioRepository): Response
    {
        $veterinarios = $veterinarioRepository->findAll();

        return $this->render('veterinario/index.html.twig', [
            'veterinarios' => $veterinarios,
        ]);
    }

    #[Route('/new', name: 'veterinario_new', methods: ['GET', 'POST'])]
    public function new(Request $request, VeterinarioRepository $repo, EntityManagerInterface $em): Response
    {
        $veterinario = new Veterinario();
        $form = $this->createForm(VeterinarioType::class, $veterinario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Regra de negócio: CRMV deve ser único
            if ($repo->existsByCrmv($veterinario->getCrmv())) {
                $this->addFlash('error', 'Já existe um veterinário com este CRMV.');
            } else {
                $em->persist($veterinario);
                $em->flush();
                $this->addFlash('success', 'Veterinário cadastrado com sucesso!');
                return $this->redirectToRoute('veterinario_index');
            }
        }

        return $this->render('veterinario/new.html.twig', [
            'veterinario' => $veterinario,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'veterinario_show', methods: ['GET'])]
    public function show(Veterinario $veterinario): Response
    {
        return $this->render('veterinario/show.html.twig', [
            'veterinario' => $veterinario,
        ]);
    }

    #[Route('/{id}/edit', name: 'veterinario_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Veterinario $veterinario, VeterinarioRepository $repo, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(VeterinarioType::class, $veterinario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Verifica se o CRMV foi alterado e se já existe
            $exists = $repo->findByCrmv($veterinario->getCrmv());
            if ($exists && $exists->getId() !== $veterinario->getId()) {
                $this->addFlash('error', 'Já existe outro veterinário com este CRMV.');
            } else {
                $em->flush();
                $this->addFlash('success', 'Veterinário atualizado com sucesso!');
                return $this->redirectToRoute('veterinario_index');
            }
        }

        return $this->render('veterinario/edit.html.twig', [
            'veterinario' => $veterinario,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'veterinario_delete', methods: ['POST'])]
    public function delete(Request $request, Veterinario $veterinario, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$veterinario->getId(), $request->request->get('_token'))) {
            $em->remove($veterinario);
            $em->flush();
            $this->addFlash('success', 'Veterinário excluído com sucesso!');
        }

        return $this->redirectToRoute('veterinario_index');
    }
}
