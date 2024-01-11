<?php

namespace App\Controller;

use App\Entity\Symphony;
use OpenApi\Attributes as OA;
use App\Repository\SymphonyRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[OA\Tag(name: 'Symphony')]
#[IsGranted('ROLE_USER')]
class SymphonyController extends AbstractController
{

    #[OA\Response(
        response: 200,
        description: 'Returns all symphonies',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Symphony::class))
        )
    )]
    #[Route('/symphony', name: 'app_symphony_index', methods: [ 'GET' ])]
    public function index(SymphonyRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[OA\Response(
        response: 200,
        description: 'Returns symphony by ID',
        content: new Model(type: Symphony::class)
    )]
    #[Route('/symphony/{id}', name: 'app_symphony_show', methods: [ 'GET' ])]
    public function show(Symphony $symphony): JsonResponse
    {
        return $this->json($symphony);
    }

    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            ref: new Model(type: Symphony::class, groups: [ 'create' ])
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Create a symphony',
        content: new Model(type: Symphony::class)
    )]
    #[Route('/symphony', name: 'app_symphony_create', methods: [ 'POST' ])]
    public function create(SymphonyRepository $repo, SerializerInterface $serializer, ValidatorInterface $validator, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $symphony = $serializer->deserialize($request->getContent(), Symphony::class, 'json', [
            'groups' => [ 'create' ]
        ]);

        $errors = $validator->validate($symphony);

        if (count($errors) > 0)
        {
            return $this->json($errors, 422);
        }

        $repo->save($symphony, TRUE);

        return $this->json($symphony, 201);
    }

    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            ref: new Model(type: Symphony::class, groups: [ 'update' ])
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Update a symphony',
        content: new Model(type: Symphony::class)
    )]
    #[Route('/symphony/{id}', name: 'app_symphony_update', methods: [ 'PUT' ])]
    public function update(SymphonyRepository $repo, SerializerInterface $serializer, ValidatorInterface $validator, Symphony $symphony, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $symphony = $serializer->deserialize($request->getContent(), Symphony::class, 'json', [
            'object_to_populate' => $symphony,
            'groups'             => [ 'update' ]
        ]);

        $errors = $validator->validate($symphony);

        if (count($errors) > 0)
        {
            return $this->json($errors, 422);
        }

        $repo->save($symphony, TRUE);

        return $this->json($symphony);
    }

    #[OA\Response(
        response: 204,
        description: 'Delete a symphony',
    )]
    #[Route('/symphony/{id}', name: 'app_symphony_delete', methods: [ 'DELETE' ])]
    public function delete(SymphonyRepository $repo, Symphony $symphony): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $repo->remove($symphony, TRUE);
        return $this->json('', 204);
    }

}
