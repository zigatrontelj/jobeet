<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('user');
        $user->setEmail('user@email.org');
        $password = $this->encoder->encodePassword($user, 'user');
        $user->setPassword($password);
        $user->setRoles(['ROLE_USER']);

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@email.org');
        $password = $this->encoder->encodePassword($user, 'admin');
        $admin->setPassword($password);
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);
        $manager->persist($admin);

        $manager->flush();
    }
}
