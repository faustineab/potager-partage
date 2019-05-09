<?php

namespace App\Controller;

use App\Entity\ForumAnswer;
use App\Entity\ForumQuestion;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ForumAnswerRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/forum/question/{id}/answer")
 */
class ForumAnswerController extends AbstractController
{
    /**
     * @Route("/new", name="forum_answer_new", methods={"POST"})
     */
    public function new(ForumQuestion $question, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $content = $request->getContent();
        
        $answer = $serializer->deserialize($content, ForumAnswer::class, 'json');
        
        $errors = $validator->validate($answer);
        if (count($errors) > 0)
            {
                foreach ($errors as $error) 
                {
                    return new JsonResponse(
                        'message: Votre réponse comporte des erreurs : '.$error.'.', 
                        406);
                }
            }
        
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $answer->setQuestion($question);
        $answer->setUser($user);
        
        $entityManager->persist($answer);
        $entityManager->flush();
        
        return new JsonResponse('message: Vous avez répondu à la question "'.$question->getTitle().'"', 200);
    }

    // /**
    //  * @Route("/{id}/edit", name="forum_question_edit", methods={"PUT"})
    //  */
    // public function edit(Request $request, ForumQuestion $forumQuestion, EntityManagerInterface $entityManager, ValidatorInterface $validator, SerializerInterface $serializer): Response
    // {
    //     $user = $this->get('security.token_storage')->getToken()->getUser();

    //     if ($user == $forumQuestion->getUser())
    //     {
    //         $content = $request->getContent();

    //         $editedQuestion = $serializer->deserialize($content, ForumQuestion::class, 'json');
    //         // dd($editedQuestion);

    //         $errors = $validator->validate($editedQuestion);

    //         if (count($errors) > 0)
    //         {
    //             foreach ($errors as $error) 
    //             {
    //                 return new JsonResponse(
    //                     'message: Votre modification comporte des erreurs : '.$error.'.', 
    //                     304);
    //             }
    //         }

    //         $title = $editedQuestion->getTitle();
    //         if ($title != null)
    //         {
    //             $forumQuestion->setTitle($title);
    //         }
            
    //         $text = $editedQuestion->getText();
    //         if ($text != null)
    //         {
    //             $forumQuestion->setText($text);
    //         }

    //         $forumQuestion->setUpdatedAt(new \Datetime());

    //         foreach ($editedQuestion->getTags() as $editedTag) 
    //         {
    //             if ($editedTag = $forumQuestion->getTags()) {
    //                 $editedQuestion->addTag($editedTag);
    //             }
    //             if ($editedTag != $forumQuestion->getTags()) {
    //                 $editedQuestion->removeTag($editedTag);
    //             }
    //         }
            
    //         $entityManager->merge($forumQuestion);
    //         $entityManager->persist($forumQuestion);
    //         $entityManager->flush();

    //         return new JsonResponse('message: Votre question a été modifiée', 200);
    //     }

    //     return new JsonResponse('message: Vous n\'êtes pas autorisé à modifier cette question', 403);
    // }

    // /**
    //  * @Route("/{id}", name="forum_question_delete", methods={"DELETE"})
    //  */
    // public function delete(ObjectManager $objectManager, ForumQuestion $forumQuestion): Response
    // {
    //     $user = $this->get('security.token_storage')->getToken()->getUser();

    //     if ($user = $forumQuestion->getUser()) {
    //         $objectManager->remove($forumQuestion);
    //         $objectManager->flush();
    //     }

    //     return new JsonResponse('message: Votre question a été supprimée', 200);
    // }
}