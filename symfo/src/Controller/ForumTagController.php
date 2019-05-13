<?php

namespace App\Controller;

use App\Entity\ForumTag;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ForumTagRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/forum/tag")
 */
class ForumTagController extends AbstractController
{
    /**
     * @Route("/", name="forum_tag_index", methods={"GET"})
     */
    public function index(SerializerInterface $serializer, ForumTagRepository $forumTagRepository): Response
    {
        $tags = $forumTagRepository->findAll();
        $jsonTags = $serializer->serialize($tags, 'json',['groups' => 'forum_tags']);
 
        return JsonResponse::fromJsonString($jsonTags);
    }

    /**
     * @Route("/new", name="forum_tag_new", methods={"POST"})
     */
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $content = $request->getContent();
        
        $tag = $serializer->deserialize($content, ForumTag::class, 'json');
        
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user->getRoles()[0] == 'ROLE_ADMIN') {            
            $errors = $validator->validate($tag);
            
            if (count($errors) > 0)
            {
                foreach ($errors as $error) 
                {
                    return JsonResponse::fromJsonString(
                        'message: Votre entrée comporte des erreurs : '.$error.'.', 
                        406);
                    }
                }
                                
                $entityManager->persist($tag);
                $entityManager->flush();

                return JsonResponse::fromJsonString('message: Votre tag a été créé', 200);
        } 
        
        return JsonResponse::fromJsonString('message: Vous n\'avez pas les droits nécessaires pour ajouter une catégorie', 403);
    }

    /**
     * @Route("/{id}", name="forum_tag_show", methods={"GET"})
     */
    public function show(ForumTag $tag, Request $request, SerializerInterface $serializer): Response
    {
        $jsonTag = $serializer->serialize($tag, 'json',
            ['groups' => 'forum_tags']
        );
 
        return JsonResponse::fromJsonString($jsonTag);
    }

    /**
     * @Route("/{id}/edit", name="forum_tag_edit", methods={"PUT"})
     */
    public function edit(Request $request, ForumTag $forumTag, EntityManagerInterface $entityManager, ValidatorInterface $validator, SerializerInterface $serializer): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            $admin = $user;
        }       

        if ($user == $forumTag->getUser() || $admin)
        {
            $content = $request->getContent();

            $editedTag = $serializer->deserialize($content, ForumTag::class, 'json');
            // dd($editedTag);

            $errors = $validator->validate($editedTag);

            if (count($errors) > 0)
            {
                foreach ($errors as $error) 
                {
                    return JsonResponse::fromJsonString(
                        'message: Votre modification comporte des erreurs : '.$error.'.', 
                        304);
                }
            }

            $name = $editedTag->getName();
            if ($name != null)
            {
                $forumTag->setName($name);
            }

            $forumTag->setUpdatedAt(new \Datetime());
            
            $entityManager->merge($forumTag);
            $entityManager->persist($forumTag);
            $entityManager->flush();

            return JsonResponse::fromJsonString('message: Votre catégorie a été modifiée', 200);
        }

        return JsonResponse::fromJsonString('message: Vous n\'êtes pas autorisé à modifier cette catégorie', 403);
    }

    /**
     * @Route("/{id}", name="forum_tag_delete", methods={"DELETE"})
     */
    public function delete(ObjectManager $objectManager, ForumTag $forumTag): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            $admin = $user;
        }

        if ($user = $forumTag->getUser() || $admin) {
            $objectManager->remove($forumTag);
            $objectManager->flush();
            
            return JsonResponse::fromJsonString('message: Votre catégorie a été supprimée', 200);
        }

        return JsonResponse::fromJsonString('message: Vous n\'êtes pas autorisé à supprimer cette catégorie', 406);
    }
}