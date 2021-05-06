<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Имя должно быть более 3 символов!',
                        'max' => 255,
                    ]),
                    new Regex([
                        'match' => true,
                        'pattern' => '/^[а-яё -]+$/ui',
                        'message' => 'Для имени допустимы только русские буквы, пробелы и дефисы',
                    ]),
                ],
                'label' => 'Имя',
                'attr' => [
                    'class' => 'validate',
                ],
            ])
            ->add('lastName', null, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Фамилия должна быть более 3 символов!',
                        'max' => 255,
                    ]),
                    new Regex([
                        'match' => true,
                        'pattern' => '/^[а-яё -]+$/ui',
                        'message' => 'Для фамилии допустимы только русские буквы, пробелы и дефисы',
                    ]),
                ],
                'label' => 'Фамилия',
                'attr' => [
                    'class' => 'validate',
                ],
            ])
            ->add('patronymic', null, [
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Отчество должна быть более 3 символов!',
                        'max' => 255,
                    ]),
                    new Regex([
                        'match' => true,
                        'pattern' => '/^[а-яё -]+$/ui',
                        'message' => 'Для отчества допустимы только русские буквы, пробелы и дефисы',
                    ]),
                ],
                'label' => 'Отчество',
                'attr' => [
                    'class' => 'validate',
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new Email([
                        'message' => 'Некорректный Email!',
                    ]),
                    new Length([
                        'max' => 180,
                    ]),
                ],
                'label' => 'Email',
                'attr' => [
                    'class' => 'validate',
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Необходимо согласиться с использованием персональных данных.',
                    ]),
                ],
                'label' => 'Я согласен с использованием моих персональных данных',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'required' => true,
                'mapped' => false,
                'type' => PasswordType::class,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Введите пароль',
                    ]),
                    new Regex([
                        'pattern' => '/[A-zА-я]+/',
                        'message' => 'Пароль должен содержать латинские буквы!',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Длина пароля должна быть от {{ limit }} символов',
                        'max' => 4096,
                    ]),
                ],
                'first_options' => [
                    'label' => 'Пароль',
                    'attr' => [
                        'class' => 'validate',
                    ],
                ],
                'second_options' => [
                    'label' => 'Повторите пароль',
                    'attr' => [
                        'class' => 'validate',
                    ],
                ],
                'invalid_message' => 'Пароли не совпадают!',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
