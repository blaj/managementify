<?php

namespace App\User\Dto;

use App\User\Validator\EmailIsFree;
use App\User\Validator\UsernameIsFree;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\Regex;

class UserRegisterRequest {

  #[NotBlank]
  #[Length(min: 3, max: 50)]
  #[UsernameIsFree]
  private string $username;

  #[NotBlank]
  #[Length(min: 3, max: 100)]
  #[Email]
  #[EmailIsFree]
  private string $email;

  #[NotBlank]
  #[Length(min: 8, max: 50)]
  #[NotCompromisedPassword]
  private string $password;

  #[NotBlank]
  #[Length(min: 3, max: 100)]
  private string $companyName;

  #[NotBlank]
  #[Length(min: 3, max: 100)]
  private string $companyCity;

  #[NotBlank]
  #[Length(min: 3, max: 100)]
  private string $companyStreet;

  #[NotBlank]
  #[Length(exactly: 6)]
  #[Regex(pattern: '^[0-9]{2}-[0-9]{3}$^')]
  private string $companyPostcode;

  public function getUsername(): string {
    return $this->username;
  }

  public function setUsername(string $username): self {
    $this->username = $username;

    return $this;
  }

  public function getEmail(): string {
    return $this->email;
  }

  public function setEmail(string $email): self {
    $this->email = $email;

    return $this;
  }

  public function getPassword(): string {
    return $this->password;
  }

  public function setPassword(string $password): self {
    $this->password = $password;

    return $this;
  }

  public function getCompanyName(): string {
    return $this->companyName;
  }

  public function setCompanyName(string $companyName): self {
    $this->companyName = $companyName;

    return $this;
  }

  public function getCompanyCity(): string {
    return $this->companyCity;
  }

  public function setCompanyCity(string $companyCity): self {
    $this->companyCity = $companyCity;

    return $this;
  }

  public function getCompanyStreet(): string {
    return $this->companyStreet;
  }

  public function setCompanyStreet(string $companyStreet): self {
    $this->companyStreet = $companyStreet;

    return $this;
  }

  public function getCompanyPostcode(): string {
    return $this->companyPostcode;
  }

  public function setCompanyPostcode(string $companyPostcode): self {
    $this->companyPostcode = $companyPostcode;

    return $this;
  }
}