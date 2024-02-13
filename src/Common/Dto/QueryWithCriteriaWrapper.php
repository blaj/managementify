<?php

namespace App\Common\Dto;

class QueryWithCriteriaWrapper {

  /**
   * @var array<string|null>
   */
  private array $statements = [];

  /**
   * @var array<string, mixed>
   */
  private array $parameters = [];

  public static function empty(): QueryWithCriteriaWrapper {
    return (new QueryWithCriteriaWrapper())->setStatements([])->setParameters([]);
  }

  /**
   * @return array<string|null>
   */
  public function getStatements(): array {
    return $this->statements;
  }

  /**
   * @param array<string|null> $statements
   *
   * @return $this
   */
  public function setStatements(array $statements): self {
    $this->statements = $statements;

    return $this;
  }

  public function addStatement(?string $statement): self {
    $this->statements[] = $statement;

    return $this;
  }

  /**
   * @return array<string, mixed>
   */
  public function getParameters(): array {
    return $this->parameters;
  }

  /**
   * @param array<string, mixed> $parameters
   *
   * @return $this
   */
  public function setParameters(array $parameters): self {
    $this->parameters = $parameters;

    return $this;
  }

  public function addParameter(mixed $parameter, string $key): self {
    $this->parameters[$key] = $parameter;

    return $this;
  }
}