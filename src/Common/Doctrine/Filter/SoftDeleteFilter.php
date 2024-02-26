<?php

namespace App\Common\Doctrine\Filter;

use App\Common\Entity\SoftDeleteInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class SoftDeleteFilter extends SQLFilter {

  public function addFilterConstraint(
      ClassMetadata $targetEntity,
      string $targetTableAlias): string {
    if ($targetEntity->reflClass !== null && !$targetEntity->reflClass->implementsInterface(SoftDeleteInterface::class)) {
      return '';
    }

    return $targetTableAlias . '.deleted = false';
  }
}