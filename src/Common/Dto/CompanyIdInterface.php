<?php

namespace App\Common\Dto;

interface CompanyIdInterface {

  function setCompanyId(int $companyId): self;

  function getCompanyId(): int;
}