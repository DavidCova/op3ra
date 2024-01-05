<?php

namespace App\Controller;

use App\Entity\Symfony;
use App\Repository\SymfonyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class SymfonyController extends AbstractController
{

    #[Route('/symfony', name: 'app_symfony_index', methods: [ 'GET' ])]
    public function index(SymfonyRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('/symfony/{id}', name: 'app_symfony_show', methods: [ 'GET' ])]
    public function show(Symfony $symfony): JsonResponse
    {
        return $this->json($symfony);
    }

    #[Route('/symfony', name: 'app_symfony_create', methods: [ 'POST' ])]
    public function create(SymfonyRepository $repo, SerializerInterface $serializer, ValidatorInterface $validator, Request $request): JsonResponse
    {
        $symfony = $serializer->deserialize($request->getContent(), Symfony::class, 'json', []);

        $errors = $validator->validate($symfony);

        if (count($errors) > 0)
        {
            return $this->json($errors, 422);
        }

        $repo->save($symfony, TRUE);

        return $this->json($symfony, 201);
    }

    #[Route('/symfony/{id}', name: 'app_symfony_update', methods: [ 'PUT' ])]
    public function update(SymfonyRepository $repo, SerializerInterface $serializer, ValidatorInterface $validator, Symfony $symfony, Request $request): JsonResponse
    {
        $symfony = $serializer->deserialize($request->getContent(), Symfony::class, 'json', [ 'object_to_populate' => $symfony ]);

        $errors = $validator->validate($symfony);

        if (count($errors) > 0)
        {
            return $this->json($errors, 422);
        }

        $repo->save($symfony, TRUE);

        return $this->json($symfony);
    }

    #[Route('/symfony/{id}', name: 'app_symfony_delete', methods: [ 'DELETE' ])]
    public function delete(SymfonyRepository $repo, Symfony $symfony): JsonResponse
    {
        $repo->remove($symfony, TRUE);
        return $this->json('', 204);
    }

}
