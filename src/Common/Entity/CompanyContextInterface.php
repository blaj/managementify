<?php

namespace App\Common\Entity;

use App\Company\Entity\Company;

interface CompanyContextInterface {

  function getCompany(): Company;

  function setCompany(Company $company): self;
}