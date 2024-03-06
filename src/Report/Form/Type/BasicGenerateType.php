<?php

namespace App\Report\Form\Type;

use App\Report\Dto\ReportFileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class BasicGenerateType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('GET')
        ->add(
            'reportFileType',
            EnumType::class,
            ['label' => 'file-type', 'class' => ReportFileType::class])
        ->add('submit', SubmitType::class, ['label' => 'save']);
  }
}