<?php

namespace App\Controller\Listing;

use App\Entity\Task;
use App\Enum\ScopeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ChangeTaskScopeController extends AbstractController
{
    #[Route('/task/update-scope', name: 'task_update_scope', methods: ['POST'])]
    public function updateScope(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $content = json_decode($request->getContent(), true);
        $taskId = $content['taskId'] ?? null;
        $newScope = $content['newScope'] ?? null;
    
        if (!$taskId || !$newScope) {
            return new JsonResponse(['error' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }
    
        $task = $entityManager->getRepository(Task::class)->find($taskId);
        if (!$task) {
            return new JsonResponse(['error' => 'Task not found'], JsonResponse::HTTP_NOT_FOUND);
        }
    
        // 🚀 Převod stringu na ENUM
        try {
            $scopeEnum = ScopeType::from($newScope);
        } catch (\ValueError $e) {
            return new JsonResponse(['error' => 'Invalid scope type'], JsonResponse::HTTP_BAD_REQUEST);
        }
    
        $task->setScope($scopeEnum);
        $entityManager->persist($task);
        $entityManager->flush();
    
        return new JsonResponse(['success' => true, 'message' => 'Task updated successfully']);
    }
}
