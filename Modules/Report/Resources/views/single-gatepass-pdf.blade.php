<!DOCTYPE html>
<html>
<head>
  <title>CGL Gatepass</title>
    <link rel="icon" href="{{URL::asset('/images/cgl-favicon.png')}}" type="image/png" sizes="16x16">
  <style>
    /* Reset default margin and padding for the whole page */
    body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: "Times New Roman", Times, serif;
    }
    
    /* Define A4 paper size */
    @page {
            size: A4;
            margin: 0;
    }
    
    /* Define a header section */
    .header {
        margin-top: 12px!important;
        width: 100%!important;
        font-size: 14px!important;
        font-weight: bold;
        font-family: "Times New Roman", Times, serif;
    }

    .header td{
        margin-bottom: -20px;
        text-align: center;
        padding: 0 5px;
        font-size: 16px;
        border: 1px solid white;
        font-family: "Times New Roman", Times, serif;
    }

    /* Style the table */
    .other_table {
            margin-top: 5px;
            width: 100%;
            border-collapse: collapse;
            font-size: 11.5px;
            font-family: "Times New Roman", Times, serif;
    }

    .other_table th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-family: "Times New Roman", Times, serif;
    }

    .other_table th {
            background-color: #ccc;
            text-align: center;
            font-weight: bold;
            font-family: "Times New Roman", Times, serif;
    }
    .other_table tbody tr td {
        text-align: center
    }
    .sections{
            background-color: #e5e5e5;
            text-align: center!important;
            font-weight: bold;
            font-family: "Times New Roman", Times, serif;
    }
    .jhamela {
            margin-top: 12px!important;
            width: 100%!important;
            font-family: "Times New Roman", Times, serif;
      }
      .jhamela td {
            border: 1px solid white;
            font-weight: bold;
            font-size: 12.5px!important;
            font-family: "Times New Roman", Times, serif;
      }
      .other_table th, .other_table td {
            border: 1px solid #000;
            padding: 8px;
            font-family: "Times New Roman", Times, serif;
        }

    .other_table th {
        background-color: #ccc;
        text-align: center; /* Center align all headers */
        font-weight: bold;
    }

    .other_table td:nth-child(1), .other_table th:nth-child(1) {
        text-align: center; /* Center align SL column */
    }

    .other_table td:nth-child(2), .other_table th:nth-child(2) {
        text-align: center; /* Center align Item column */
    }

    .other_table td:nth-child(3), .other_table th:nth-child(3) {
        text-align: center; /* Center align Unit column */
    }

    .other_table td:nth-child(4) {
        text-align: center; /* Right align Qty data */
    }

    .other_table td:nth-child(5) {
        text-align: left; /* Left align Item Description data */
    }

    .generalTitle {
        margin-bottom: -3px;
        margin-left: 3px;
    }

  </style>
</head>
<body>
  <div>
    <table class="header">
        <tr>
                <td></td>
                <td>CGL</td>
                <td></td>
        </tr>
        <tr>
                <td></td>
                <td>Concorde Garments LTD</td>
                <td></td>
        </tr>
        <tr>
                <td></td>
                <td>Gatepass</td>
                <td></td>
        </tr> 
    </table>

    <table class="jhamela">
        <tr>
            <td id="gatepassNo">Gatepass No: {{ $gatepass->gate_pass_no }}</td>
            <td></td>
            <td id="gatepassCreationDate" style="text-align: right">Date: {{ $gatepass->creation_datetime }}</td>
        </tr>
    </table>
    <p class="generalTitle">Item Details</p>
    <table class="other_table">
        <thead>
            <tr>
                <th>SL</th>
                <th>Item</th>
                <th>Unit</th>
                <th>Qty</th>
                <th>Item Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gatepass->items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{$item->itemInfo?->name}}</td>
                    <td>{{$item->unitInfo?->name}}</td>
                    <td>{{$item->qty}}</td>
                    <td>{{$item->item_description}}</td>
                </tr>
            @endforeach
            {{-- <tfoot>
                <tr>
                        <td></td>
                        <td></td>
                        <td>{{ round($operation_bulletin->total_smv_machine_sam, 2) }}</td>
                        <td>{{ round($operation_bulletin->total_smv_helper_sam, 2) }}</td>
                        <td>{{ round($operation_bulletin->total_smv_iron_sam, 2) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ round($sum_worker_mc, 2) }}</td>
                        <td>{{ round($sum_worker_hp, 2) }}</td>
                        <td>{{ round($sum_worker_ir, 2) }}</td>
                </tr>
            </tfoot> --}}
        </tbody>
    </table>

  </div>

  <div style="margin-top: 20px;">
    <div style="diplay: inline-block">
        <div style="display:inline-block;width: 30%; text-align: center;">
            <p style="font-weight: bold;">Created By</p>
            <p>{{ $gatepass->createdByUser?->name }}</p>
        </div>
        <div style="display:inline-block;width: 30%; text-align: center;">
            <p style="font-weight: bold;">Department Head</p>
            <p>{{ $gatepass->hodUserName }}</p>
        </div>
        <div style="display:inline-block;width: 30%; text-align: center;">
            <p style="font-weight: bold;">Approved By</p>
            {{--
            @if($gatepass->imageFile)
            <p><img height="40" width="40" src="{{ $gatepass->imageFile }}" alt="GM Admin"></p>
            @endif
            --}}
            <p>{{ $gatepass->secondApprovedBy?->name }}</p>
        </div>
    </div>
</div>
</body>
</html>
