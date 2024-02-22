<?php

namespace App\Client\Dto;

use App\Common\Entity\DayOfWeek;

class PreferredHourGroupDto {

  private DayOfWeek $dayOfWeek;

  /**
   * @var array<PreferredHourRowDto>
   */
  private array $rows = [];

  public function getDayOfWeek(): DayOfWeek {
    return $this->dayOfWeek;
  }

  public function setDayOfWeek(DayOfWeek $dayOfWeek): self {
    $this->dayOfWeek = $dayOfWeek;

    return $this;
  }

  /**
   * @return array<PreferredHourRowDto>
   */
  public function getRows(): array {
    return $this->rows;
  }

  /**
   * @param array<PreferredHourRowDto> $rows
   */
  public function setRows(array $rows): self {
    $this->rows = $rows;

    return $this;
  }

  public function addRow(PreferredHourRowDto $row): self {
    $this->rows[] = $row;

    return $this;
  }
}