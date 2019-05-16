<?php

namespace App\Controller;

use App\Entity\Garden;
use App\Entity\ForumQuestion;
use App\Entity\ForumTag;
use App\Repository\ForumTagRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/garden/{gardenid}/forum/question")
 * @ParamConverter("garden", options={"id" = "gardenid"})
 */
class ForumQuestionController extends AbstractController
{
    /**
     * @Route("/", name="forum_question_index", methods={"GET"})
     */
    public function index(Garden $garden, SerializerInterface $serializer): Response
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $gardenMembers = $garden->getUsers();
        foreach ($gardenMembers as $gardenMember) {
            if ($currentUser == $gardenMember) {
                $questions = $garden->getForumQuestions();
                $jsonQuestions = $serializer->serialize($questions, 'json', [
                    'groups' => 'forum_questions',
                    'circular_reference_handler' => function ($questions) {
                        return $questions->getId();
                    },
                ]);
                    
                return JsonResponse::fromJsonString($jsonQuestions);
            }
            return JsonResponse::fromJsonString('Vous ne faites pas partie de ce potager', 400);
        }
    }

    /**
     * @Route("/new", name="forum_question_new", methods={"POST"})
     */
    public function new(Garden $garden, Request $request, ForumTagRepository $tagRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $gardenMembers = $garden->getUsers();
        foreach ($gardenMembers as $gardenMember) {
            if ($currentUser == $gardenMember) {
                
                $question = json_decode($request->getContent(), true);
                
                $errors = $validator->validate($question);
                if (count($errors) > 0) {
                    foreach ($errors as $error) {
                        return JsonResponse::fromJsonString(
                            'message: Votre question comporte des erreurs : ' . $error . '.',
                            406
                        );
                    }
                }
                
                $newQuestion = new ForumQuestion();
                
                $tag = $tagRepository->findOneBy(['name' => $question['tag']]);
                if ($tag == null) {
                    $tag = new ForumTag();
                    $tag->setName($question['tag'])
                    ->setGarden($garden);
                    
                    $entityManager->persist($tag);
                };
                
                $newQuestion->setUser($currentUser)
                            ->setTitle($question['title'])
                            ->setText($question['text'])
                            ->addTag($tag)
                            ->setGarden($garden);
                
                $entityManager->persist($newQuestion);
                $entityManager->flush();
                
                return JsonResponse::fromJsonString('Votre question a été posée', 200);
            }
            return JsonResponse::fromJsonString('Vous ne faites pas partie de ce potager', 400);
        }
    }

    /**
     * @Route("/{id}", name="forum_question_show", methods={"GET"})
     */
    public function show(Garden $garden, ForumQuestion $question, SerializerInterface $serializer): Response
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $gardenMembers = $garden->getUsers();
        foreach ($gardenMembers as $gardenMember) {
            if ($currentUser == $gardenMember) {
                $jsonQuestion = $serializer->serialize(
                    $question,
                    'json',
                    ['groups' => 'forum_questions',
                    'circular_reference_handler' => function ($questions) {
                        return $questions->getId();
                    },
                    ]
                );
                
                return JsonResponse::fromJsonString($jsonQuestion);
            }
            return JsonResponse::fromJsonString('Vous ne faites pas partie de ce potager', 400);
        }
    }

    /**
     * @Route("/{id}/edit", name="forum_question_edit", methods={"PUT"})
     */
    public function edit(Garden $garden, Request $request, ForumQuestion $question, EntityManagerInterface $entityManager, ValidatorInterface $validator, ForumTagRepository $tagRepository): Response
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $gardenMembers = $garden->getUsers();
        $questionOwner = $question->getUser();
        $userRoles = $currentUser->getRoles();
        foreach ($userRoles as $key => $value) {
            if ($value == 'ROLE_ADMIN') {
                $admin = $currentUser;
            }
        }

        foreach ($gardenMembers as $gardenMember) {
            
            if ($currentUser == $gardenMember && $currentUser == $questionOwner || $admin) {

                $editedQuestion = json_decode($request->getContent(), true);
                
                $errors = $validator->validate($editedQuestion);       
                if (count($errors) > 0) {
                    foreach ($errors as $error) {
                        return JsonResponse::fromJsonString(
                            'message: Votre modification comporte des erreurs : ' . $error . '.',
                            304
                        );
                    }
                }
                
                if (isset($editedQuestion['title'])) {
                    $question->setTitle($editedQuestion['title']);
                }
                
                if (isset($editedQuestion['text'])) {
                    $question->setText($editedQuestion['text']);
                }
                
                $question->setUpdatedAt(new \Datetime());
                
                if (isset($editedQuestion['tag']) || $editedQuestion['tag'] !== $question->getTags()) {
                    
                    $tag = $tagRepository->findOneBy(['name' => $editedQuestion['tag']]);
                    
                    if ($tag == null) {
                        $tag = new ForumTag();
                        $tag->setName($editedQuestion['tag'])
                            ->setGarden($garden);
                        
                        $entityManager->persist($tag);
                    };
                    
                    $question->addTag($tag);
                }
                
                $entityManager->merge($question);
                $entityManager->persist($question);
                $entityManager->flush();
                
                return JsonResponse::fromJsonString('message: Votre question a été modifiée', 200);
            }
            
            return JsonResponse::fromJsonString('Vous n\'êtes pas autorisé à modifier cette question', 403);
        }
    }

    /**
     * @Route("/{id}", name="forum_question_delete", methods={"DELETE"})
     */
    public function delete(Garden $garden, ObjectManager $objectManager, ForumQuestion $question): Response
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $gardenMembers = $garden->getUsers();
        $questionOwner = $question->getUser();        
        $userRoles = $currentUser->getRoles();
        foreach ($userRoles as $key => $value) {
            if ($value == 'ROLE_ADMIN') {
                $admin = $currentUser;
            }
        }

        foreach ($gardenMembers as $gardenMember) {
            
            if ($currentUser == $gardenMember && $currentUser == $questionOwner || $admin) {
                
                foreach ($question->getTags() as $tag) {
                    $question->removeTag($tag);
                }
                $objectManager->remove($question);
                $objectManager->flush();
                
                return JsonResponse::fromJsonString('La question a été supprimée', 200);
            }
                
            return JsonResponse::fromJsonString('message: Vous n\'êtes pas autorisé à supprimer cette question', 406);
        }
    }
}
