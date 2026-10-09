<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- CSRF Token -->
    {{--
    <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
        .page_break {
            page-break-before: always;
            margin-top: 100px;
        }

        #first {
            display: none;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
            page-break-inside: auto;
            width: 100%;
        }

        body {
            /* font-family: "Century Gothic", CenturyGothic, AppleGothic, sans-serif; */
            font-size: 9px;
            margin-top: 120px;
        }

        @page {
            margin: 80px 50px 80px 50px;
        }

        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
            color: black;
            text-align: left;
            background-color: #ffffff;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
        }

        .page-number:after {
            content: counter(page);
        }

        thead {
            display: table-row-group;
        }

        tr {
            page-break-inside: auto;
        }

        p {
            text-align: justify;
            text-justify: inter-word;
            margin: 0;
            padding: 0;
            font-size: 8;
        }

        .upperline {
            -webkit-text-decoration-line: overline;
            /* Safari */
            text-decoration: overline
        }

        hr {
            margin-top: 0em;
            margin-bottom: 0em;
            border: none;
            height: 2px;
            /* Set the hr color */
            color: #333;
            /* old IE */
            background-color: #333;
            /* Modern Browsers */
        }

        hr.soft {
            margin-top: 0em;
            margin-bottom: 0em;
            border: none;
            height: .5px;
        }

        input[type=checkbox] {
            display: inline;
        }

        /* tr.no-bottom-border td {
            border-bottom: none;
            border-top: none;
        } */

        .shade-box {
            display: inline-block;
            width: 10px;
            height: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.3);
            padding: 3px;
            text-align: center;
            /* line-height: 110px; */
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body>
    <footer>
        <table style='width:100%;' border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class='text-left"'>
                    <p class="m-0" style="font-size:9"></p>
                    <p class="m-0" style="font-size:9"></p>
                </td>
                <td class='text-center'>
                    <i ></i>
                </td>
                <td class='text-right'>
                    <span class="page-number">Page <script type="text/php">{PAGE_NUM} of {PAGE_COUNT}</script></span>
                </td>
            </tr>
        </table>
    </footer>
    <header>
        <table style='width:100%;' border="2" cellspacing="0" cellpadding="0">
            <tr>
                <td width='100px' style='width:20; text-align:center;' rowspan="2">
                    <img src="{{get_company_logo($resign->company)}}" alt="Company logo" height="100" style="margin: auto;">
                </td>
             
            </tr>
            <tr>
                <td colspan="3" class='text-center' style="margin-top:0; padding:0;">
                    <p style="font-size: 9">Form Title :</p>
                    <p style="font-weight: bold; font-size:17px;" class="text-center m-0">CLEARANCE FORM</p>
                </td>
            </tr>
        </table>
    </header>
    <table style='width:100%;' border="1" cellspacing="0" cellpadding="1">
        <tr>
            <td colspan="2">
                <p style="font-weight: bold;">Employee Name: <span class="font-weight-normal">{{$resign->Employee->user_info->name}}</span></p>
            </td>
            <td colspan="2">
                <p style="font-weight: bold;">Date Filed: <span class="font-weight-normal">{{date('F j, Y', strtotime($resign->created_at))}}</span></p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p style="font-weight: bold;">Position: <span class="font-weight-normal">{{$resign->Employee->position}}</span></p>
            </td>
            <td colspan="2">
                <p style="font-weight: bold;">Department: <span class="font-weight-normal">{{$resign->Employee->department->name}}</span></p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p style="font-weight: bold;">Date Hired: <span class="font-weight-normal">{{date('F j, Y', strtotime($resign->date_hired))}}</span></p>
            </td>
            <td colspan="2">
                <p style="font-weight: bold;">Last Date of Employment: <span class="font-weight-normal">{{date('F j, Y', strtotime($resign->last_date))}}</span></p>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <p style="font-weight: bold;">Reason for Separation: <span class="font-weight-normal">{!! nl2br(e($resign->reason_for_separation)) !!}</span></p>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <p class="font-weight-bold text-center">SEE INSTRUCTIONS ON PAGE 2</p>
                <p class="text-center">(Clearance procedure for the employee and instructions for Authorized Signatories on how to fill-out the clearance)</p>
                <p class="font-weight-bold text-center"><i>**** TO BE PROCESSED BY RESIGNED EMPLOYEE ****</i></p>
            </td>
        </tr>
        <tr>
            <td style="background-color: #e4e4e4;">
                <p class="font-weight-bold text-center">Department</p>
            </td>
            <td style="background-color: #e4e4e4;">
                <p class="font-weight-bold text-center">Accountability</p>
                <p class="font-weight-bold text-center">(Put " ✓ " or "N/A")</p>
            </td>
            <td style="background-color: #e4e4e4;">
                <p class="font-weight-bold text-center">Remarks / Status</p>
            </td>
            <td style="background-color: #e4e4e4;">
                <p class="font-weight-bold text-center">Name & Signature of Authorized Representative</p>
            </td>
        </tr>
        @foreach ($resign->exit_clearance as $key=> $exit)
            <tr>
                <td>
                    @if($exit->department_id == "immediate_sup")
                        <p class="text-uppercase">A. Immediate Sup</p>
                    @elseif($exit->department_id == "dep_head")
                        <p class="text-uppercase">B. Dept Head</p>
                    @endif

                    @if($exit->department)
                        <p class="text-left">{{ chr(67 + $key) }}. {{ strtoupper($exit->department->name) }}</p>
                    @endif
                </td>
                <td>
                    @foreach($exit->checklists as $checklist)
                        {{-- <p class="mb-0"><span><input type="checkbox" class="mb-0" @if($checklist->status == 'Completed' || $checklist->status == 'N/A') checked @endif> </span>{{$checklist->checklist}}</p> --}}

                        <p class="mb-0">
                            <div class="shade-box" style="@if($checklist->status == 'Completed' || $checklist->status == 'N/A') background-color:black; @endif"></div>
                            {{$checklist->checklist}}
                        </p>
                    @endforeach
                </td>
                <td>
                    @php
                        $is_completed = false;
                    @endphp
                    @foreach($exit->checklists as $checklist)
                        @if($checklist->exit_clearance_id == $exit->id)
                            @php
                                $check = $exit->checklists->every(function($item){
                                    return $item->status == 'Completed' || $item->status == 'N/A';
                                });

                                if ($check) {
                                    $is_completed = true;
                                }
                            @endphp
                        @endif
                    @endforeach

                    @if($is_completed)
                        <p class="text-center">Completed</p>
                    @endif
                </td>
                <td>
                    @foreach($exit->signatories as $signatory)
                        <p class="m-0 text-center @if($signatory->status == 'Pending') text-danger @else text-success @endif">{{$signatory->Employee->user_info->name}}</p>
                    @endforeach
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4" style="background-color: #e4e4e4;">
                <p class="text-center font-weight-bold">CONSENT TO SETTLE OUTSTANDING OBLIGATIONS & AUTHORITY TO DEDUCT</p>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <p class="text-center" style="font-size: 7;">
                    I understand that clearance is my responsibility and the company can hold my salary if clearance is not complete.   
                    This is also to authorize the Company to deduct from my final pay any outstanding obligation for money, asset and/or property accounted to me as stated in this clearance. If 
                    my final pay is insufficient to cover the amount or cost of my outstanding obligations and accountabilities, I agree to settle the remaining balance within a reasonable time as 
                    approved by Accounting Department and Head of Business Unit.
                </p>

                <table style='width:100%; margin-top:10px; table-layout:fixed;' border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <p class="font-weight-bold text-center">
                                Confirm: 
                                <span id="confirm" style="border-bottom: 1px solid black; display: inline-block; width: 150px;" class="font-weight-normal">
                                    {{$resign->Employee->user_info->name}}
                                </span>
                            </p>
                            <p class="text-center ml-5 mb-0 mt-0">
                                Name & Signature
                            </p>
                        </td>
                        <td>
                            <p class="font-weight-bold text-center">Date: 
                                <span style="border-bottom: 1px solid black; display: inline-block; width: 150px;" class="font-weight-normal text-center">
                                    {{date('F j, Y')}}
                                </span>
                            </p>
                            
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <div class="page-break">

        {{-- <p class="font-weight-bold">Clearance Procedure:</p>
        <p>Resigning employee shall be responsible for processing his/her clearance documents.</p>
        <p class="mt-4">The following steps must be followed:</p>
        <p class="text-center font-weight-bold">**** TO BE PROCESSED BY RESIGNED EMPLOYEE ****</p> --}}
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
</body>

</html>
