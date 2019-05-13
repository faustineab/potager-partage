<?php
namespace App\Controller;


use App\Entity\Plot;
use App\Entity\User;
use App\Entity\Garden;
use App\Form\AdminType;
use App\Form\RegistrationUserType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Repository\GardenRepository;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register/admin", name="app_register")
     */
    public function registerAdmin(Request $request, GardenRepository $gardenRepository, UserPasswordEncoderInterface $encoder, ObjectManager $manager, RoleRepository $roleRepository): Response
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
            'gardenName' => new Assert\Length(array('min' => 1)),
            'gardenAddress' => new Assert\Length(array('min' => 1)),
            'gardenZipCode' => new Assert\Length(array('min' => 1)),
            'gardenCity' => new Assert\Length(array('min' => 1)),
            'gardenSpecificities' => new Assert\Length(array('min' => 1)),
            'gardenMeters' => new Assert\Length(array('min' => 1)),
            'gardenPlots_Row' => new Assert\Length(array('min' => 1)),
            'gardenPlots_Column' => new Assert\Length(array('min' => 1)),

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
        $gardenName = $data['gardenName'];
        $gardenAddress = $data['gardenAddress'];
        $gardenZipCode = $data['gardenZipCode'];
        $gardenCity = $data['gardenCity'];
        $gardenSpecificities = $data['gardenSpecificities'];
        $gardenMeters = $data['gardenMeters'];
        $gardenPlots_Row = $data['gardenPlots_Row'];
        $gardenPlots_Column = $data['gardenPlots_Column'];


        $user = new User();
        $hashedPassword = $encoder->encodePassword($user, $password);

        $user->setStatut('ok')
            ->setName($username)
            ->setPassword($hashedPassword)
            ->setEmail($email)
            ->setPhone($phone)
            ->setAddress($address);


        $manager->persist($user);
        dump($user);


        $garden = new Garden();
        $garden->setName($gardenName)
            ->setAddress($gardenAddress)
            ->setZipCode($gardenZipCode)
            ->setCity($gardenCity)
            ->setAddressSpecificities($gardenSpecificities)
            ->setMeters($gardenMeters)
            ->setNumberPlotsRow($gardenPlots_Row)
            ->setNumberPlotsColumn($gardenPlots_Column);


        $manager->persist($garden);
        $manager->flush();
        
        $gardenId = $garden->getId();

        $garden = $gardenRepository->find($gardenId);

        $garden->addUser($user);

        $manager->persist($garden);


        $garden = $gardenRepository->findOneBy([
            'name' => $gardenName
        ]);

        $garden->addUser($user);
     
        $plots =  $gardenPlots_Row * $gardenPlots_Column;
       

        for ($p = 0; $p < $plots; $p++) {
            $plot = new Plot();
            $manager->persist($plot);

            $plot->setStatus('inactif');
            $garden->addPlot($plot);
        }

        $manager->persist($plot);
        $manager->persist($garden);
        // dump($garden);
        // exit;



        $role = $roleRepository->findOneBy([
            'label' => 'Administrateur'
        ]);
        $role->addUser($user);

        $manager->persist($role);
        $manager->flush();
        dump($role);

        $credentials = [
            'username' => $data['email'],
            'password' => $data['password']
        ];
        dump($credentials);

        return  new JsonResponse($credentials);
    }

    /**
     * @Route("/register/user", name="registration_get", methods={"GET"})
     *  @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse

     */
    public function registerUserGet(GardenRepository $gardenRepository, Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, User $user = null, UserRepository $userRepository, SerializerInterface $serializer): Response
    {
        $gardens = $gardenRepository->findAll();
        // dump($gardens);

        $data = $serializer->serialize($gardens, 'json', ['groups' => ['garden_register']]);

        return new Response($data);
    }


    /**
     * @Route("/register/user", name="registration", methods={"POST"})
     *  @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function registerUser(GardenRepository $gardenRepository, Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, User $user = null, UserRepository $userRepository): Response
    {

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
        $hashedPassword = $encoder->encodePassword($user, $password);

        $user->setStatut('à valider')
            ->setName($username)
            ->setPassword($hashedPassword)
            ->setEmail($email)
            ->setPhone($phone)
            ->setAddress($address);


        $manager->persist($user);

        $garden = $gardenRepository->find($gardenId);

        $garden->addUser($user);

        $manager->persist($garden);
        $manager->flush();

        dump($garden);

        $credentials = [
            'username' => $data['email'],
            'password' => $data['password']
        ];
        dump($credentials);

        return  new JsonResponse($credentials);
    }
}
