<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatusEnum;
use App\Exports\RegistrationsExport;
use App\Http\Requests\Export\RegistrationsStoreRequest;
use App\Models\Competition;
use App\Models\Registration;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class ExportController extends Controller
{
    public function index(Request $request)
    {
        $competitions = Competition::with('registrations.order')->get();
        $competitions = $competitions->filter(function ($competition) {
            if ($competition->registrations->isEmpty()) {
                return false;
            }

            if ($competition->registrations->every(function ($registration) {
                $isExpired = $registration->order->status == OrderStatusEnum::Expired;
                $isPending = $registration->order->status == OrderStatusEnum::Pending;

                return $isExpired || $isPending;
            })) {
                // return false if all the registrations order status is pending
                // or expired. because we can assume that the registrations
                // is not a valid registrations since its not being paid
                // yet.
                return false;
            }

            return true;
        });

        return view('exports.index', [
            'data' => [
                'competitions' => $competitions
            ],
            ...$this->withLinks([]),
            ...$this->withMetadata([]),
            ...$this->withUser($request)
        ]);
    }

    public function storeRegistrations(RegistrationsStoreRequest $request)
    {
        $data = $request->validated();

        if ($data['competition'] != 'all') {
            $registrations = Registration::with('team.members')
                ->where(['competition_id' => $data['competition']])
                ->whereRelation('order', 'status', '!=', OrderStatusEnum::Expired->value)
                ->whereRelation('order', 'status', '!=', OrderStatusEnum::Pending->value)
                ->get();

            return (new RegistrationsExport($registrations, 'exports.stub.registrations'))->download(
                sprintf('registrations-%s.xlsx', now()->toDateString()),
                Excel::XLSX
            );
        }

        $registrations = Registration::with('team.members')
            ->whereIsRegistered()
            ->whereRelation('order', 'status', '!=', OrderStatusEnum::Expired->value)
            ->whereRelation('order', 'status', '!=', OrderStatusEnum::Pending->value)
            ->get();

        return (new RegistrationsExport($registrations, 'exports.stub.registrations-all'))->download(
            sprintf('all-registrations-%s.xlsx', now()->getTimestamp()),
            Excel::XLSX
        );
    }
}
