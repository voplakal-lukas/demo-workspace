<?php

namespace App\Controller\Task;

use App\Entity\Task;
use App\Enum\ListingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CompleteTaskController extends AbstractController
{
    #[Route('/task/complete/{taskId}', name: 'app_task_complete')]
    public function index(
        #[MapEntity(mapping: ['taskId' => 'id'])]
        Task $task,
        EntityManagerInterface $entityManager, 
        Request $request
        ): Response
    {
        $task->setListing(ListingType::ARCHIVED);
        $entityManager->flush();

        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?: '/');
    }
}
