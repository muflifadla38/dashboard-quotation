<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview {{ $quotation->no }} Quotation</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="mx-9 my-12">
    <style>
        body {
            font-family: Montserrat, ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            color: #167f82;
        }
    </style>

    <div class="flex justify-between items-center mb-20">
        <div class="flex justify-between items-center">
            <img src="{{ env('STORAGE_URL') . ($setting->logo ? "/storage/$setting->logo" : '/assets/logo.png') }}"
                class="pe-4 max-h-14" alt="Icon">
            <div class="">
                <h3 class="text-base font-bold">{{ $setting->owner }}</h3>
                <a href="tel:{{ $setting->phone }}">
                    <span class="text-xs">{{ $setting->phone }}</span>
                </a><br>
                <a href="mailto:{{ $setting->email }}">
                    <span class="text-xs">{{ $setting->email }}</span>
                </a>
            </div>
        </div>
        <h1 class="text-4xl font-bold">Quotation</h1>
    </div>
    <div class="flex justify-between">
        <div class="">
            <h4 class="font-bold text-base pb-1">Bill To</h4>
            <div class="text-xs">
                <p>{{ $quotation->client->name }}</p>
                <p>{{ $quotation->client->address }}</p>
                <p>{{ $quotation->client->region }}</p>

                @if ($quotation->client->cc)
                    <p class="mt-5"><span class="font-bold">CC:</span> {{ $quotation->client->cc }}</p>
                @endif
            </div>
        </div>
        <div class="text-end">
            <div class="mb-10">
                <h4 class="font-bold text-base pb-2">Quote #</h4>
                <div class="text-xs">
                    <p>{{ $quotation->no }}</p>
                </div>
            </div>
            <div class="">
                <h4 class="font-bold text-base pb-2">Quote Date</h4>
                <div class="text-xs">
                    <p>{{ now()->parse($quotation->date)->format('F jS, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="-mx-4 mt-8 flow-root sm:mx-0">
        <table class="min-w-full">
            <colgroup>
                <col class="w-full sm:w-1/2">
                <col class="sm:w-1/6">
                <col class="sm:w-1/6">
                <col class="sm:w-1/6">
            </colgroup>
            <thead class="border-b border-gray-300 text-base">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold sm:pl-0">
                        Item
                    </th>
                    <th scope="col" class="hidden px-3 py-3.5 text-center font-semibold sm:table-cell">
                        Price
                    </th>
                    <th scope="col" class="hidden px-3 py-3.5 text-center font-semibold sm:table-cell">
                        Qty
                    </th>
                    <th scope="col" class="py-3.5 pl-3 pr-4 text-right font-semibold sm:pr-0">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subtotal = 0;
                    $tax = $quotation->tax / 100;
                @endphp

                @foreach ($quotation->quotationItems as $quotationItem)
                    @php
                        $subtotal += $quotationItem->quantity * $quotationItem->item->price;
                    @endphp

                    <tr @class(['text-xs', 'border-b border-gray-300 pb-2' => $loop->last])>
                        <td class="max-w-0 py-3 pl-4 pr-3 sm:pl-0">
                            <div class="font-medium">{{ $quotationItem->item->name }}</div>
                        </td>
                        <td class="hidden px-3 py-3 text-center sm:table-cell">
                            {{ Number::currency($quotationItem->item->price, 'IDR', 'id') }}
                        </td>
                        <td class="hidden px-3 py-3 text-center sm:table-cell">
                            {{ $quotationItem->quantity }}
                        </td>
                        <td class="py-3 pl-3 pr-4 text-right sm:pr-0">
                            {{ Number::currency($quotationItem->quantity * $quotationItem->item->price, 'IDR', 'id') }}
                        </td>
                    </tr>
                @endforeach

                @php
                    $taxTotal = $subtotal * $tax;
                    $total = $taxTotal ? $subtotal + $taxTotal : $subtotal;
                @endphp
            </tbody>
            <tfoot class="text-right text-base">
                <tr class="text-left">
                    <th scope="row" rowspan="{{ $taxTotal ? 4 : 2 }}" colspan="2"
                        class="hidden sm:table-cell pt-6 sm:pl-0">
                        @if ($quotation->termin || $quotation->maintenance)
                            <p class="pb-1">Terms & Conditions</p>

                            @if ($quotation->termin)
                                <p class="text-xs font-normal pb-2 w-80">
                                    Payment of {{ $quotation->termin }}% of the total price must be completed before
                                    starting the project. The remaining payment shall be paid upon completion of the
                                    work in accordance with the agreement.
                                </p>
                            @endif

                            @if ($quotation->maintenance)
                                <p class="text-xs font-normal w-80">
                                    The web maintenance period is {{ $quotation->maintenance }} working days, starting
                                    after the website goes live.
                                </p>
                            @endif
                        @endif
                    </th>
                </tr>

                @if ($taxTotal)
                    <tr class="">
                        <th scope="row" class="hidden pl-4 pr-3 pt-6 font-normal sm:table-cell sm:pl-0">
                            Subtotal
                        </th>
                        <td class="pl-3 pr-4 pt-6 sm:pr-0">
                            <span class="">{{ Number::currency($subtotal, 'IDR', 'id') }}</span>
                        </td>
                    </tr>
                    <tr class="">
                        <th scope="row" class="hidden pl-4 pr-3 pt-4 font-normal sm:table-cell sm:pl-0">
                            Tax
                        </th>
                        <td class="pl-3 pr-4 pt-4 sm:pr-0">
                            <span class="">{{ Number::currency($taxTotal, 'IDR', 'id') }}</span>
                        </td>
                    </tr>
                @endif

                <tr>
                    <th scope="row" class="hidden pl-4 pr-3 pt-6 font-semibold sm:table-cell sm:pl-0 align-top">
                        Total
                    </th>
                    <td class="pl-3 pr-4 pt-6 font-semibold sm:pr-0 align-top">
                        <span class="">{{ Number::currency($total, 'IDR', 'id') }}</span>
                    </td>
                </tr>
                <tr class="text-left">
                    <td scope="row" colspan="4" class="hidden sm:table-cell pt-6 sm:pl-0">
                        <p class="pb-1 font-bold">Payment Method</p>
                        <div class="text-xs font-normal text-left w-56">
                            <div class="flex justify-between pb-1">
                                <div class="w-1/2">
                                    <span>Bank</span>
                                </div>
                                <div class="w-1/2">
                                    <span>{{ $quotation->payment->bank }}</span>
                                </div>
                            </div>
                            <div class="flex justify-between pb-1">
                                <div class="w-1/2">
                                    <span>Account</span>
                                </div>
                                <div class="w-1/2">
                                    <span>{{ $quotation->payment->account }}</span>
                                </div>
                            </div>
                            <div class="flex justify-between pb-1">
                                <div class="w-1/2">
                                    <span>Name</span>
                                </div>
                                <div class="w-1/2">
                                    <span>{{ $quotation->payment->name }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
