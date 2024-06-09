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


class GatepassReportExport implements FromCollection, WithStyles, ShouldAutoSize
{
    use Exportable;

    public $row = array();
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $id = request('id');
        $gatepass = Gatepass::with([
                        'items',
                        'createdByUser',
                        'secondApprovedBy'
                    ])
                    ->find($id);

        $title_1st = ['CGL'];
        $title_2nd = ['Concorde Garments LTD'];
        $title_3rd = ['Gatepass'];
        $gap_1st = ['','','','','',''];
        $topHeader_1st = ['Gatepass No: '. $gatepass->gate_pass_no, '', '', '','Date: '. $gatepass->creation_datetime];
        $title_4th = ['General Item Details'];
        $header = ['Sl','Item','Unit','Qty','Item Description'];

        $i=1;
        foreach($gatepass->items as $item) {
            $data_row[] = [
                'Sl' => $i,
                'Item' => $item->itemInfo?->name,
                'Unit' => $item->unitInfo?->name,
                'Qty' => $item->qty,
                'Item Description' => $item->item_description
            ];

            $i++;
        }

        $bottom_data_head = ['Created By:', '', 'Department Head:', '', 'Approver:', ''];
        $bottom_data = [$gatepass->createdByUser?->name, '', '', '', $gatepass->secondApprovedBy?->name, ''];

        return collect([
            $title_1st,
            $title_2nd,
            $title_3rd,
            $gap_1st,
            $gap_1st,
            $topHeader_1st,
            $gap_1st,
            $title_4th,
            $header,
            $data_row,
            $gap_1st,
            $gap_1st,
            $bottom_data_head,
            $bottom_data
        ]);
    }
    
    public function styles(Worksheet $sheet)
    {
        // Merge cells and define styles
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->mergeCells('A3:F3');
        $sheet->mergeCells('A4:F4');
        $sheet->mergeCells('A5:F5');
        $sheet->mergeCells('B6:C6');
        $sheet->mergeCells('A7:F7');
        $sheet->mergeCells('A8:F8');

        // Define the styles for each color
        $f7b154Style = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'f7b154',
                ],
            ],
        ];

    
        // Apply styles to the specified columns in row 2
        $sheet->getStyle('A9:E9')->applyFromArray($f7b154Style);
        
        // Define the common style for center alignment
        $centerAlignmentStyle = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ];

        // Apply the common style to all columns
        $sheet->getStyle('A:E')->applyFromArray($centerAlignmentStyle);
        

        // Define styles for specific cells
        $styles = [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 14,
                ],
                
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                ],
            ],
            2 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID
                ]
            ],
            3 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                ]
            ],
            '5:5' => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                ]
            ],
            6 => [
                'font' => [
                    'bold' => true,
                    'size' => 10,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                ]
            ],
            '7:8' => [
                'font' => [
                    'bold' => false,
                    'size' => 12,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                ]
            ],
        ];

        // Apply the defined styles
        foreach ($styles as $range => $style) {
            $sheet->getStyle($range)->applyFromArray($style);
        }

        return $styles;
    }
}
