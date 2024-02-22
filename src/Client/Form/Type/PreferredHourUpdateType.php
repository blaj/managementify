<?php

namespace App\Client\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;

class PreferredHourUpdateType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('PUT')
        ->add('fromTime', TimeType::class, ['label' => 'from-time'])
        ->add('toTime', TimeType::class, ['label' => 'to-time'])
        ->add('submit', SubmitType::class, ['label' => 'save']);
  }
}