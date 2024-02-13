<?php

namespace App\Common\PaginatedList\Dto;

class Page {

  private ?int $no = null;

  private ?int $size = null;

  private ?int $totalItems = null;

  public function getNo(): ?int {
    return $this->no;
  }

  public function setNo(?int $no): self {
    $this->no = $no;

    return $this;
  }

  public function getSize(): ?int {
    return $this->size;
  }

  public function setSize(?int $size): self {
    $this->size = $size;

    return $this;
  }

  public function getTotalItems(): ?int {
    return $this->totalItems;
  }

  public function setTotalItems(?int $totalItems): self {
    $this->totalItems = $totalItems;

    return $this;
  }
}