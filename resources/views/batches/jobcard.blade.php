<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Master Jobcard - {{ $partInternal->part_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.3;
        }

        .header {
            margin-bottom: 10px;
        }

        .company-name {
            color: #0033A0;
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .process-layout {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .qc-engg {
            float: right;
            font-size: 9pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 4px;
            text-align: left;
            vertical-align: top;
        }

        .label-cell {
            background-color: #f0f0f0;
            font-weight: bold;
            width: 20%;
        }

        .info-table td {
            padding: 3px 5px;
        }

        .operations-table th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
            font-size: 8pt;
        }

        .operations-table td {
            font-size: 8pt;
            padding: 5px;
        }

        .op-no {
            text-align: center;
            font-weight: bold;
            width: 5%;
        }

        .description {
            width: 60%;
        }

        .qty-col {
            width: 8%;
            text-align: center;
        }

        .date-col {
            width: 10%;
        }

        .sign-col {
            width: 9%;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 8pt;
            padding: 5px 0;
        }

        .page-break {
            page-break-after: always;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>

<body>
    <!-- PAGE 1 -->
    <div class="page-1">
        <div class="header clearfix">
            <div class="company-name">P.T. Metinca Prima Industrial Works</div>
            <div class="process-layout">
                PROCESS LAYOUT
                <span class="qc-engg">
                    <table style="border: 1px solid #000; display: inline-table; margin: 0;">
                        <tr>
                            <td style="padding: 2px 10px; border-right: 1px solid #000;">Q.C.</td>
                            <td style="padding: 2px 10px;">ENGG</td>
                        </tr>
                    </table>
                </span>
            </div>
        </div>

        <!-- Part Information Table -->
        <table class="info-table">
            <tr>
                <td class="label-cell">PART NO.INTERNAL</td>
                <td colspan="5"><strong>{{ $partInternal->part_number }}</strong></td>
            </tr>
            <tr>
                <td class="label-cell">DESCRIPTION :</td>
                <td colspan="5">{{ $partInternal->part_name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-cell">DRAWING NO:</td>
                <td style="width: 15%;">{{ $partInternal->drawing_no ?? '-' }}</td>
                <td class="label-cell" style="width: 15%;">DRG.ISS.DATE :</td>
                <td style="width: 15%;">{{ $partInternal->drg_iss_date ?? '-' }}</td>
                <td class="label-cell" style="width: 18%;">PART NO.CUSTOMER :</td>
                <td style="width: 12%;">{{ $partInternal->part_no_customer ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-cell">MATL. SPEC.</td>
                <td colspan="2"><strong>{{ $partInternal->matl_spec ?? '-' }}</strong></td>
                <td class="label-cell">DEOXIDATION :</td>
                <td colspan="2">{{ $partInternal->deoxidation ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-cell">MATL.REQD.EACH</td>
                <td><strong>{{ number_format($partInternal->matl_req ?? 0, 2) }} Kg</strong></td>
                <td class="label-cell">EST.YIELD %</td>
                <td>{{ $partInternal->est_yield ?? '93' }} %</td>
                <td class="label-cell">COMPONENTS PER MOULD</td>
                <td>{{ $partInternal->comp_per_mould ?? '1' }} PCE</td>
            </tr>
        </table>

        <!-- Operations Table -->
        <table class="operations-table">
            <thead>
                <tr>
                    <th class="op-no">OP<br>NO:</th>
                    <th class="description">DESCRIPTION OF OPERATION</th>
                    <th class="qty-col">QTY<br>PASS</th>
                    <th class="qty-col">QTY<br>SCRAP</th>
                    <th class="date-col">DATE</th>
                    <th class="sign-col">SUP<br>SIGN</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $page1Operations = $partInternal->partOperations->where('route_order', '<=', 6);
                @endphp

                @foreach ($page1Operations as $operation)
                    <tr>
                        <td class="op-no">{{ $operation->route_order }}</td>
                        <td class="description">
                            @if ($operation->division)
                                <strong>{{ strtoupper($operation->division->name) }}</strong><br>
                            @endif

                            @if ($operation->area)
                                Area: {{ $operation->area->name }}<br>
                            @endif

                            @if ($operation->operation_data)
                                {!! nl2br(e($operation->operation_data)) !!}
                            @endif

                            @if ($operation->process)
                                <br>
                                @if ($operation->process->equipment)
                                    Equipment: {{ $operation->process->equipment }}<br>
                                @endif
                                @if ($operation->process->capacity)
                                    Capacity: {{ $operation->process->capacity }} units<br>
                                @endif
                                @if ($operation->process->duration)
                                    Duration: {{ $operation->process->duration }} minutes<br>
                                @endif
                                @if ($operation->process->operator_count)
                                    Operators: {{ $operation->process->operator_count }} person(s)
                                @endif
                            @endif
                        </td>
                        <td class="qty-col">{{ $operation->getWipForBatchOperation($batch->id)->qty_pass ?? 0 }}</td>
                        <td class="qty-col">{{ $operation->getWipForBatchOperation($batch->id)->qty_scrap ?? 0 }}</td>
                        <td class="date-col">
                            {{ $operation->getWipForBatchOperation($batch->id)->operation_date ?? '' }}</td>
                        <td class="sign-col">
                            {{ $operation->getWipForBatchOperation($batch->id)->operator->name ?? '' }}
                        </td>
                    </tr>
                @endforeach

                @if ($page1Operations->count() < 10)
                    @for ($i = $page1Operations->count(); $i < 10; $i++)
                        <tr>
                            <td class="op-no">&nbsp;</td>
                            <td class="description">&nbsp;</td>
                            <td class="qty-col"></td>
                            <td class="qty-col"></td>
                            <td class="date-col"></td>
                            <td class="sign-col"></td>
                        </tr>
                    @endfor
                @endif
            </tbody>
        </table>

        <div class="footer">
            <table style="border: none; width: 100%;">
                <tr>
                    <td style="border: none; text-align: left;">F-007A Effective Date : {{ now()->format('d/m/Y') }}
                    </td>
                    <td style="border: none; text-align: right;">Page : 1 Of 2</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="page-break"></div>

    <!-- PAGE 2 -->
    <div class="page-2">
        <div class="header clearfix">
            <div class="company-name">P.T. Metinca Prima Industrial Works</div>
        </div>

        <!-- Page 2 Header Table -->
        <table class="info-table" style="margin-bottom: 5px;">
            <tr>
                <td rowspan="3" style="width: 20%; text-align: center; vertical-align: middle;">
                    <strong>PROCESS<br>CONTINUATION<br>LAYOUT</strong>
                </td>
                <td class="label-cell" style="width: 20%;">BATCH NO :</td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td class="label-cell">PART NO :</td>
                <td colspan="3">{{ $partInternal->part_number }}</td>
            </tr>
            <tr>
                <td class="label-cell">DRAWING NO :</td>
                <td style="width: 20%;">{{ $partInternal->drawing_no ?? '-' }}</td>
                <td class="label-cell" style="width: 20%;">PAGE</td>
                <td style="width: 20%;">OF PAGES</td>
            </tr>
        </table>

        <!-- Operations Table Page 2 -->
        <table class="operations-table">
            <thead>
                <tr>
                    <th class="op-no">OP<br>NO:</th>
                    <th class="description">DESCRIPTION OF OPERATION</th>
                    <th class="qty-col">QTY<br>PASS</th>
                    <th class="qty-col">QTY<br>SCRAP</th>
                    <th class="date-col">DATE</th>
                    <th class="sign-col">SUP<br>SIGN</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $page2Operations = $partInternal->partOperations->where('route_order', '>', 6);
                @endphp

                @foreach ($page2Operations as $operation)
                    <tr>
                        <td class="op-no">{{ $operation->route_order }}</td>
                        <td class="description">
                            @if ($operation->division)
                                <strong>{{ strtoupper($operation->division->name) }}</strong><br>
                            @endif

                            @if ($operation->area)
                                Area: {{ $operation->area->name }}<br>
                            @endif

                            @if ($operation->operation_data)
                                {!! nl2br(e($operation->operation_data)) !!}
                            @endif

                            @if ($operation->process)
                                <br>
                                @if ($operation->process->equipment)
                                    Equipment: {{ $operation->process->equipment }}<br>
                                @endif
                                @if ($operation->process->capacity)
                                    Capacity: {{ $operation->process->capacity }} units<br>
                                @endif
                                @if ($operation->process->duration)
                                    Duration: {{ $operation->process->duration }} minutes<br>
                                @endif
                                @if ($operation->process->operator_count)
                                    Operators: {{ $operation->process->operator_count }} person(s)
                                @endif
                            @endif
                        </td>
                        <td class="qty-col">{{ $operation->getWipForBatchOperation($batch->id)->qty_pass ?? 0 }}</td>
                        <td class="qty-col">{{ $operation->getWipForBatchOperation($batch->id)->qty_scrap ?? 0 }}</td>
                        <td class="date-col">
                            {{ $operation->getWipForBatchOperation($batch->id)->operation_date ?? '' }}</td>
                        <td class="sign-col">
                            {{ $operation->getWipForBatchOperation($batch->id)->operator->name ?? '' }}
                        </td>
                    </tr>
                @endforeach

                @if ($page2Operations->count() < 15)
                    @for ($i = $page2Operations->count(); $i < 15; $i++)
                        <tr>
                            <td class="op-no">&nbsp;</td>
                            <td class="description">&nbsp;</td>
                            <td class="qty-col"></td>
                            <td class="qty-col"></td>
                            <td class="date-col"></td>
                            <td class="sign-col"></td>
                        </tr>
                    @endfor
                @endif
            </tbody>
        </table>

        <!-- Amendment Table -->
        <table style="margin-top: 10px;">
            <tr>
                <th style="width: 10%; text-align: center;">ISS</th>
                <th style="width: 15%; text-align: center;">DATE</th>
                <th style="width: 15%; text-align: center;">SIGN</th>
                <th style="width: 60%; text-align: center;">AMENDEMENT / MODIFICATION</th>
            </tr>
            <tr>
                <td style="height: 30px;"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>

        <div class="footer">
            <table style="border: none; width: 100%;">
                <tr>
                    <td style="border: none; text-align: left;">F-007A Effective Date : {{ now()->format('d/m/Y') }}
                    </td>
                    <td style="border: none; text-align: right;">Page : 2 Of 2</td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
