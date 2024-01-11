<?php

namespace App\Controller;

use App\Entity\Composer;
use OpenApi\Attributes as OA;
use App\Repository\ComposerRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[OA\Tag(name: 'Composer')]
#[IsGranted('ROLE_USER')]
class ComposerController extends AbstractController
{

    #[OA\Response(
        response: 200,
        description: 'Returns all composers',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Composer::class))
        )
    )]
    #[Route('/composer', name: 'app_composer_index', methods: [ 'GET' ])]
    public function index(ComposerRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[OA\Response(
        response: 200,
        description: 'Returns composer by ID',
        content: new Model(type: Composer::class)
    )]
    #[Route('/composer/{id}', name: 'app_composer_show', methods: [ 'GET' ])]
    public function show(Composer $composer): JsonResponse
    {
        return $this->json($composer);
    }

    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            ref: new Model(type: Composer::class, groups: ['create'])
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Create a composer',
        content: new Model(type: Composer::class)
    )]
    # #[IsGranted('ROLE_USER')] not necessary with $this->denyAccessUnlessGranted('ROLE_ADMIN'); but also possible to use

    #[Route('/composer', name: 'app_composer_create', methods: [ 'POST' ])]
    public function create(ComposerRepository $repo, SerializerInterface $serializer, ValidatorInterface $validator, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $composer = $serializer->deserialize($request->getContent(), Composer::class, 'json', [
            'groups' => [ 'create' ]
        ]);

        $errors = $validator->validate($composer);

        if (count($errors) > 0)
        {
            return $this->json($errors, 422);
        }

        $repo->save($composer, TRUE);

        return $this->json($composer, 201);
    }

    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            ref: new Model(type: Composer::class, groups: ['update'])
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Update a composers',
        content: new Model(type: Composer::class)
    )]
    #[Route('/composer/{id}', name: 'app_composer_update', methods: [ 'PUT' ])]
    public function update(ComposerRepository $repo, SerializerInterface $serializer, ValidatorInterface $validator, Composer $composer, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $composer = $serializer->deserialize($request->getContent(), Composer::class, 'json', [
            'object_to_populate' => $composer,
            'groups'             => [ 'update' ]
        ]);

        $errors = $validator->validate($composer);

        if (count($errors) > 0)
        {
            return $this->json($errors, 422);
        }

        $repo->save($composer, TRUE);

        return $this->json($composer);
    }

    #[OA\Response(
        response: 204,
        description: 'Delete a composers',
    )]
    #[Route('/composer/{id}', name: 'app_composer_delete', methods: [ 'DELETE' ])]
    public function delete(ComposerRepository $repo, Composer $composer): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $repo->remove($composer, TRUE);
        return $this->json('', 204);
    }

}
