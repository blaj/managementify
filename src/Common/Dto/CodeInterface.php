<?php

namespace App\Common\Dto;

interface CodeInterface {

  function setCode(string $code): self;

  function getCode(): string;
}