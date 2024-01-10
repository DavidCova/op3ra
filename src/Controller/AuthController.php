<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\AccessTokenHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthController extends AbstractController
{

    #[Route('/login', name: 'app_auth_login', methods:['POST'])]
    public function login(#[CurrentUser] User $user, AccessTokenHandler $accessTokenHandler): JsonResponse
    {
        if (!$user)
        {
            return $this->json('Invalid credentials', Response::HTTP_UNAUTHORIZED);
        }

        $token = $accessTokenHandler->createForUser($user);

        return $this->json([
            'token' => $token,
        ]);
    }

}
