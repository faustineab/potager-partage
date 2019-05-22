<?php

namespace App\Controller;

use App\Entity\Garden;
use App\Entity\ForumTag;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/garden/{gardenid}/forum/tag")
 * @ParamConverter("garden", options={"id" = "gardenid"})
 */
class ForumTagController extends AbstractController
{
    /**
     * @Route("/", name="forum_tag_index", methods={"GET"})
     */
    public function index(Garden $garden,SerializerInterface $serializer): Response
    {
        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {
                $tags = $garden->getForumTags();
                $jsonTags = $serializer->serialize($tags, 'json', [
                    'groups' => 'forum_tags',
                    'circular_reference_handler' => function ($tags) {
                        return $tags->getId();
                    },
                ]);
                    
                return JsonResponse::fromJsonString($jsonTags);
            }
            return JsonResponse::fromJsonString('Vous ne faites pas partie de ce potager', 400);
        
    }

    /**
     * @Route("/new", name="forum_tag_new", methods={"POST"})
     */
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $content = $request->getContent();
        
        $tag = $serializer->deserialize($content, ForumTag::class, 'json');
        
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userRoles = $user->getRoles();
        foreach ($userRoles as $key => $value) {
            if ($value == 'ROLE_ADMIN') {
                $admin = $user;
            }
        }
        if ($admin) {            
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
    public function edit(Garden $garden, Request $request, ForumTag $forumTag, EntityManagerInterface $entityManager, ValidatorInterface $validator, SerializerInterface $serializer): Response
    {
        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {
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
    public function delete(Garden $garden, ObjectManager $objectManager, ForumTag $forumTag): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        $gardenMembers = $garden->getUsers();
        
        $userRoles = $user->getRoles();
        //dd($userRoles);
        foreach ($userRoles as $key => $value) {
            if ($value = 'ROLE_ADMIN') { 
                $admin = $user; 
            }
        }
        
        foreach ($gardenMembers as $member) {
            if ($user == $member && $admin) {  

                $objectManager->remove($forumTag);
                $objectManager->flush();
                
                return JsonResponse::fromJsonString('Votre catégorie a été supprimée', 200);
            }
            return JsonResponse::fromJsonString('Vous n\'êtes pas autorisé à supprimer cette catégorie', 400);
        }
    }
}