<?php

namespace App\Exports;

use App\Models\Client;

class ClientExport
{
    public static function build(): array
    {
        $headers = ['#', 'Name', 'Company', 'Email', 'Phone', 'CNIC', 'Type', 'City', 'Created At'];

        $rows = Client::orderBy('name')->get()->map(function ($c) {
            return [
                $c->id,
                $c->name,
                $c->company,
                $c->email,
                $c->phone,
                $c->cnic,
                str_replace('_', ' ', $c->client_type ?? '-'),
                $c->city ?? '',
                $c->created_at?->format('Y-m-d'),
            ];
        })->toArray();

        return [$headers, $rows];
    }
}
