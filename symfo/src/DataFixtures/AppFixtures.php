<?php
namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Garden;
use App\Entity\ForumTag;
use App\Entity\ForumAnswer;
use App\Entity\ForumQuestion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('us_US');

        $adminRole = new Role();
        $adminRole->setName('ROLE_ADMIN');
        $adminRole->setLabel('Administrateur');
        $manager->persist($adminRole);
        $adminUser = new User();
        $adminUser->setName('admin')
            ->setEmail('admin@gmail.com')
            ->setPassword($this->encoder->encodePassword($adminUser, 'administrateur'))
            ->setPhone('0662656869')
            ->setAddress('148 rue de charenton 33120 Arcachon')
            ->setStatut('ok')
            ->addRole($adminRole);
        $manager->persist($adminUser);

        $moderatorRole = new Role();
        $moderatorRole->setName('ROLE_USER');
        $moderatorRole->setLabel('Utilisateur');
        $manager->persist($moderatorRole);
        $moderatorUser = new User();
        $moderatorUser->setName('user')
            ->setEmail('user@gmail.com')
            ->setPassword($this->encoder->encodePassword($moderatorUser, 'utilisateur'))
            ->setPhone('0662656869')
            ->setAddress('148 rue de charenton 33120 Arcachon')
            ->setStatut('en attente')
            ->addRole($moderatorRole);

        $manager->persist($moderatorUser);

        //  Création des tags
        $tags = [];

        for ($t = 1; $t <= 6; $t++) {
            $tag = new ForumTag();
            $name = $faker->word();
            $tag->setName($name);
            $manager->persist($tag);
            $tags[] = $tag;
        }
        $manager->flush();

        $users = [];
        // Création des users
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $name = $faker->name();
            $email = $faker->email();
            $address = $faker->address();
            $password = $this->encoder->encodePassword($user, 'password');
            $user->setName($name)
                ->setEmail($email)
                ->setPassword($password)
                ->setPhone('0663626567')
                ->setAddress($address)
                ->setStatut('ok');

            $manager->persist($user);
            $users[] = $user;

            $garden = new Garden();
            $garden->setName('les planteurs fous');
            $garden->setAddress('10 rue des Oliviers');
            $garden->setZipcode('16120');
            $garden->setCity('Chateauneuf');
            $garden->setMeters('100');
            $garden->setNumberPlotsRow('5');
            $garden->setNumberPlotsColumn('4');
            $garden->addUser($moderatorUser);
            $manager->persist($garden);
        }
        $manager->flush();

        //  Création des questions
        for ($j = 1; $j <= 10; $j++) {
            $question = new ForumQuestion();

            $title = $faker->sentence();
            $description = $faker->paragraph(2);
            $createdAt = $faker->dateTimeBetween($startDate = '-1 year', $endDate = 'now');
            $user = $users[mt_rand(0, count($users) - 1)];
            $tag1 = $tags[mt_rand(0, count($tags) - 1)];
            $tag2 = $tags[mt_rand(0, count($tags) - 1)];

            $question->setTitle($title)
                ->setText($description)
                ->setCreatedAt($createdAt)
                ->setUser($user)
                ->addTag($tag1)
                ->addTag($tag2);
            // ->setStatus($faker->optional($weight:0.9)->randomDigit);
            $manager->persist($question);
            //  Création des réponses
            for ($k = 1; $k <= (mt_rand(2, 3)); $k++) {

                $response = new ForumAnswer();

                $user = $users[mt_rand(0, count($users) - 1)];
                $description = $faker->paragraph(2);

                $now = new \DateTime();
                $interval = $now->diff($question->getCreatedAt());
                $days = $interval->days;
                $minimumResponseDate = '-' . $days . 'days';

                $response->setUser($user)
                    ->setQuestion($question)
                    ->setText($description)
                    ->setCreatedAt($faker->dateTimeBetween($minimumResponseDate));
                // ->setStatus($faker->optional($weight:0.9)->randomDigit);
                $manager->persist($response);
            }
        }

        $manager->flush();
    }
}
