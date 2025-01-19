<?php

namespace App\Controller\Listing;

use App\Enum\ListingType;
use App\Enum\ScopeType;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PersonalListingController extends AbstractController
{
    #[Route('/listing/personal/', name: 'app_listing_personal')]
    public function index(TaskRepository $taskRepository, ): Response
    {   
        $user = $this->getUser();
        $tasksCurrent = $taskRepository->findBy([
            'scope' => ScopeType::CURRENT,
            'listing' => ListingType::PERSONAL,
            'user' => $user,
        ]);
        $tasksNext = $taskRepository->findBy([
            'scope' => ScopeType::NEXT,
            'listing' => ListingType::PERSONAL,
            'user' => $user,
        ]);
        return $this->render('listing/personal.html.twig', [
            'tasksCurrent' => $tasksCurrent,
            'tasksNext' => $tasksNext,
        ]);
    }
}
