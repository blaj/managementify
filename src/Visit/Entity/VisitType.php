<?php

namespace App\Visit\Entity;

use App\Common\Entity\CompanyContextInterface;
use App\Common\Entity\Dictionary;
use App\Company\Entity\Company;
use App\Visit\Repository\VisitTypeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: VisitTypeRepository::class)]
#[Table(name: 'visit_type', schema: 'visit')]
class VisitType extends Dictionary implements CompanyContextInterface {

  #[Column(name: 'preferred_price', type: Types::BIGINT, nullable: true)]
  private ?int $preferredPrice;

  #[JoinColumn(name: 'company_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  #[ManyToOne(targetEntity: Company::class, fetch: 'LAZY')]
  private Company $company;

  public function getPreferredPrice(): ?int {
    return $this->preferredPrice;
  }

  public function setPreferredPrice(?int $preferredPrice): self {
    $this->preferredPrice = $preferredPrice;

    return $this;
  }

  public function getCompany(): Company {
    return $this->company;
  }

  public function setCompany(Company $company): self {
    $this->company = $company;

    return $this;
  }
}
