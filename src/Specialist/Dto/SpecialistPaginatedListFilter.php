<?php

namespace App\Specialist\Dto;

class SpecialistPaginatedListFilter {

  private ?string $search = null;

  public function getSearch(): ?string {
    return $this->search;
  }

  public function setSearch(?string $search): self {
    $this->search = $search;

    return $this;
  }
}