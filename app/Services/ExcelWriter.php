<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelWriter
{
    /**
     * @param  array<int|string, string>  $headers  Column headers (keys are ignored).
     * @param  array<int, array<int, mixed>>  $rows  Row values aligned to headers.
     */
    public static function stream(string $filename, array $headers, array $rows): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $row = 1;
        foreach ($headers as $i => $header) {
            $col = Coordinate::stringFromColumnIndex($i + 1);
            $sheet->setCellValue("{$col}{$row}", $header);
            $sheet->getStyle("{$col}{$row}")->getFont()->setBold(true)->setSize(12);
        }

        $row = 2;
        foreach ($rows as $rowData) {
            foreach ($headers as $i => $header) {
                $col = Coordinate::stringFromColumnIndex($i + 1);
                $value = $rowData[$i] ?? '';
                if ($value instanceof \BackedEnum) {
                    $value = $value->value;
                }
                $sheet->setCellValue("{$col}{$row}", $value);
            }
            $row++;
        }

        foreach ($headers as $i => $header) {
            $col = Coordinate::stringFromColumnIndex($i + 1);
            $sheet->getColumnDimension($col)->setWidth(22);
        }

        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
