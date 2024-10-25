<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Setting;
use Spatie\Browsershot\Browsershot;

use function Spatie\LaravelPdf\Support\pdf;

class PrintController extends Controller
{
    protected $view = 'prints.quotation';

    // To download pdf, you must do artisan serve (127.0.0.1:8000) and trun on http://filament-quotation.test. Then print report via 127.0.0.1:8000

    public function quotation(Quotation $quotation)
    {
        $filename = str_replace(' ', '_', "Quotation_{$quotation->client->name}_{$quotation->no}.pdf");
        $setting = Setting::first();
        $quotation->load('client', 'quotationItems.item');

        return pdf()
            ->view($this->view, compact('quotation', 'setting'))
            ->withBrowsershot(function (Browsershot $browsershot) {
                $browsershot
                    // ->timeout(300)
                    ->setNodeBinary('C:/laragon/bin/nodejs/node-v20.9.0/node.exe')
                    ->setOption('newHeadless', true);
            })
            ->format('a4')
            ->download($filename);
    }

    public function preview(Quotation $quotation)
    {
        $setting = Setting::first();
        $quotation->load('client', 'quotationItems.item');

        return view($this->view, compact('quotation', 'setting'));
    }
}
