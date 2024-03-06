<?php

namespace App\Report\Service\Generator;

use App\Report\Dto\ReportGenerateWrapper;
use App\Report\Dto\ReportType;
use JsonSerializable;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @template DS of JsonSerializable
 */
interface XlsxReportGeneratorServiceInterface {

  /**
   * @param DS $dataSource
   */
  function generate(ReportGenerateWrapper $reportGenerateWrapper, $dataSource): File;

  function getReportType(): ReportType;
}