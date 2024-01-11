<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\AccessTokenHandler;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[OA\Tag(name: 'Auth')]
#[Security]
class AuthController extends AbstractController
{

    #[OA\RequestBody(
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'username', type: 'string'),
                new OA\Property(property: 'password', type: 'string'),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful login.',
        content: new OA\JsonContent(
            type: 'object',
            properties: [new OA\Property(property: 'token', type: 'string')]
        )

    )]
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
