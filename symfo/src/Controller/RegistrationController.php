<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Garden;
use App\Repository\GardenRepository;
use App\Form\AdminType;
use App\Form\RegistrationUserType;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UserRepository;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register/admin", name="app_register")
     */
    public function registerAdmin(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $userGarden = [
            'user' => new User(),
            'garden' => new Garden()
        ];



        $form = $this->createForm(AdminType::class, $userGarden);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password

            $hashedPassword = $passwordEncoder->encodePassword($userGarden['user'], $userGarden['user']->getPassword());
            $userGarden['user']->setPassword($hashedPassword);
            //   $userGarden['user']->addRole('ROLE_ADMIN'); set rôle admin à faire 


            $userGarden['user']->setStatut('ok');

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userGarden['user']);
            $entityManager->persist($userGarden['garden']);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $userGarden['user'],
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/register/user", name="registration", methods={"GET","POST"})
     *  @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse

     */
    public function registerUser(GardenRepository $gardenRepository, Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, User $user = null, UserRepository $userRepository): Response
    {
        $content = $request->getContent();
        dump($content);

        $data = json_decode($request->getContent(), true);

        $validator = Validation::createValidator();

        $constraint = new Assert\Collection(array(
            // the keys correspond to the keys in the input array
            'name' => new Assert\Length(array('min' => 1)),
            'password' => new Assert\Length(array('min' => 1)),
            'email' => new Assert\Email(),
            'phone' => new Assert\Length(array('min' => 1)),
            'address' => new Assert\Length(array('min' => 1)),
            'gardenId' => new Assert\Length(array('min' => 1))
        ));

        $violations = $validator->validate($data, $constraint);

        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], 500);
        }

        $username = $data['name'];
        $password = $data['password'];
        $email = $data['email'];
        $phone = $data['phone'];
        $address = $data['address'];
        $gardenId = $data['gardenId'];


        $user = new User();
        $user->setStatut('à valider')
            ->setName($username)
            ->setPassword($password)
            ->setEmail($email)
            ->setPhone($phone)
            ->setAddress($address);

        $manager->persist($user);

        $garden = $gardenRepository->find($gardenId);

        $garden->addUser($user);

        $manager->persist($garden);
        $manager->flush();

        dump($garden);
        exit;


        $json = [
            'username' => $data['email'],
            'password' => $data['password']
        ];
        dump($json);
        exit;



        return $this->redirectToRoute('app_login', $json, 307);
    }
}
