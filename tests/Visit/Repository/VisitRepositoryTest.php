<?php

namespace App\Tests\Visit\Repository;

use App\Client\Entity\Client;
use App\Client\Repository\ClientRepository;
use App\Common\Utils\DateTimeImmutableUtils;
use App\Specialist\Entity\Specialist;
use App\Specialist\Repository\SpecialistRepository;
use App\Tests\RepositoryTestCase;
use App\Visit\Entity\Visit;
use App\Visit\Repository\VisitRepository;
use DateTimeImmutable;
use PHPUnit\Framework\Assert;

class VisitRepositoryTest extends RepositoryTestCase {

  private VisitRepository $visitRepository;
  private SpecialistRepository $specialistRepository;
  private ClientRepository $clientRepository;

  private Specialist $specialist;
  private Client $client;

  public function setUp(): void {
    parent::setUp();

    $this->visitRepository = self::getService(VisitRepository::class);
    $this->specialistRepository = self::getService(SpecialistRepository::class);
    $this->clientRepository = self::getService(ClientRepository::class);

    $this->specialist = (new Specialist())->setFirstname('firstname')->setSurname('surname');
    $this->specialistRepository->save($this->specialist);

    $this->client = (new Client())->setFirstname('firstname')->setSurname('surname');
    $this->clientRepository->save($this->client);
  }

  /**
   * @test
   */
  public function givenNonExistingSpecialistId_whenFindAllBySpecialistIdAndOnDate_shouldReturnEmptyArray(): void {
    // given
    $fromTime = new DateTimeImmutable('2023-01-01 10:00:00');
    $toTime = new DateTimeImmutable('2023-01-01 12:00:00');
    $visit = $this->visit($fromTime, $toTime);

    $this->visitRepository->save($visit);

    $nonExistingSpecialistId = $this->specialist->getId() + 1;

    // when
    $entities =
        $this->visitRepository->findAllBySpecialistIdAndOnDate($nonExistingSpecialistId, $fromTime);

    // then
    Assert::assertEmpty($entities);
  }

  /**
   * @test
   */
  public function givenNonExistingDate_whenFindAllBySpecialistIdAndOnDate_shouldReturnEmptyArray(): void {
    // given
    $fromTime = new DateTimeImmutable('2023-01-01 10:00:00');
    $toTime = new DateTimeImmutable('2023-01-01 12:00:00');
    $visit = $this->visit($fromTime, $toTime);

    $this->visitRepository->save($visit);

    $nonExistingDate = DateTimeImmutableUtils::addDay($fromTime);

    // when
    $entities =
        $this->visitRepository->findAllBySpecialistIdAndOnDate(
            $this->specialist->getId(),
            $nonExistingDate);

    // then
    Assert::assertEmpty($entities);
  }

  /**
   * @test
   */
  public function givenValid_whenFindAllBySpecialistIdAndOnDate_shouldReturnEntitiesArray(): void {
    // given
    $fromTime = new DateTimeImmutable('2023-01-01 10:00:00');
    $toTime = new DateTimeImmutable('2023-01-01 12:00:00');

    $visit1 = $this->visit($fromTime, $toTime);
    $visit2 = $this->visit($fromTime, $toTime);

    $this->visitRepository->save($visit1);
    $this->visitRepository->save($visit2);

    // when
    $entities =
        $this->visitRepository->findAllBySpecialistIdAndOnDate(
            $this->specialist->getId(),
            $fromTime);

    // then
    Assert::assertNotEmpty($entities);
    Assert::assertCount(2, $entities);
    Assert::assertEquals([$visit1, $visit2], $entities);
  }

  private function visit(DateTimeImmutable $fromTime, DateTimeImmutable $toTime): Visit {
    return (new Visit())
        ->setFromTime($fromTime)
        ->setToTime($toTime)
        ->setNote('note')
        ->setSpecialist($this->specialist)
        ->setClient($this->client);
  }
}