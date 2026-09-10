<?php

namespace App\Exports;

use App\Models\Submission;
use App\Models\Assignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GradesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $assignmentId;
    protected $rowNumber = 0;

    public function __construct($assignmentId)
    {
        $this->assignmentId = $assignmentId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Submission::with('student')
            ->where('assignment_id', $this->assignmentId)
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Username',
            'Status',
            'Nilai',
            'Tanggal Dikumpulkan',
            'Detail Poin Auto-Grading'
        ];
    }

    public function map($row): array
    {
        $this->rowNumber++;
        
        $statusStr = 'Belum Dikerjakan';
        if ($row->status === 'draft') $statusStr = 'Sedang Dikerjakan (Draft)';
        elseif ($row->status === 'submitted') $statusStr = 'Sudah Dikumpulkan';

        $gradingNotes = '';
        if ($row->grading_detail) {
            $details = json_decode($row->grading_detail, true);
            if (is_array($details)) {
                $notes = [];
                foreach ($details as $idx => $d) {
                    $notes[] = ($idx + 1) . ". {$d['target']}: {$d['points_awarded']}/{$d['points_max']} ({$d['note']})";
                }
                $gradingNotes = implode("\n", $notes);
            }
        }

        return [
            $this->rowNumber,
            $row->student ? $row->student->name : 'Siswa Terhapus',
            $row->student ? $row->student->username : '-',
            $statusStr,
            $row->score ?? 0,
            $row->submitted_at ? \Carbon\Carbon::parse($row->submitted_at)->format('d/m/Y H:i:s') : '-',
            $gradingNotes
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('G')->getAlignment()->setWrapText(true);
    }
}
