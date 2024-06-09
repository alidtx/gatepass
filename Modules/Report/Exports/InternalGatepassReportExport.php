<?php

namespace Modules\Report\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponseTrait;
use DB;
use Modules\Gatepass\Entities\Gatepass;
use Modules\Gatepass\Entities\GatepassItem;
use Modules\Gatepass\Entities\GatepassDocument;
use Modules\GatepassReceive\Entities\InternalReceive;
use Illuminate\Support\Collection;

class InternalGatepassReportExport implements FromCollection, WithStyles, ShouldAutoSize
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection() {
        $gatepasses = InternalReceive::with([
            'receivedItems.itemsInfo.itemInfo.unitInfo',
            'receivedItems.itemsInfo.unitInfo',
            'gatepassCheckInfo.gatepassInfo',
            'gatepassCheckInfo.fromLocationInfo',
            'toLocationInfo'
        ])
        ->when(request()->filled('from_date') && request()->filled('to_date'), function ($query) {
            return $query->customDateRange(request()->from_date, request()->to_date);
        }, function ($query) {
            return $query->currentMonth();
        })
        ->when(request()->filled('from_location_id'), function ($query) {
            return $query->whereHas('gatepassCheckInfo', function ($query) {
                return $query->where('from_location_id', request()->filled('from_location_id'));
            });
        })
        ->when(request()->filled('to_location_id'), function ($query) {
            return $query->where('to_location_id', request()->filled('to_location_id'));
        })
        ->orderBy('id', 'desc')
        ->get();

        $reportData = new Collection();

        // Add your top header text here
        $titleRow1 = ['CGL Internal Gate Pass Report'];
        $reportData->push($titleRow1);
    
        // Add your From - To date range text here
        // Assuming you have request data with 'from_date' and 'to_date'
        $fromDate = request()->filled('from_date') ? date('j M Y', strtotime(request()->from_date)) : date('j M Y', strtotime(now()->startOfMonth()));;
        $toDate = request()->filled('to_date') ? date('j M Y', strtotime(request()->to_date)) : date('j M Y', strtotime(now()->endOfMonth()));
        $titleRow2 = ["From $fromDate to $toDate"];
        $reportData->push($titleRow2);
        
        $titleRow3 = [''];
        $reportData->push($titleRow3);
    
        // Add a blank row for spacing if needed
        $reportData->push([]);

        // Define the headers
        $headers = [
            'Transfer Date',
            'Gatepass No',
            'From Location',
            'To Location',
            'Sl',
            'Item Name',
            'Unit',
            'Release Qty',
            'Received Qty',
            'Difference'
        ];

        // Add headers to report
        $reportData->push($headers);

        foreach ($gatepasses as $gatepass) {
            $firstItem = true;
            foreach ($gatepass->receivedItems as $index => $item) {
                $difference = $item?->itemsInfo?->qty - $item?->received_qty;
                $row = [
                    $firstItem ? date('j M Y', strtotime($gatepass->received_date_time)) : '',
                    $firstItem ? $gatepass->gatepassCheckInfo?->gatepassInfo?->gate_pass_no : '',
                    $firstItem ? $gatepass->gatepassCheckInfo?->fromLocationInfo?->name : '',
                    $firstItem ? $gatepass->toLocationInfo?->name : '',
                    $index + 1,
                    $item->itemsInfo?->itemInfo?->name,
                    $item->itemsInfo?->unitInfo?->name,
                    $item->itemsInfo?->qty,
                    $item->received_qty==0 ? '0' : $item->received_qty,
                    $difference==0 ? '0' : $difference
                ];
                $reportData->push($row);
                $firstItem = false; // Set to false after the first item
            }
        }

        return $reportData;
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells for title rows
        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:J2');
        $sheet->mergeCells('A3:J3');

        // Style the title rows
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getFont()->setBold(false)->setSize(12);

        // Center the title text
        $sheet->getStyle('A1:J2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        // Set the height for the title rows
        $sheet->getRowDimension(1)->setRowHeight(20);
        $sheet->getRowDimension(2)->setRowHeight(20);

        // Apply styles to the header row
        $sheet->getStyle('A4:J4')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'f7b154'],
            ],
            'font' => [
                'bold' => true,
            ],
        ]);

        $sheet->getStyle('A4:E4')->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Define the range for center-aligned columns (headers and data)
        // Assuming your data starts from row 5
        $centerColumnRange = 'A5:E' . $sheet->getHighestRow();

        // Center-align the specified columns data
        $sheet->getStyle($centerColumnRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Set borders for the entire data range
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        // Apply borders to all cells in the table
        $sheet->getStyle('A5:J' . $sheet->getHighestRow())->applyFromArray($styleArray);

        // Set auto-sizing for all columns
        foreach (range('A', 'J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Optionally set wrapping text for specific columns if needed
        $wrapTextColumnRange = 'F5:F' . $sheet->getHighestRow(); // Example for 'Item Name' column
        $sheet->getStyle($wrapTextColumnRange)->getAlignment()->setWrapText(true);
    }
}