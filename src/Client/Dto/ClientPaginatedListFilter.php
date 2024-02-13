<?php

namespace App\Client\Dto;

class ClientPaginatedListFilter {

  private ?string $search = null;

  public function getSearch(): ?string {
    return $this->search;
  }

  public function setSearch(?string $search): self {
    $this->search = $search;

    return $this;
  }
}