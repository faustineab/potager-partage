<?php

namespace App\Controller;

use App\Entity\Garden;
use App\Entity\ForumAnswer;
use App\Entity\ForumQuestion;
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
 * @Route("/api/garden/{gardenid}/forum/question/{questionid}/answer")
 * @ParamConverter("garden", options={"id" = "gardenid"})
 * @ParamConverter("question", class="App\Entity\ForumQuestion", options={"id" = "questionid"})
 */
class ForumAnswerController extends AbstractController
{
    /**
     * @Route("/new", name="forum_answer_new", methods={"POST"})
     */
    public function new(Garden $garden, ForumQuestion $question, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {
                $content = $request->getContent();
                
                $answer = $serializer->deserialize($content, ForumAnswer::class, 'json');
                
                $errors = $validator->validate($answer);
                if (count($errors) > 0) {
                    foreach ($errors as $error) {
                        return JsonResponse::fromJsonString('message: Votre réponse comporte des erreurs : ' . $error . '.',406);
                    }
                }
                $currentUser = $this->get('security.token_storage')->getToken()->getUser();
                $answer->setQuestion($question);
                $answer->setUser($currentUser);
                
                $entityManager->persist($answer);
                $entityManager->flush();
                
                return JsonResponse::fromJsonString('message: Vous avez répondu à la question "' . $question->getTitle() . '"', 200);
            }
            return JsonResponse::fromJsonString('Vous n\'êtes pas autorisé à répondre à cette question', 400);
        
    }

    /**
     * @Route("/{id}/edit", name="forum_answer_edit", methods={"PUT"})
     */
    public function edit(Garden $garden, Request $request, ForumAnswer $forumAnswer, EntityManagerInterface $entityManager, ValidatorInterface $validator, SerializerInterface $serializer): Response
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $gardenMembers = $garden->getUsers();
        $answerOwner = $forumAnswer->getUser();
        $userRoles = $currentUser->getRoles();
        foreach ($userRoles as $key => $value) {
            if ($value == 'ROLE_ADMIN') {
                $admin = $currentUser;
            }
        }
        
        foreach ($gardenMembers as $gardenMember) {
            
            if ($currentUser == $gardenMember && $currentUser == $answerOwner || $admin) {
                dd($admin);
                $content = $request->getContent();
                
                $editedAnswer = $serializer->deserialize($content, ForumAnswer::class, 'json');
                
                $errors = $validator->validate($editedAnswer);
                
                if (count($errors) > 0) {
                    foreach ($errors as $error) {
                        return JsonResponse::fromJsonString(
                            'message: Votre modification comporte des erreurs : ' . $error . '.',
                            304
                        );
                    }
                }
                
                $text = $editedAnswer->getText();
                if ($text != null) {
                    $forumAnswer->setText($text);
                }
                
                $forumAnswer->setUpdatedAt(new \Datetime());
                
                $entityManager->merge($forumAnswer);
                $entityManager->persist($forumAnswer);
                $entityManager->flush();
                
                return JsonResponse::fromJsonString('message: Votre réponse a été modifiée', 200);
            }
            
            return JsonResponse::fromJsonString('message: Vous n\'êtes pas autorisé à modifier cette réponse', 403);
        }
    }

    /**
     * @Route("/{id}", name="forum_answer_delete", methods={"DELETE"})
     */
    public function delete(Garden $garden, ObjectManager $objectManager, ForumAnswer $answer): Response
    {
        $answerUser = $answer->getUser();

        //$userRole= $userEntity->getRoles();

        $currentUser= $this->get('security.token_storage')->getToken()->getUser();


        if ($answerUser == $currentUser) {
                
                $objectManager->remove($answer);
                $objectManager->flush();
                
                return JsonResponse::fromJsonString('La question a été supprimée', 200);
            }
                
            return JsonResponse::fromJsonString('message: Vous n\'êtes pas autorisé à supprimer cette question', 406);
        
        }
}
