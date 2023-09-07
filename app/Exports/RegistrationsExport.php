<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RegistrationsExport implements ShouldAutoSize, FromView
{
    use Exportable;

    public Collection $registrations;

    public function __construct(Collection $registrations)
    {
        $this->registrations = $registrations;
    }

    public function view(): View
    {
        return view('exports.stub.registrations', [
            'registrations' => $this->registrations
        ]);
    }
}
