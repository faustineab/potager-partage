<?php

namespace App\Controller;

use App\Entity\ForumQuestion;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ForumQuestionRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/forum/question")
 */
class ForumQuestionController extends AbstractController
{
    /**
     * @Route("/", name="forum_question_index", methods={"GET"})
     */
    public function index(SerializerInterface $serializer, ForumQuestionRepository $forumQuestionRepository): Response
    {
        $questions = $forumQuestionRepository->findAll();
        $jsonQuestions = $serializer->serialize($questions, 'json',['groups' => 'forum_questions']);
 
        return JsonResponse::fromJsonString($jsonQuestions);
    }

    /**
     * @Route("/new", name="forum_question_new", methods={"POST"})
     */
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $content = $request->getContent();
        
        $question = $serializer->deserialize($content, ForumQuestion::class, 'json');
        
        $errors = $validator->validate($question);
        if (count($errors) > 0)
            {
                foreach ($errors as $error) 
                {
                    return new JsonResponse(
                        'message: Votre question comporte des erreurs : '.$error.'.', 
                        406);
                }
            }
        
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $question->setUser($user);
        
        $entityManager->persist($question);
        $entityManager->flush();
        
        return new JsonResponse('message: Votre question a été posée', 200);
    }

    /**
     * @Route("/{id}", name="forum_question_show", methods={"GET"})
     */
    public function show(ForumQuestion $question, Request $request, SerializerInterface $serializer): Response
    {
        $jsonQuestion = $serializer->serialize($question, 'json',
            ['groups' => 'forum_questions']
        );
 
        return JsonResponse::fromJsonString($jsonQuestion);
    }

    /**
     * @Route("/{id}/edit", name="forum_question_edit", methods={"PUT"})
     */
    public function edit(Request $request, ForumQuestion $forumQuestion, EntityManagerInterface $entityManager, ValidatorInterface $validator, SerializerInterface $serializer): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user == $forumQuestion->getUser())
        {
            $content = $request->getContent();

            $editedQuestion = $serializer->deserialize($content, ForumQuestion::class, 'json');
            // dd($editedQuestion);

            $errors = $validator->validate($editedQuestion);

            if (count($errors) > 0)
            {
                foreach ($errors as $error) 
                {
                    return new JsonResponse(
                        'message: Votre modification comporte des erreurs : '.$error.'.', 
                        304);
                }
            }

            $title = $editedQuestion->getTitle();
            if ($title != null)
            {
                $forumQuestion->setTitle($title);
            }
            
            $text = $editedQuestion->getText();
            if ($text != null)
            {
                $forumQuestion->setText($text);
            }

            $forumQuestion->setUpdatedAt(new \Datetime());

            foreach ($editedQuestion->getTags() as $editedTag) 
            {
                if ($editedTag = $forumQuestion->getTags()) {
                    $editedQuestion->addTag($editedTag);
                }
                if ($editedTag != $forumQuestion->getTags()) {
                    $editedQuestion->removeTag($editedTag);
                }
            }
            
            $entityManager->merge($forumQuestion);
            $entityManager->persist($forumQuestion);
            $entityManager->flush();

            return new JsonResponse('message: Votre question a été modifiée', 200);
        }

        return new JsonResponse('message: Vous n\'êtes pas autorisé à modifier cette question', 403);
    }

    /**
     * @Route("/{id}", name="forum_question_delete", methods={"DELETE"})
     */
    public function delete(ObjectManager $objectManager, ForumQuestion $forumQuestion): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user = $forumQuestion->getUser()) {
            $objectManager->remove($forumQuestion);
            $objectManager->flush();
        }

        return new JsonResponse('message: Votre question a été supprimée', 200);
    }
}