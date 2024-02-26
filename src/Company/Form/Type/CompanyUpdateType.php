<?php

namespace App\Company\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CompanyUpdateType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('PUT')
        ->add('name', TextType::class, ['label' => 'name'])
        ->add('city', TextType::class, ['label' => 'city'])
        ->add('street', TextType::class, ['label' => 'street'])
        ->add('postcode', TextType::class, ['label' => 'postcode'])
        ->add('submit', SubmitType::class, ['label' => 'save']);
  }
}