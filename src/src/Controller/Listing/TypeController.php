<?php

namespace App\Controller\Listing;

use App\Enum\ListingType;
use App\Enum\ScopeType;
use App\Repository\TaskRepository;
use Twig\Environment as TwigEnvironment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TypeController extends AbstractController
{
    #[Route('/listing/{type}', name: 'app_listing', requirements: ['type' => 'personal|work'], methods: ['GET'])]
    public function index(string $type, TaskRepository $taskRepository): Response
    {
        $user = $this->getUser();
        $listingType = ($type === 'personal') ? ListingType::PERSONAL : ListingType::WORK;

        $tasksCurrent = $taskRepository->findBy([
            'scope' => ScopeType::CURRENT,
            'listing' => $listingType,
            'user' => $user,
        ]);
        $tasksNext = $taskRepository->findBy([
            'scope' => ScopeType::NEXT,
            'listing' => $listingType,
            'user' => $user,
        ]);

        return $this->render('listing/type.html.twig', [
            'listingType' => $type,
            'tasksCurrent' => $tasksCurrent,
            'tasksNext' => $tasksNext,
        ]);
    }

    #[Route('/listing/{type}/tables', name: 'app_listing_tables', requirements: ['type' => 'personal|work'], methods: ['GET'])]
    public function getListingTables(string $type, TaskRepository $taskRepository, TwigEnvironment $twig): Response
    {
        $user = $this->getUser();
        $listingType = ($type === 'personal') ? ListingType::PERSONAL : ListingType::WORK;

        $tasksCurrent = $taskRepository->findBy([
            'scope' => ScopeType::CURRENT,
            'listing' => $listingType,
            'user' => $user,
        ]);
        $tasksNext = $taskRepository->findBy([
            'scope' => ScopeType::NEXT,
            'listing' => $listingType,
            'user' => $user,
        ]);

        return new Response($twig->render('listing/table-listing.html.twig', [
            'listingType' => $type,
            'tasksCurrent' => $tasksCurrent,
            'tasksNext' => $tasksNext,
        ]));
    }
}