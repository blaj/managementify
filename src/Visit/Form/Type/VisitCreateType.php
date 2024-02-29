<?php

namespace App\Visit\Form\Type;

use App\Client\Form\Type\ClientChoiceType;
use App\Specialist\Form\Type\SpecialistChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisitCreateType extends AbstractType {

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver
        ->setRequired('companyId')
        ->setAllowedTypes('companyId', 'int');
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('POST')
        ->add('date', DateType::class, ['label' => 'date', 'disabled' => true])
        ->add('fromTime', TimeType::class, ['label' => 'from-time'])
        ->add('toTime', TimeType::class, ['label' => 'to-time'])
        ->add(
            'specialistId',
            SpecialistChoiceType::class,
            ['label' => 'specialist', 'companyId' => $options['companyId'], 'disabled' => true])
        ->add(
            'clientId',
            ClientChoiceType::class,
            ['label' => 'client', 'companyId' => $options['companyId']])
        ->add('note', TextareaType::class, ['label' => 'note', 'required' => false])
        ->add(
            'visitTypeId',
            VisitTypeChoiceType::class,
            ['label' => 'visit-type', 'companyId' => $options['companyId'], 'required' => false])
        ->add('submit', SubmitType::class, ['label' => 'save']);
  }
}