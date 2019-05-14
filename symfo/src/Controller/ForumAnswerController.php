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
 * @Route("/api/forum/question/{questionid}/answer")
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
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                return JsonResponse::fromJsonString(
                    'message: Votre réponse comporte des erreurs : ' . $error . '.',
                    406
                );
            }
        }

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $answer->setQuestion($question);
        $answer->setUser($user);

        $entityManager->persist($answer);
        $entityManager->flush();

        return JsonResponse::fromJsonString('message: Vous avez répondu à la question "' . $question->getTitle() . '"', 200);
    }

    /**
     * @Route("/{id}/edit", name="forum_answer_edit", methods={"PUT"})
     */
    public function edit(Request $request, ForumAnswer $forumAnswer, EntityManagerInterface $entityManager, ValidatorInterface $validator, SerializerInterface $serializer): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            $admin = $user;
        }

        if ($user == $forumAnswer->getUser() || $admin) {
            $content = $request->getContent();

            $editedAnswer = $serializer->deserialize($content, ForumAnswer::class, 'json');
            // dd($editedAnswer);

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

    /**
     * @Route("/{id}", name="forum_answer_delete", methods={"DELETE"})
     */
    public function delete(ObjectManager $objectManager, ForumAnswer $forumAnswer): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            $admin = $user;
        }

        if ($user = $forumAnswer->getUser() || $admin) {
            $objectManager->remove($forumAnswer);
            $objectManager->flush();

            return JsonResponse::fromJsonString('message: Votre réponse a été supprimée', 200);
        }

        return JsonResponse::fromJsonString('message: Vous n\'êtes pas autorisé à supprimer cette réponse', 406);
    }
}
