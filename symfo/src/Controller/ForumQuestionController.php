<?php

namespace App\Controller;

use App\Entity\ForumQuestion;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ForumQuestionRepository;
use Symfony\Component\HttpFoundation\Request;
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
        $jsonQuestions = $serializer->serialize($questions, 'json',
            ['groups' => 'forum_questions']
        );
 
        return JsonResponse::fromJsonString($jsonQuestions);
    }

    /**
     * @Route("/new", name="forum_question_new", methods={"PUT"})
     */
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $content = $request->getContent();
        
        $question = $this->get('serializer')->deserialize($content, ForumQuestion::class, 'json');
        
        $errors = $validator->validate($question);
        if (count($errors) > 0) {
            dd($errors);
        }
        
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $question->setUser($user);
        
        $entityManager->persist($question);
        $entityManager->flush();
        
        return new JsonResponse('message: Votre question a été posée');

    }

    // /**
    //  * @Route("/{id}", name="garden_show", methods={"GET"})
    //  */
    // public function show(Garden $garden): Response
    // {
    //     return $this->render('garden/show.html.twig', [
    //         'garden' => $garden,
    //     ]);
    // }

    // /**
    //  * @Route("/{id}/edit", name="garden_edit", methods={"GET","POST"})
    //  */
    // public function edit(Request $request, Garden $garden): Response
    // {
    //     $form = $this->createForm(GardenType::class, $garden);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->getDoctrine()->getManager()->flush();

    //         return $this->redirectToRoute('garden_index', [
    //             'id' => $garden->getId(),
    //         ]);
    //     }

    //     return $this->render('garden/edit.html.twig', [
    //         'garden' => $garden,
    //         'form' => $form->createView(),
    //     ]);
    // }

    // /**
    //  * @Route("/{id}", name="garden_delete", methods={"DELETE"})
    //  */
    // public function delete(Request $request, Garden $garden): Response
    // {
    //     if ($this->isCsrfTokenValid('delete' . $garden->getId(), $request->request->get('_token'))) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->remove($garden);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('garden_index');
    // }
}
