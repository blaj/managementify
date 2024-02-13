<?php

namespace App\Specialist\Form\Type;

use App\Specialist\Dto\SpecialistPaginatedListFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpecialistPaginatedListFilterType extends AbstractType {

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults(['data_class' => SpecialistPaginatedListFilter::class]);
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->add('search', TextType::class, ['label' => '', 'required' => false]);
  }
}