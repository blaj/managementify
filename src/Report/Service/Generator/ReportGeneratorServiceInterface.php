<?php

namespace App\Report\Service\Generator;

use App\Report\Dto\ReportFileType;
use App\Report\Dto\ReportGenerateWrapper;
use JsonSerializable;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @template DS of JsonSerializable
 */
interface ReportGeneratorServiceInterface {

  /**
   * @param DS $dataSource
   */
  function generate(ReportGenerateWrapper $reportGenerateWrapper, $dataSource): File;

  function getReportFileType(): ReportFileType;
}