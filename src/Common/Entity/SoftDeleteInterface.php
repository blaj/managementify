<?php

namespace App\Common\Entity;

interface SoftDeleteInterface {

  function isDeleted(): bool;

  function setDeleted(bool $deleted): self;
}