<?php

namespace App\Common\Dto;

interface IdInterface {

  function setId(int $id): self;

  function getId(): int;
}