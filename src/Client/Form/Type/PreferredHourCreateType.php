<?php

namespace App\Client\Form\Type;

use App\Common\Entity\DayOfWeek;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;

class PreferredHourCreateType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('POST')
        ->add('fromTime', TimeType::class, ['label' => 'from-time'])
        ->add('toTime', TimeType::class, ['label' => 'to-time'])
        ->add(
            'dayOfWeeks',
            EnumType::class,
            ['class' => DayOfWeek::class, 'label' => 'day-of-week', 'multiple' => true])
        ->add('submit', SubmitType::class, ['label' => 'save']);
  }
}