<?php

namespace App\Report\Form\Type;

use Symfony\Component\Form\AbstractType;

class VisitSummaryGenerateType extends AbstractType {

  public function getParent(): string {
    return BasicGenerateType::class;
  }
}