<?php

namespace App\Report\Service\Generator;

use App\Common\Exception\NotYetImplementedException;
use App\FileStorage\Service\FileStorageService;
use App\Report\Dto\ReportFileType;
use App\Report\Dto\ReportGenerateWrapper;
use App\Report\Dto\ReportType;
use Dompdf\Dompdf;
use JsonSerializable;
use Symfony\Component\HttpFoundation\File\File;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @template DS of JsonSerializable
 * @implements ReportGeneratorServiceInterface<DS>
 */
class PdfReportGeneratorService implements ReportGeneratorServiceInterface {

  /**
   * @var array<string, string>
   */
  private static array $templatePathMap = [
      ReportType::VISIT_SUMMARY->value => 'report/generator/visit-summary.html.twig'
  ];

  public function __construct(
      private readonly FileStorageService $fileStorageService,
      private readonly Environment $environment) {}

  /**
   * @param DS $dataSource
   */
  public function generate(ReportGenerateWrapper $reportGenerateWrapper, $dataSource): File {
    $templatePath =
        self::$templatePathMap[$reportGenerateWrapper->reportType->value]
        ??
        throw new NotYetImplementedException();

    $dompdf = new Dompdf();
    try {
      $dompdf->loadHtml($this->environment->render($templatePath, $dataSource->jsonSerialize()));
    } catch (LoaderError|RuntimeError|SyntaxError $e) {

    }
    $dompdf->render();
    $output = $dompdf->output();

    $reportPath = $this->fileStorageService->getReportPath($reportGenerateWrapper);
    $filePath =
        $this->fileStorageService->saveContentToFile(
            $output,
            str_replace('_', '-', strtolower($reportGenerateWrapper->reportType->value))
            . '-'
            . time()
            . '.pdf',
            $reportPath);

    return new File($filePath);
  }

  public function getReportFileType(): ReportFileType {
    return ReportFileType::PDF;
  }
}