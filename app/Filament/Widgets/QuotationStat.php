<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class QuotationStat extends BaseWidget
{
    protected function getCachedStats(): array
    {
        return [
            Stat::make(
                'Total Earnings',
                Number::currency(
                    QuotationItem::query()
                        ->join('items', 'quotation_items.item_id', '=', 'items.id')
                        ->selectRaw('SUM(quotation_items.quantity * items.price) as total')
                        ->value('total'),
                    'IDR',
                    'id'
                )
            ),
            Stat::make('Total Quotation', Quotation::count()),
            Stat::make('Total Client', Client::count()),
        ];
    }
}
