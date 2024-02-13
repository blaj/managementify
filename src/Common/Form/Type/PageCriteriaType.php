<?php

namespace App\Common\Form\Type;

use App\Common\PaginatedList\Dto\PageCriteria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageCriteriaType extends AbstractType {

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults([
        'data_class' => PageCriteria::class
    ]);
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->add(
            'no',
            NumberType::class,
            ['empty_data' => PageCriteria::$defaultNo, 'required' => false])
        ->add(
            'size',
            NumberType::class,
            ['empty_data' => PageCriteria::$defaultSize, 'required' => false]);
  }
}