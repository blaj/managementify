<?php

namespace App\User\Dto;

interface UsernameInterface {

  function getUsername(): string;

  function setUsername(string $username): self;
}