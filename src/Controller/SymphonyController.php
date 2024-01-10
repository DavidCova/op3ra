<?php

namespace App\Controller;

use App\Entity\Symphony;
use App\Repository\SymphonyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class SymphonyController extends AbstractController
{

    #[Route('/symphony', name: 'app_symphony_index', methods: [ 'GET' ])]
    public function index(SymphonyRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('/symphony/{id}', name: 'app_symphony_show', methods: [ 'GET' ])]
    public function show(Symphony $symphony): JsonResponse
    {
        return $this->json($symphony);
    }

    #[Route('/symphony', name: 'app_symphony_create', methods: [ 'POST' ])]
    public function create(SymphonyRepository $repo, SerializerInterface $serializer, ValidatorInterface $validator, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $symphony = $serializer->deserialize($request->getContent(), Symphony::class, 'json', []);

        $errors = $validator->validate($symphony);

        if (count($errors) > 0)
        {
            return $this->json($errors, 422);
        }

        $repo->save($symphony, TRUE);

        return $this->json($symphony, 201);
    }

    #[Route('/symphony/{id}', name: 'app_symphony_update', methods: [ 'PUT' ])]
    public function update(SymphonyRepository $repo, SerializerInterface $serializer, ValidatorInterface $validator, Symphony $symphony, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $symphony = $serializer->deserialize($request->getContent(), Symphony::class, 'json', [ 'object_to_populate' => $symphony ]);

        $errors = $validator->validate($symphony);

        if (count($errors) > 0)
        {
            return $this->json($errors, 422);
        }

        $repo->save($symphony, TRUE);

        return $this->json($symphony);
    }

    #[Route('/symphony/{id}', name: 'app_symphony_delete', methods: [ 'DELETE' ])]
    public function delete(SymphonyRepository $repo, Symphony $symphony): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $repo->remove($symphony, TRUE);
        return $this->json('', 204);
    }

}
