<?php
namespace App\Traits;
use Maatwebsite\Excel\Events\AfterSheet;

Trait ExportPageNumber{

    public function setPageNumbers(AfterSheet $AfterSheet, $format = "&R&F Page &P / &N") : void
    {
        $AfterSheet->sheet->getDelegate()
                    ->getHeaderFooter()->setOddFooter($format);
        $AfterSheet->sheet->getDelegate()
            ->getHeaderFooter()->setEvenFooter($format);
    }
}
