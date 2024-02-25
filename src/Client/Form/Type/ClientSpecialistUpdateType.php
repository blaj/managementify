<?php

namespace App\Client\Form\Type;

use App\ClientSpecialist\Entity\AssignType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ClientSpecialistUpdateType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('PUT')
        ->add('fromDate', DateType::class, ['label' => 'from-date', 'required' => false])
        ->add('toDate', DateType::class, ['label' => 'to-date', 'required' => false])
        ->add(
            'assignType',
            EnumType::class,
            ['class' => AssignType::class, 'label' => 'assign-type'])
        ->add('submit', SubmitType::class, ['label' => 'save']);
  }
}