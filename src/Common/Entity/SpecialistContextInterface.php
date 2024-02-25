<?php

namespace App\Common\Entity;

use App\Specialist\Entity\Specialist;

interface SpecialistContextInterface {

  function getSpecialist(): Specialist;

  function setSpecialist(Specialist $specialist): self;
}