<?php

namespace App\Controller;

use App\Entity\Composer;
use App\Repository\ComposerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ComposerController extends AbstractController
{

    #[Route('/composer', name: 'app_composer_index', methods: [ 'GET' ])]
    
    public function index(ComposerRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('/composer/{id}', name: 'app_composer_show', methods: [ 'GET' ])]
    public function show(Composer $composer): JsonResponse
    {
        return $this->json($composer);
    }

    #[Route('/composer', name: 'app_composer_create', methods: [ 'POST' ])]
    public function create(ComposerRepository $repo, SerializerInterface $serializer, ValidatorInterface $validator, Request $request): JsonResponse
    {
        $composer = $serializer->deserialize($request->getContent(), Composer::class, 'json', []);

        $errors = $validator->validate($composer);

        if(count($errors) > 0) {
            return $this->json($errors, 422);
        }

        $repo->save($composer, TRUE);

        return $this->json($composer, 201);
    }

    #[Route('/composer/{id}', name: 'app_composer_update', methods: [ 'PUT' ])]
    public function update(ComposerRepository $repo, SerializerInterface $serializer, ValidatorInterface $validator, Composer $composer, Request $request): JsonResponse
    {
        $composer = $serializer->deserialize($request->getContent(), Composer::class, 'json', [ 'object_to_populate' => $composer ]);

        $errors = $validator->validate($composer);

        if(count($errors) > 0) {
            return $this->json($errors, 422);
        }

        $repo->save($composer, TRUE);

        return $this->json($composer);
    }

    #[Route('/composer/{id}', name: 'app_composer_delete', methods: [ 'DELETE' ])]
    public function delete(ComposerRepository $repo, Composer $composer): JsonResponse
    {
        $repo->remove($composer, TRUE);
        return $this->json('', 204);
    }

}
