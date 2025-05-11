<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccountSettingsController extends AbstractController
{
    #[Route('/account-settings', name: 'app_account_settings')]
    public function index(): Response
    {
        return $this->render('account_settings/index.html.twig', [
            'controller_name' => 'AccountSettingsController',
        ]);
    }
}
