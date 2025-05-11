<?php

namespace App\Controller\Task;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreateTaskController extends AbstractController
{
    #[Route('/task/create', name: 'app_task_create')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {   
        $user = $this->getUser();
        $form = $this->createForm(TaskType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            /** @var Task $task */
            $task = $form->getData();
            $task->setCreationDate(new \DateTime());
            $task->setUpdatedDate(new \DateTime());
            $task->setUser($user);
            $entityManager->persist($task);
            $entityManager->flush();
            $listingType = $task->getListing()->value;
            return $this->redirectToRoute('app_listing', ['type' => $listingType]);
        } 
        return $this->render('task/create.html.twig', [
            'form' => $form
        ]);
    }
}
