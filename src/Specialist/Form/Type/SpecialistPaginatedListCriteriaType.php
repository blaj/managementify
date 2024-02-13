<?php

namespace App\Specialist\Form\Type;

use App\Common\Form\Type\PageCriteriaType;
use App\Common\Form\Type\SortType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SpecialistPaginatedListCriteriaType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('GET')
        ->add('filter', SpecialistPaginatedListFilterType::class)
        ->add('sort', SortType::class)
        ->add('pageCriteria', PageCriteriaType::class)
        ->add('submit', SubmitType::class, ['label' => 'filter']);
  }
}