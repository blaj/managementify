<?php

namespace App\User\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserRegisterType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('POST')
        ->add('username', TextType::class, ['label' => 'username'])
        ->add('email', EmailType::class, ['label' => 'email'])
        ->add(
            'password',
            RepeatedType::class,
            [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'password'],
                'second_options' => ['label' => 're-password']])
        ->add('companyName', TextType::class, ['label' => 'company-name'])
        ->add('companyCity', TextType::class, ['label' => 'city'])
        ->add('companyStreet', TextType::class, ['label' => 'street'])
        ->add('companyPostcode', TextType::class, ['label' => 'postcode'])
        ->add('submit', SubmitType::class, ['label' => 'register']);
  }
}