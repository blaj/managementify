<?php

namespace App\Company\Dto;

readonly class CompanyDetailsDto {

  public function __construct(
      public int $id,
      public string $name,
      public string $city,
      public string $street,
      public string $postcode) {}
}