<?php

namespace App\User\Form\Type;

use App\User\Entity\PermissionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RoleCreateType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('POST')
        ->add('code', TextType::class, ['label' => 'code'])
        ->add('name', TextType::class, ['label' => 'name'])
        ->add(
            'permissionTypes',
            EnumType::class,
            [
                'class' => PermissionType::class,
                'label' => 'permission-type',
                'expanded' => true,
                'multiple' => true])
        ->add('submit', SubmitType::class, ['label' => 'save']);
  }
}