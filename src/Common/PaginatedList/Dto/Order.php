<?php

namespace App\Common\PaginatedList\Dto;

enum Order: string {
  case ASC = 'ASC';
  case DESC = 'DESC';
}
