<?php

namespace Database\Seeders;

use App\Models\GuidanceField;
use App\Models\ImsStatement;
use App\Models\ServiceType;
use Illuminate\Database\Seeder;
use ZipArchive;

class ImsStatementSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Mapping Layanan ke ServiceType
        $layananMap = [
            'ORIENTASI' => ServiceType::where('code', 'LAY-01')->first()?->id,
            'INFORMASI' => ServiceType::where('code', 'LAY-02')->first()?->id,
            'PENEMPATAN DAN PENYALURAN' => ServiceType::where('code', 'LAY-03')->first()?->id,
            'PENEMPATAN & PENYALURAN' => ServiceType::where('code', 'LAY-03')->first()?->id,
            'PENGUASAAN KONTEN' => ServiceType::where('code', 'LAY-04')->first()?->id,
            'KONSELING PERORANGAN' => ServiceType::where('code', 'LAY-05')->first()?->id,
            'BIMBINGAN KELOMPOK' => ServiceType::where('code', 'LAY-06')->first()?->id,
            'KONSELING KELOMPOK' => ServiceType::where('code', 'LAY-07')->first()?->id,
            'KONSULTASI' => ServiceType::where('code', 'LAY-08')->first()?->id,
            'MEDIASI' => ServiceType::where('code', 'LAY-09')->first()?->id,
        ];

        // 2. Mapping Bidang ke GuidanceField
        $bidangMap = [
            'PRIBADI' => GuidanceField::where('code', 'BID-01')->first()?->id,
            'SOSIAL' => GuidanceField::where('code', 'BID-02')->first()?->id,
            'BELAJAR' => GuidanceField::where('code', 'BID-03')->first()?->id,
            'KARIR' => GuidanceField::where('code', 'BID-04')->first()?->id,
            'KARIER' => GuidanceField::where('code', 'BID-04')->first()?->id,
        ];

        // 3. Sumber Data: Buka BK.xlsx atau fallback ke cache JSON
        $excelPath = 'C:/Users/MyBook 11 Plus/Desktop/BK.xlsx';
        $items = [];

        if (file_exists($excelPath)) {
            $zip = new ZipArchive();
            if ($zip->open($excelPath) === true) {
                $sharedStrings = [];
                if (($index = $zip->locateName('xl/sharedStrings.xml')) !== false) {
                    $xml = simplexml_load_string($zip->getFromIndex($index));
                    foreach ($xml->si as $si) {
                        $text = '';
                        if (isset($si->t)) {
                            $text = (string)$si->t;
                        } elseif (isset($si->r)) {
                            foreach ($si->r as $r) {
                                $text .= (string)$r->t;
                            }
                        }
                        $sharedStrings[] = $text;
                    }
                }

                if (($index = $zip->locateName('xl/worksheets/sheet1.xml')) !== false) {
                    $xml = simplexml_load_string($zip->getFromIndex($index));
                    $currentLayanan = '';
                    $currentBidang = '';

                    foreach ($xml->sheetData->row as $row) {
                        if ((int)$row['r'] < 6) continue;

                        $colA = ''; $colB = ''; $colC = ''; $colD = '';
                        foreach ($row->c as $c) {
                            $ref = (string)$c['r'];
                            $colLetter = preg_replace('/[0-9]/', '', $ref);
                            $val = (string)$c->v;
                            if ((string)$c['t'] === 's' && isset($sharedStrings[(int)$val])) {
                                $val = $sharedStrings[(int)$val];
                            }
                            $val = trim($val);

                            if ($colLetter === 'A') $colA = $val;
                            if ($colLetter === 'B') $colB = $val;
                            if ($colLetter === 'C') $colC = $val;
                            if ($colLetter === 'D') $colD = $val;
                        }

                        if (!empty($colA)) $currentLayanan = strtoupper($colA);
                        if (!empty($colB)) $currentBidang = strtoupper($colB);

                        if (is_numeric($colC) && !empty($colD)) {
                            $items[] = [
                                'number' => (int)$colC,
                                'layanan' => $currentLayanan,
                                'bidang' => $currentBidang,
                                'statement' => $colD,
                            ];
                        }
                    }
                }
                $zip->close();
            }
        }

        // Fallback JSON jika pembacaan file excel gagal
        $jsonPath = 'C:/Users/MyBook 11 Plus/.gemini/antigravity-ide/brain/00db48c0-0c5d-4bad-ae1a-0ef56291dbe9/scratch/all_180_items.json';
        if (empty($items) && file_exists($jsonPath)) {
            $items = json_decode(file_get_contents($jsonPath), true);
        }

        foreach ($items as $item) {
            $serviceTypeId = $layananMap[$item['layanan']] ?? null;
            $guidanceFieldId = $bidangMap[$item['bidang']] ?? null;

            if ($serviceTypeId && $guidanceFieldId) {
                ImsStatement::updateOrCreate(
                    ['statement_number' => $item['number']],
                    [
                        'service_type_id' => $serviceTypeId,
                        'guidance_field_id' => $guidanceFieldId,
                        'statement_text' => $item['statement'],
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
