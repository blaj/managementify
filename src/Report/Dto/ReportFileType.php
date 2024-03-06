<?php

namespace App\Report\Dto;

enum ReportFileType: string {

  case CSV = 'CSV';
  case PDF = 'PDF';
  case XLSX = 'XLSX';
}
