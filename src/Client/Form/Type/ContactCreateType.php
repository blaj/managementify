<?php

namespace App\Client\Form\Type;

use App\Client\Entity\ContactType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactCreateType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('POST')
        ->add('content', TextType::class, ['label' => 'content'])
        ->add(
            'type',
            EnumType::class,
            ['class' => ContactType::class, 'label' => 'type'])
        ->add('submit', SubmitType::class, ['label' => 'save']);
  }
}