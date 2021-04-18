<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->passwordEncoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // Создание пользователя с правами администратора
        $adminUser = new User();
        $adminUser->setEmail("admin@admin.ru");
        $adminUser->setPassword($this->passwordEncoder->encodePassword($adminUser, 'hard_admin_passw0rd!'));
        $adminUser->setLastName("Админин");
        $adminUser->setFirstName("Админ");
        $adminUser->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        // Создание пользователя с обычными правами
        $basicUser = new User();
        $basicUser->setEmail("example@mail.ru");
        $basicUser->setPassword($this->passwordEncoder->encodePassword($basicUser, 'Qwerty123!'));
        $basicUser->setLastName("Иванов");
        $basicUser->setFirstName("Петр");
        $basicUser->setRoles(['ROLE_USER']);
        // Добавим пользователей в базу данных
        $manager->persist($adminUser);
        $manager->persist($basicUser);
        // Подтверждаем изменения
        $manager->flush();
    }
}
