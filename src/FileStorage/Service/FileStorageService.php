<?php

namespace App\FileStorage\Service;

use App\Common\Exception\NotYetImplementedException;
use App\FileStorage\Dto\DownloadFile;
use App\Report\Dto\ReportGenerateWrapper;
use App\Report\Dto\ReportType;
use RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileStorageService {

  private static string $parameterBagKey = 'kernel.project_dir';

  private static string $reportPath = '/public/uploads/report';

  /**
   * @var array<string, string>
   */
  private static array $reportTypePathFunctionMap = [
      ReportType::VISIT_SUMMARY->value => 'visitSummaryPath'
  ];

  public function __construct(
      private readonly ParameterBagInterface $parameterBag,
      private readonly Filesystem $filesystem) {}

  public function getDownloadFile(string $path, string $originalFileName): ?DownloadFile {
    if (!$this->filesystem->exists($path)) {
      return null;
    }

    return new DownloadFile(new File($path), $originalFileName);
  }

  public function uploadFile(?UploadedFile $uploadedFile, ?string $destinationPath): void {
    if ($uploadedFile === null || $destinationPath === null) {
      return;
    }

    $this->createFolder($destinationPath);

    $uploadedFile->move($destinationPath);
  }

  public function createFolderAndFile(string $path, string $fileName): string {
    $this->createFolder($path);
    $filePath = $path . '/' . $fileName;
    $this->createFile($filePath);

    return $filePath;
  }

  public function createFolder(string $path): void {
    if (!$this->filesystem->exists($path)) {
      $this->filesystem->mkdir($path);
    }
  }

  public function createFile(string $filePath): void {
    if (!$this->filesystem->exists($filePath)) {
      $this->filesystem->touch($filePath);
    }
  }

  public function saveContentToFile(?string $content, string $fileName, string $path): string {
    $filePath = $this->createFolderAndFile($path, $fileName);
    file_put_contents($filePath, $content, FILE_APPEND);

    return $filePath;
  }

  public function getReportPath(ReportGenerateWrapper $reportGenerateWrapper): string {
    $pathCallback =
        self::$reportTypePathFunctionMap[$reportGenerateWrapper->reportType->value]
        ??
        throw new NotYetImplementedException();

    if (!method_exists($this, $pathCallback)) {
      throw new NotYetImplementedException();
    }

    return call_user_func([$this, $pathCallback], $reportGenerateWrapper);
  }

  public function visitSummaryPath(ReportGenerateWrapper $reportGenerateWrapper): string {
    return $this->kernelProjectDir()
        . self::$reportPath
        . '/'
        . $reportGenerateWrapper->companyId
        . '/'
        . $reportGenerateWrapper->userId
        . '/visit-summary';
  }

  private function kernelProjectDir(): string {
    $kernelProjectDir = $this->parameterBag->get(self::$parameterBagKey);

    if (!is_string($kernelProjectDir)) {
      throw new RuntimeException();
    }

    return $kernelProjectDir;
  }
}