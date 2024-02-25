<?php

namespace App\Client\Form\Type;

use App\ClientSpecialist\Entity\AssignType;
use App\Specialist\Form\Type\SpecialistChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientSpecialistCreateType extends AbstractType {

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver
        ->setRequired('companyId')
        ->setAllowedTypes('companyId', 'int');
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('POST')
        ->add('fromDate', DateType::class, ['label' => 'from-date', 'required' => false])
        ->add('toDate', DateType::class, ['label' => 'to-date', 'required' => false])
        ->add(
            'assignType',
            EnumType::class,
            ['class' => AssignType::class, 'label' => 'assign-type'])
        ->add(
            'specialistId',
            SpecialistChoiceType::class,
            ['companyId' => $options['companyId'], 'label' => 'specialist'])
        ->add('submit', SubmitType::class, ['label' => 'save']);
  }
}