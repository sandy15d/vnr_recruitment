<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MRFDetail implements FromView,ShouldAutoSize
{

    public function __construct($MRFId)
    {
        $this->MRFId = $MRFId;
    }

    public function title(): string
    {
        return 'MRF Detail';
    }

    public function view(): View
    {
        return view('exports.MRFDetail', ['MRFId' => $this->MRFId]);
    }
}
