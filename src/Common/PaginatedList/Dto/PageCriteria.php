<?php

namespace App\Common\PaginatedList\Dto;

class PageCriteria {

  public static int $defaultNo = 0;
  public static int $defaultSize = 15;

  public static function default(): PageCriteria {
    return (new PageCriteria())->setNo(self::$defaultNo)->setSize(self::$defaultSize);
  }

  private ?int $no = null;

  private ?int $size = null;

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
}