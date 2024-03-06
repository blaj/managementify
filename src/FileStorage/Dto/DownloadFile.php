<?php

namespace App\FileStorage\Dto;

use Symfony\Component\HttpFoundation\File\File;

readonly class DownloadFile {

  public function __construct(public ?File $file, public string $originalFileName) {}
}