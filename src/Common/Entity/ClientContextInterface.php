<?php

namespace App\Common\Entity;

use App\Client\Entity\Client;

interface ClientContextInterface {

  function getClient(): Client;

  function setClient(Client $client): self;
}