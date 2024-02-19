<?php

namespace App\User\Service;

use App\Common\Entity\Address;
use App\Company\Entity\Company;
use App\Company\Repository\CompanyRepository;
use App\User\Dto\UserRegisterRequest;
use App\User\Entity\User;
use App\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService {

  public function __construct(
      private readonly UserRepository $userRepository,
      private readonly CompanyRepository $companyRepository,
      private readonly EntityManagerInterface $entityManager,
      private readonly UserPasswordHasherInterface $userPasswordHasher) {}

  public function register(UserRegisterRequest $userRegisterRequest): void {
    $company = (new Company())
        ->setName($userRegisterRequest->getCompanyName())
        ->setAddress(
            (new Address())
                ->setCity($userRegisterRequest->getCompanyCity())
                ->setStreet($userRegisterRequest->getCompanyStreet())
                ->setPostcode($userRegisterRequest->getCompanyPostcode()));

    $user = (new User())
        ->setUsername($userRegisterRequest->getUsername())
        ->setEmail($userRegisterRequest->getEmail())
        ->setCompany($company);

    $user->setPassword(
        $this->userPasswordHasher->hashPassword($user, $userRegisterRequest->getPassword()));

    $this->entityManager->beginTransaction();
    $this->companyRepository->save($company, false);
    $this->userRepository->save($user, false);
    $this->entityManager->flush();
    $this->entityManager->commit();
  }
}