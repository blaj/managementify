<?php

namespace App\Specialist\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SpecialistUpdateType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('PUT')
        ->add('firstname', TextType::class, ['label' => 'firstname'])
        ->add('surname', TextType::class, ['label' => 'surname'])
        ->add('foreignId', TextType::class, ['label' => 'foreign-id', 'required' => false])
        ->add('submit', SubmitType::class, ['label' => 'save']);
  }
}