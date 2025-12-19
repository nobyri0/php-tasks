<?php

namespace App\Service;

use App\Entity\RestaurantOrder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

class OrderExcelExporter
{
    public function export(array $orders): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setTitle('Заказы');
        
        $headers = ['№ заказа', 'Дата', 'Клиент', 'Телефон', 'Блюда', 'Количество блюд', 'Сумма (₽)', 'Статус'];
        $columnLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        
        foreach ($headers as $index => $header) {
            $cell = $columnLetters[$index] . '1';
            $sheet->setCellValue($cell, $header);
        }
        
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '667eea'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];
        
        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);
        
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(40);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        
        $row = 2;
        foreach ($orders as $order) {
            /** @var RestaurantOrder $order */
            
            $dishesNames = [];
            foreach ($order->getDishes() as $dish) {
                $dishesNames[] = $dish->getName() . ' (' . $dish->getPrice() . ' ₽)';
            }
            $dishesString = implode(', ', $dishesNames);
            
            $statusMap = [
                'pending' => 'Ожидает',
                'preparing' => 'Готовится',
                'completed' => 'Завершён',
                'cancelled' => 'Отменён',
            ];
            $statusRu = $statusMap[$order->getStatus()] ?? $order->getStatus();
            
            $sheet->setCellValue('A' . $row, '#' . $order->getId());
            $sheet->setCellValue('B' . $row, $order->getOrderDate()->format('d.m.Y H:i'));
            $sheet->setCellValue('C' . $row, $order->getClient()->getName());
            $sheet->setCellValue('D' . $row, $order->getClient()->getPhone() ?? 'не указан');
            $sheet->setCellValue('E' . $row, $dishesString);
            $sheet->setCellValue('F' . $row, $order->getDishes()->count());
            $sheet->setCellValue('G' . $row, $order->getTotalAmount());
            $sheet->setCellValue('H' . $row, $statusRu);
            
            $dataStyle = [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ];
            
            $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray($dataStyle);

            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $sheet->getStyle('E' . $row)->getAlignment()->setWrapText(true);

            $statusColors = [
                'Ожидает' => 'FFF3CD',
                'Готовится' => 'CFE2FF',
                'Завершён' => 'D1E7DD',
                'Отменён' => 'F8D7DA',
            ];
            
            if (isset($statusColors[$statusRu])) {
                $sheet->getStyle('H' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB($statusColors[$statusRu]);
            }
            
            $row++;
        }
        $totalRow = $row + 1;
        $sheet->setCellValue('A' . $totalRow, 'ИТОГО:');
        $sheet->mergeCells('A' . $totalRow . ':F' . $totalRow);
        $sheet->setCellValue('G' . $totalRow, '=SUM(G2:G' . ($row - 1) . ')');
        $totalStyle = [
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E8E8E8'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];
        
        $sheet->getStyle('A' . $totalRow . ':H' . $totalRow)->applyFromArray($totalStyle);
        $infoRow = $totalRow + 2;
        $sheet->setCellValue('A' . $infoRow, 'Отчёт создан: ' . (new \DateTime())->format('d.m.Y H:i:s'));
        $sheet->getStyle('A' . $infoRow)->getFont()->setItalic(true)->setSize(10);
        $sheet->setCellValue('A' . ($infoRow + 1), 'Всего заказов: ' . count($orders));
        $sheet->getStyle('A' . ($infoRow + 1))->getFont()->setBold(true);
        $tempFile = tempnam(sys_get_temp_dir(), 'orders_') . '.xlsx';
        
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);
        
        return $tempFile;
    }
}