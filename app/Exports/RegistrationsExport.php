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
    public string $viewName;

    public function __construct(Collection $registrations, string $viewName)
    {
        $this->registrations = $registrations;
        $this->viewName = $viewName;
    }

    public function view(): View
    {
        return view($this->viewName, [
            'registrations' => $this->registrations
        ]);
    }
}
