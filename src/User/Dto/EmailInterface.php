<?php

namespace App\User\Dto;

interface EmailInterface {

  function getEmail(): string;

  function setEmail(string $email): self;
}