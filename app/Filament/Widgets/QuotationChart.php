<?php

namespace App\Filament\Widgets;

use Filament\Forms\Components\DatePicker;
use Filament\Support\RawJs;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class QuotationChart extends ApexChartWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $chartId = 'quotationChart';

    protected static ?string $heading = 'Quotation Chart';

    public string $dateAlias = 'date';

    protected function getOptions(): array
    {
        $data = Trend::query(
            \App\Models\QuotationItem::query()
                ->join('items', 'quotation_items.item_id', '=', 'items.id')
                ->selectRaw('SUM(quotation_items.quantity * items.price) as aggregate')
        )
            ->between(
                start: Carbon::parse($this->filterFormData['date_start']),
                end: Carbon::parse($this->filterFormData['date_end']),
            )
            ->dateColumn('quotation_items.created_at')
            ->perMonth()
            ->sum('aggregate', false);

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Eearnings',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'xaxis' => [
                'categories' => $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('M y')),
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'colors' => ['#6366f1'],
            'stroke' => [
                'curve' => 'smooth',
            ],
        ];
    }

    protected function extraJsOptions(): ?RawJs
    {
        return RawJs::make(<<<'JS'
        {
            yaxis: {
                labels: {
                    formatter: function (val, index) {
                        return new Intl.NumberFormat("id-ID", {style: "currency", currency: "IDR"}).format(val)
                    }
                }
            }
        }
        JS);
    }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('date_start')
                ->default(now()->subYear(1)->subMonth(3)),
            DatePicker::make('date_end')
                ->default(now()),
        ];
    }
}
