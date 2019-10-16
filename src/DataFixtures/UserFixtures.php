<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('poulpe');
        $user->setNom('Paul Le Poulpe');
        $user->setPrenom('Paul');
        $user->setBio('Je gère tout');
        $user->setRoles(['ROLE_USER','ROLE_ADMIN']);
        $password = "123456789";
        $password =  $this->userPasswordEncoder->encodePassword($user,$password);
        $user->setPassword($password);
        $user->setGithub('faites-le');
        $user->setImage('marie-ico.jpg');
        $user->setEmail('paul@gmail.com');

        $manager->persist($user);
        $manager->flush();
    }
}
