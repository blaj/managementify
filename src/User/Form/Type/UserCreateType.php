<?php

namespace App\User\Form\Type;

use App\Client\Form\Type\ClientChoiceType;
use App\Specialist\Form\Type\SpecialistChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserCreateType extends AbstractType {

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver
        ->setRequired('companyId')
        ->setAllowedTypes('companyId', 'int');
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('POST')
        ->add('username', TextType::class, ['label' => 'username'])
        ->add('email', TextType::class, ['label' => 'email'])
        ->add('password', PasswordType::class, ['label' => 'password'])
        ->add(
            'roleId',
            RoleChoiceType::class,
            ['companyId' => $options['companyId'], 'label' => 'role', 'required' => false])
        ->add(
            'specialistId',
            SpecialistChoiceType::class,
            ['companyId' => $options['companyId'], 'label' => 'specialist', 'required' => false])
        ->add(
            'clientId',
            ClientChoiceType::class,
            ['companyId' => $options['companyId'], 'label' => 'client', 'required' => false])
        ->add('submit', SubmitType::class, ['label' => 'save']);
  }
}