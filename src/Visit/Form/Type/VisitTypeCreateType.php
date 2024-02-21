<?php

namespace App\Visit\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class VisitTypeCreateType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('POST')
        ->add('code', TextType::class, ['label' => 'code'])
        ->add('name', TextType::class, ['label' => 'name'])
        ->add(
            'preferredPrice',
            MoneyType::class,
            ['label' => 'preferred-price', 'required' => false])
        ->add('submit', SubmitType::class, ['label' => 'save']);
  }
}