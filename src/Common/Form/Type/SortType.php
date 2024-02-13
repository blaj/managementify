<?php

namespace App\Common\Form\Type;

use App\Common\PaginatedList\Dto\Order;
use App\Common\PaginatedList\Dto\Sort;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortType extends AbstractType {

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults([
        'data_class' => Sort::class
    ]);
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->add(
            'order',
            EnumType::class,
            ['class' => Order::class, 'required' => false])
        ->add(
            'by',
            TextType::class,
            ['required' => false]);
  }
}