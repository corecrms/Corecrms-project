@extends('back.layout.app')
@section('title', 'Shipment')
@section('style')
    <link href="{{ asset('back/assets/js/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <style>
        :root {
            --dt-row-selected: 255, 255, 255;
            --dt-row-selected-text: 0, 0, 0;
        }

        .dataTables_paginate {
            display: none;
        }

        .dataTables_length {
            display: none;
        }

        .dataTables_info {
            display: none;
        }

        .odd.selected {
            background-color: azure !important;
        }
    </style>
@endsection

@section('content')

    <div class="content">

        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Edit Shipments</h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow border-0 mt-5 rounded-3 p-4">
                <form id="shipmentForm" action="{{route('shipment.store')}}" method="POST">
                    @csrf
                    @method('POST')
                    <!-- Origin Address -->
                    <h4>Origin Address</h4>
                    <div class="row mt-2 mb-2">
                        <div class="form-group col-md-6">
                            <label for="originLine1">Address Line 1</label>
                            <input type="text" class="form-control" id="originLine1" name="origin_address[line_1]" required value="{{$shipment['shipment']['origin_address']['line_1'] ?? ''}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="originLine2">Address Line 2</label>
                            <input type="text" class="form-control" id="originLine2" name="origin_address[line_2]" value="{{$shipment['shipment']['origin_address']['line_2'] ?? ''}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="originState">State</label>
                            <input type="text" class="form-control" id="originState" name="origin_address[state]" value="{{$shipment['shipment']['origin_address']['state'] ?? ''}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="originCity">City</label>
                            <input type="text" class="form-control" id="originCity" name="origin_address[city]" required value="{{$shipment['shipment']['origin_address']['city'] ?? ''}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="originPostalCode">Postal Code</label>
                            <input type="text" class="form-control" id="originPostalCode"
                                name="origin_address[postal_code]" value="{{$shipment['shipment']['origin_address']['postal_code'] ?? ''}}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="originCountry">Country Code</label>
                            {{-- <input type="text" class="form-control" id="originCountry"
                                name="origin_address[country_alpha2]" required> --}}
                                <select name="origin_address[country_alpha2]" id="" class="form-control">
                                    <option value="AD">AD</option>
                                    <option value="AE">AE</option>
                                    <option value="AF">AF</option>
                                    <option value="AG">AG</option>
                                    <option value="AI">AI</option>
                                    <option value="AL">AL</option>
                                    <option value="AM">AM</option>
                                    <option value="AO">AO</option>
                                    <option value="AQ">AQ</option>
                                    <option value="AR">AR</option>
                                    <option value="AS">AS</option>
                                    <option value="AT">AT</option>
                                    <option value="AU">AU</option>
                                    <option value="AW">AW</option>
                                    <option value="AF">AF</option>
                                    <option value="AO">AO</option>
                                    <option value="AI">AI</option>
                                    <option value="AX">AX</option>
                                    <option value="AL">AL</option>
                                    <option value="AD">AD</option>
                                    <option value="AE">AE</option>
                                    <option value="AR">AR</option>
                                    <option value="AM">AM</option>
                                    <option value="AS">AS</option>
                                    <option value="AQ">AQ</option>
                                    <option value="TF">TF</option>
                                    <option value="AG">AG</option>
                                    <option value="AU">AU</option>
                                    <option value="AT">AT</option>
                                    <option value="AZ">AZ</option>
                                    <option value="BI">BI</option>
                                    <option value="BE">BE</option>
                                    <option value="BJ">BJ</option>
                                    <option value="BQ">BQ</option>
                                    <option value="BF">BF</option>
                                    <option value="BD">BD</option>
                                    <option value="BG">BG</option>
                                    <option value="BH">BH</option>
                                    <option value="BS">BS</option>
                                    <option value="BA">BA</option>
                                    <option value="BL">BL</option>
                                    <option value="BY">BY</option>
                                    <option value="BZ">BZ</option>
                                    <option value="BM">BM</option>
                                    <option value="BO">BO</option>
                                    <option value="BR">BR</option>
                                    <option value="BB">BB</option>
                                    <option value="BN">BN</option>
                                    <option value="BT">BT</option>
                                    <option value="BV">BV</option>
                                    <option value="BW">BW</option>
                                    <option value="CF">CF</option>
                                    <option value="CA">CA</option>
                                    <option value="CC">CC</option>
                                    <option value="CH">CH</option>
                                    <option value="CL">CL</option>
                                    <option value="CN">CN</option>
                                    <option value="CI">CI</option>
                                    <option value="CM">CM</option>
                                    <option value="CD">CD</option>
                                    <option value="CG">CG</option>
                                    <option value="CK">CK</option>
                                    <option value="CO">CO</option>
                                    <option value="KM">KM</option>
                                    <option value="CV">CV</option>
                                    <option value="CR">CR</option>
                                    <option value="CU">CU</option>
                                    <option value="CW">CW</option>
                                    <option value="CX">CX</option>
                                    <option value="KY">KY</option>
                                    <option value="CY">CY</option>
                                    <option value="CZ">CZ</option>
                                    <option value="DE">DE</option>
                                    <option value="DJ">DJ</option>
                                    <option value="DM">DM</option>
                                    <option value="DK">DK</option>
                                    <option value="DO">DO</option>
                                    <option value="DZ">DZ</option>
                                    <option value="EC">EC</option>
                                    <option value="EG">EG</option>
                                    <option value="ER">ER</option>
                                    <option value="EH">EH</option>
                                    <option value="ES">ES</option>
                                    <option value="EE">EE</option>
                                    <option value="ET">ET</option>
                                    <option value="FI">FI</option>
                                    <option value="FJ">FJ</option>
                                    <option value="FK">FK</option>
                                    <option value="FR">FR</option>
                                    <option value="FO">FO</option>
                                    <option value="FM">FM</option>
                                    <option value="GA">GA</option>
                                    <option value="GB">GB</option>
                                    <option value="GE">GE</option>
                                    <option value="GG">GG</option>
                                    <option value="GH">GH</option>
                                    <option value="GI">GI</option>
                                    <option value="GN">GN</option>
                                    <option value="GP">GP</option>
                                    <option value="GM">GM</option>
                                    <option value="GW">GW</option>
                                    <option value="GQ">GQ</option>
                                    <option value="GR">GR</option>
                                    <option value="GD">GD</option>
                                    <option value="GL">GL</option>
                                    <option value="GT">GT</option>
                                    <option value="GF">GF</option>
                                    <option value="GU">GU</option>
                                    <option value="GY">GY</option>
                                    <option value="HK">HK</option>
                                    <option value="HM">HM</option>
                                    <option value="HN">HN</option>
                                    <option value="HR">HR</option>
                                    <option value="HT">HT</option>
                                    <option value="HU">HU</option>
                                    <option value="ID">ID</option>
                                    <option value="IM">IM</option>
                                    <option value="IN">IN</option>
                                    <option value="IO">IO</option>
                                    <option value="IE">IE</option>
                                    <option value="IR">IR</option>
                                    <option value="IQ">IQ</option>
                                    <option value="IS">IS</option>
                                    <option value="IL">IL</option>
                                    <option value="IT">IT</option>
                                    <option value="JM">JM</option>
                                    <option value="JE">JE</option>
                                    <option value="JO">JO</option>
                                    <option value="JP">JP</option>
                                    <option value="KZ">KZ</option>
                                    <option value="KE">KE</option>
                                    <option value="KG">KG</option>
                                    <option value="KH">KH</option>
                                    <option value="KI">KI</option>
                                    <option value="KN">KN</option>
                                    <option value="KR">KR</option>
                                    <option value="KW">KW</option>
                                    <option value="LA">LA</option>
                                    <option value="LB">LB</option>
                                    <option value="LR">LR</option>
                                    <option value="LY">LY</option>
                                    <option value="LC">LC</option>
                                    <option value="LI">LI</option>
                                    <option value="LK">LK</option>
                                    <option value="LS">LS</option>
                                    <option value="LT">LT</option>
                                    <option value="LU">LU</option>
                                    <option value="LV">LV</option>
                                    <option value="MO">MO</option>
                                    <option value="MF">MF</option>
                                    <option value="MA">MA</option>
                                    <option value="MC">MC</option>
                                    <option value="MD">MD</option>
                                    <option value="MG">MG</option>
                                    <option value="MV">MV</option>
                                    <option value="MX">MX</option>
                                    <option value="MH">MH</option>
                                    <option value="MK">MK</option>
                                    <option value="ML">ML</option>
                                    <option value="MT">MT</option>
                                    <option value="MM">MM</option>
                                    <option value="ME">ME</option>
                                    <option value="MN">MN</option>
                                    <option value="MP">MP</option>
                                    <option value="MZ">MZ</option>
                                    <option value="MR">MR</option>
                                    <option value="MS">MS</option>
                                    <option value="MQ">MQ</option>
                                    <option value="MU">MU</option>
                                    <option value="MW">MW</option>
                                    <option value="MY">MY</option>
                                    <option value="YT">YT</option>
                                    <option value="NA">NA</option>
                                    <option value="NC">NC</option>
                                    <option value="NE">NE</option>
                                    <option value="NF">NF</option>
                                    <option value="NG">NG</option>
                                    <option value="NI">NI</option>
                                    <option value="NU">NU</option>
                                    <option value="NL">NL</option>
                                    <option value="NO">NO</option>
                                    <option value="NP">NP</option>
                                    <option value="NR">NR</option>
                                    <option value="NZ">NZ</option>
                                    <option value="OM">OM</option>
                                    <option value="PK">PK</option>
                                    <option value="PA">PA</option>
                                    <option value="PN">PN</option>
                                    <option value="PE">PE</option>
                                    <option value="PH">PH</option>
                                    <option value="PW">PW</option>
                                    <option value="PG">PG</option>
                                    <option value="PL">PL</option>
                                    <option value="PR">PR</option>
                                    <option value="KP">KP</option>
                                    <option value="PT">PT</option>
                                    <option value="PY">PY</option>
                                    <option value="PS">PS</option>
                                    <option value="PF">PF</option>


                                    <!-- Add more options here -->
                                </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="originContactName">Contact Name</label>
                            <input type="text" class="form-control" id="originContactName"
                                name="origin_address[contact_name]" required value="{{$shipment['shipment']['origin_address']['contact_name'] ?? ''}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="originCompanyName">Company Name</label>
                            <input type="text" class="form-control" id="originCompanyName"
                                name="origin_address[company_name]" value="{{$shipment['shipment']['origin_address']['company_name'] ?? ''}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="originContactPhone">Contact Phone</label>
                            <input type="text" class="form-control" id="originContactPhone"
                                name="origin_address[contact_phone]" required value="{{$shipment['shipment']['origin_address']['contact_phone'] ?? ''}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="originContactEmail">Contact Email</label>
                            <input type="email" class="form-control" id="originContactEmail"
                                name="origin_address[contact_email]" required value="{{$shipment['shipment']['origin_address']['contact_email'] ?? ''}}">
                        </div>
                    </div>

                    <!-- Destination Address -->
                    <h4>Destination Address</h4>
                    <div class="row mt-2 mb-2">
                        <div class="form-group col-md-6">
                            <label for="originLine1">Address Line 1</label>
                            <input type="text" class="form-control" id="originLine1" name="destination_address[line_1]"
                                required value="{{$shipment['shipment']['destination_address']['line_1'] ?? ''}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="originLine2">Address Line 2</label>
                            <input type="text" class="form-control" id="originLine2" name="destination_address[line_2]" value="{{$shipment['shipment']['destination_address']['line_2'] ?? ''}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="originState">State</label>
                            <input type="text" class="form-control" id="originState" name="destination_address[state]" value="{{$shipment['shipment']['destination_address']['state'] ?? ''}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="originCity">City</label>
                            <input type="text" class="form-control" id="originCity" name="destination_address[city]" value="{{$shipment['shipment']['destination_address']['city'] ?? ''}}"
                                required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="originPostalCode">Postal Code</label>
                            <input type="text" class="form-control" id="originPostalCode"
                                name="destination_address[postal_code]" required value="{{$shipment['shipment']['destination_address']['postal_code'] ?? ''}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="originCountry">Country Code</label>
                            {{-- <input type="text" class="form-control" id="originCountry"
                                name="destination_address[country_alpha2]" required> --}}
                                <select name="destination_address[country_alpha2]" id="" class="form-control">
                                    <option value="AD">AD</option>
                                    <option value="AE">AE</option>
                                    <option value="AF">AF</option>
                                    <option value="AG">AG</option>
                                    <option value="AI">AI</option>
                                    <option value="AL">AL</option>
                                    <option value="AM">AM</option>
                                    <option value="AO">AO</option>
                                    <option value="AQ">AQ</option>
                                    <option value="AR">AR</option>
                                    <option value="AS">AS</option>
                                    <option value="AT">AT</option>
                                    <option value="AU">AU</option>
                                    <option value="AW">AW</option>
                                    <option value="AF">AF</option>
                                    <option value="AO">AO</option>
                                    <option value="AI">AI</option>
                                    <option value="AX">AX</option>
                                    <option value="AL">AL</option>
                                    <option value="AD">AD</option>
                                    <option value="AE">AE</option>
                                    <option value="AR">AR</option>
                                    <option value="AM">AM</option>
                                    <option value="AS">AS</option>
                                    <option value="AQ">AQ</option>
                                    <option value="TF">TF</option>
                                    <option value="AG">AG</option>
                                    <option value="AU">AU</option>
                                    <option value="AT">AT</option>
                                    <option value="AZ">AZ</option>
                                    <option value="BI">BI</option>
                                    <option value="BE">BE</option>
                                    <option value="BJ">BJ</option>
                                    <option value="BQ">BQ</option>
                                    <option value="BF">BF</option>
                                    <option value="BD">BD</option>
                                    <option value="BG">BG</option>
                                    <option value="BH">BH</option>
                                    <option value="BS">BS</option>
                                    <option value="BA">BA</option>
                                    <option value="BL">BL</option>
                                    <option value="BY">BY</option>
                                    <option value="BZ">BZ</option>
                                    <option value="BM">BM</option>
                                    <option value="BO">BO</option>
                                    <option value="BR">BR</option>
                                    <option value="BB">BB</option>
                                    <option value="BN">BN</option>
                                    <option value="BT">BT</option>
                                    <option value="BV">BV</option>
                                    <option value="BW">BW</option>
                                    <option value="CF">CF</option>
                                    <option value="CA">CA</option>
                                    <option value="CC">CC</option>
                                    <option value="CH">CH</option>
                                    <option value="CL">CL</option>
                                    <option value="CN">CN</option>
                                    <option value="CI">CI</option>
                                    <option value="CM">CM</option>
                                    <option value="CD">CD</option>
                                    <option value="CG">CG</option>
                                    <option value="CK">CK</option>
                                    <option value="CO">CO</option>
                                    <option value="KM">KM</option>
                                    <option value="CV">CV</option>
                                    <option value="CR">CR</option>
                                    <option value="CU">CU</option>
                                    <option value="CW">CW</option>
                                    <option value="CX">CX</option>
                                    <option value="KY">KY</option>
                                    <option value="CY">CY</option>
                                    <option value="CZ">CZ</option>
                                    <option value="DE">DE</option>
                                    <option value="DJ">DJ</option>
                                    <option value="DM">DM</option>
                                    <option value="DK">DK</option>
                                    <option value="DO">DO</option>
                                    <option value="DZ">DZ</option>
                                    <option value="EC">EC</option>
                                    <option value="EG">EG</option>
                                    <option value="ER">ER</option>
                                    <option value="EH">EH</option>
                                    <option value="ES">ES</option>
                                    <option value="EE">EE</option>
                                    <option value="ET">ET</option>
                                    <option value="FI">FI</option>
                                    <option value="FJ">FJ</option>
                                    <option value="FK">FK</option>
                                    <option value="FR">FR</option>
                                    <option value="FO">FO</option>
                                    <option value="FM">FM</option>
                                    <option value="GA">GA</option>
                                    <option value="GB">GB</option>
                                    <option value="GE">GE</option>
                                    <option value="GG">GG</option>
                                    <option value="GH">GH</option>
                                    <option value="GI">GI</option>
                                    <option value="GN">GN</option>
                                    <option value="GP">GP</option>
                                    <option value="GM">GM</option>
                                    <option value="GW">GW</option>
                                    <option value="GQ">GQ</option>
                                    <option value="GR">GR</option>
                                    <option value="GD">GD</option>
                                    <option value="GL">GL</option>
                                    <option value="GT">GT</option>
                                    <option value="GF">GF</option>
                                    <option value="GU">GU</option>
                                    <option value="GY">GY</option>
                                    <option value="HK">HK</option>
                                    <option value="HM">HM</option>
                                    <option value="HN">HN</option>
                                    <option value="HR">HR</option>
                                    <option value="HT">HT</option>
                                    <option value="HU">HU</option>
                                    <option value="ID">ID</option>
                                    <option value="IM">IM</option>
                                    <option value="IN">IN</option>
                                    <option value="IO">IO</option>
                                    <option value="IE">IE</option>
                                    <option value="IR">IR</option>
                                    <option value="IQ">IQ</option>
                                    <option value="IS">IS</option>
                                    <option value="IL">IL</option>
                                    <option value="IT">IT</option>
                                    <option value="JM">JM</option>
                                    <option value="JE">JE</option>
                                    <option value="JO">JO</option>
                                    <option value="JP">JP</option>
                                    <option value="KZ">KZ</option>
                                    <option value="KE">KE</option>
                                    <option value="KG">KG</option>
                                    <option value="KH">KH</option>
                                    <option value="KI">KI</option>
                                    <option value="KN">KN</option>
                                    <option value="KR">KR</option>
                                    <option value="KW">KW</option>
                                    <option value="LA">LA</option>
                                    <option value="LB">LB</option>
                                    <option value="LR">LR</option>
                                    <option value="LY">LY</option>
                                    <option value="LC">LC</option>
                                    <option value="LI">LI</option>
                                    <option value="LK">LK</option>
                                    <option value="LS">LS</option>
                                    <option value="LT">LT</option>
                                    <option value="LU">LU</option>
                                    <option value="LV">LV</option>
                                    <option value="MO">MO</option>
                                    <option value="MF">MF</option>
                                    <option value="MA">MA</option>
                                    <option value="MC">MC</option>
                                    <option value="MD">MD</option>
                                    <option value="MG">MG</option>
                                    <option value="MV">MV</option>
                                    <option value="MX">MX</option>
                                    <option value="MH">MH</option>
                                    <option value="MK">MK</option>
                                    <option value="ML">ML</option>
                                    <option value="MT">MT</option>
                                    <option value="MM">MM</option>
                                    <option value="ME">ME</option>
                                    <option value="MN">MN</option>
                                    <option value="MP">MP</option>
                                    <option value="MZ">MZ</option>
                                    <option value="MR">MR</option>
                                    <option value="MS">MS</option>
                                    <option value="MQ">MQ</option>
                                    <option value="MU">MU</option>
                                    <option value="MW">MW</option>
                                    <option value="MY">MY</option>
                                    <option value="YT">YT</option>
                                    <option value="NA">NA</option>
                                    <option value="NC">NC</option>
                                    <option value="NE">NE</option>
                                    <option value="NF">NF</option>
                                    <option value="NG">NG</option>
                                    <option value="NI">NI</option>
                                    <option value="NU">NU</option>
                                    <option value="NL">NL</option>
                                    <option value="NO">NO</option>
                                    <option value="NP">NP</option>
                                    <option value="NR">NR</option>
                                    <option value="NZ">NZ</option>
                                    <option value="OM">OM</option>
                                    <option value="PK">PK</option>
                                    <option value="PA">PA</option>
                                    <option value="PN">PN</option>
                                    <option value="PE">PE</option>
                                    <option value="PH">PH</option>
                                    <option value="PW">PW</option>
                                    <option value="PG">PG</option>
                                    <option value="PL">PL</option>
                                    <option value="PR">PR</option>
                                    <option value="KP">KP</option>
                                    <option value="PT">PT</option>
                                    <option value="PY">PY</option>
                                    <option value="PS">PS</option>
                                    <option value="PF">PF</option>


                                    <!-- Add more options here -->
                                </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="originContactName">Contact Name</label>
                            <input type="text" class="form-control" id="originContactName"
                                name="destination_address[contact_name]" required value="{{$shipment['shipment']['destination_address']['contact_name'] ?? ''}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="originCompanyName">Company Name</label>
                            <input type="text" class="form-control" id="originCompanyName"
                                name="destination_address[company_name]" value="{{$shipment['shipment']['destination_address']['company_name'] ?? ''}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="originContactPhone">Contact Phone</label>
                            <input type="text" class="form-control" id="originContactPhone"
                                name="destination_address[contact_phone]" required value="{{$shipment['shipment']['destination_address']['contact_phone'] ?? ''}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="originContactEmail">Contact Email</label>
                            <input type="email" class="form-control" id="originContactEmail"
                                name="destination_address[contact_email]" required value="{{$shipment['shipment']['destination_address']['contact_email'] ?? ''}}">
                        </div>
                    </div>

                    <!-- Parcels -->
                    <div class="d-flex justify-content-between mt-2 mb-2">
                        <h4>Parcels</h4>
                        <button type="button" class="btn btn-success btn-sm" id="addParcelBtn">Add
                            Parcel</button>
                    </div>
                    <div id="parcels">
                        @foreach ($shipment['shipment']['parcels'] as $parcel)
                        <div id="parcels mt-2">
                            <div class="parcel mt-2">
                                <h5>Parcel {{$loop->iteration}}</h5>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="totalActualWeight">Actual Weight</label>
                                        <input type="number" class="form-control" id="totalActualWeight"
                                            name="parcels[0][items][0][actual_weight]" required value="{{$parcel['items'][0]['actual_weight']}}">
                                    </div>
                                    <!-- Add more fields for items within the parcel -->
                                    <div class="form-group col-md-6">
                                        <label for="itemDescription">Item Description</label>
                                        <input type="text" class="form-control" id="itemDescription"
                                            name="parcels[0][items][0][description]" required value="{{$parcel['items'][0]['description']}}">
                                    </div>
                                    {{-- <div class="form-group col-md-6">
                                        <label for="itemHsCode">Item Category</label>
                                        <input type="text" class="form-control" id="itemHsCode"
                                            name="parcels[0][items][0][item_category_id]" required>
                                    </div> --}}
                                    <div class="form-group col-md-6">
                                        <label for="itemHsCode">Hs Code</label>
                                        <input type="text" class="form-control" id="itemHsCode"
                                            name="parcels[0][items][0][hs_code]" required value="{{$parcel['items'][0]['hs_code']}}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="itemSku">SKU</label>
                                        <input type="text" class="form-control" id="itemSku"
                                            name="parcels[0][items][0][sku]" required value="{{$parcel['items'][0]['sku']}}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="itemOriginCountry">Qty</label>
                                        <input type="text" class="form-control" id="itemOriginCountry"
                                            name="parcels[0][items][0][quantity]" required value="{{$parcel['items'][0]['quantity']}}">
                                    </div>
                                    {{-- <h6 class="text-secondary">Demension</h6> --}}
                                    <div class="form-group col-md-6">
                                        <label for="itemOriginCountry">length</label>
                                        <input type="text" class="form-control" id="itemOriginCountry"
                                            name="parcels[0][items][0][dimensions][length]" required value="{{$parcel['items'][0]['dimensions']['length']}}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="itemOriginCountry">Width</label>
                                        <input type="text" class="form-control" id="itemOriginCountry"
                                            name="parcels[0][items][0][dimensions][width]" required value="{{$parcel['items'][0]['dimensions']['width']}}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="itemOriginCountry">Height</label>
                                        <input type="text" class="form-control" id="itemOriginCountry"
                                            name="parcels[0][items][0][dimensions][height]" required value="{{$parcel['items'][0]['dimensions']['height']}}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="itemOriginCountry">Declear Currency</label>
                                        {{-- <input type="text" class="form-control" id="itemOriginCountry"
                                            name="parcels[0][items][0][declared_currency]" required> --}}
                                        <select name="parcels[0][items][0][declared_currency]" id=""
                                            class="form-control">
                                            <option value="USD" {{$parcel['items'][0]['declared_currency'] == "USD" ? 'selected': ''}}>USD</option>
                                            <option value="EUR" {{$parcel['items'][0]['declared_currency'] == "EUR" ? 'selected': ''}}>EUR</option>
                                            <option value="GBP">GBP</option>
                                            <option value="AUD">AUD</option>
                                            <option value="CAD">CAD</option>
                                            <option value="JPY">JPY</option>
                                            <option value="CNY">CNY</option>
                                            <option value="INR" {{$parcel['items'][0]['declared_currency'] == "CNY" ? 'selected': ''}}>INR</option>
                                            <option value="PKR" {{$parcel['items'][0]['declared_currency'] == "PKR" ? 'selected': ''}}>PKR</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="itemOriginCountry">Declear Custom Value</label>
                                        <input type="text" class="form-control" id="itemOriginCountry"
                                            name="parcels[0][items][0][declared_customs_value]" required value="{{$parcel['items'][0]['declared_customs_value']}}">
                                    </div>

                                </div>

                                {{-- <button type="button" class="btn btn-danger btn-sm mt-3 remove-parcel-btn">Remove
                                    Parcel</button> --}}

                            </div>

                        </div>
                        @endforeach

                    <!-- Submit Button -->
                    <button type="submit" class="btn save-btn text-white mt-3">Submit</button>
                </form>





            </div>
        @endsection


        @section('scripts')
           <script>
             $(document).ready(function() {
                // Add parcel
                let parcelIndex = {{count($shipment['shipment']['parcels']) ?? 1}};
                // let parcelIndex = 1;
                $('#addParcelBtn').click(function() {
                    parcelIndex++;
                    var newParcel = `
                    <div class="parcel mt-2">
               <h5>Parcel ${parcelIndex}</h5>
                    <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="totalActualWeight">Actual Weight</label>
                                    <input type="number" class="form-control" id="totalActualWeight"
                                        name="parcels[${parcelIndex - 1}][items][0][actual_weight]" required>
                                </div>
                                <!-- Add more fields for items within the parcel -->
                                <div class="form-group col-md-6">
                                    <label for="itemDescription">Item Description</label>
                                    <input type="text" class="form-control" id="itemDescription"
                                        name="parcels[${parcelIndex - 1}][items][0][description]" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="itemHsCode">Hs Code</label>
                                    <input type="text" class="form-control" id="itemHsCode"
                                        name="parcels[${parcelIndex - 1}][items][0][hs_code]" required>
                                </div>
                                {{-- <div class="form-group col-md-6">
                                    <label for="itemContainsBatteryPi966">Contains Battery (PI966)</label>
                                    <select class="form-control" id="itemContainsBatteryPi966"
                                        name="parcels[${parcelIndex - 1}][items][0][contains_battery_pi966]" required>
                                        <option value="true">Yes</option>
                                        <option value="false">No</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="itemContainsBatteryPi967">Contains Battery (PI967)</label>
                                    <select class="form-control" id="itemContainsBatteryPi967"
                                        name="parcels[${parcelIndex - 1}][items][0][contains_battery_pi967]" required>
                                        <option value="true">Yes</option>
                                        <option value="false">No</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="itemContainsLiquids">Contains Liquids</label>
                                    <select class="form-control" id="itemContainsLiquids"
                                        name="parcels[${parcelIndex - 1}][items][0][contains_liquids]" required>
                                        <option value="true">Yes</option>
                                        <option value="false">No</option>
                                    </select>
                                </div> --}}
                                <div class="form-group col-md-6">
                                    <label for="itemSku">SKU</label>
                                    <input type="text" class="form-control" id="itemSku"
                                        name="parcels[${parcelIndex - 1}][items][0][sku]" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="itemOriginCountry">Qty</label>
                                    <input type="text" class="form-control" id="itemOriginCountry"
                                        name="parcels[${parcelIndex - 1}][items][0][quantity]" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="itemOriginCountry">length</label>
                                    <input type="text" class="form-control" id="itemOriginCountry"
                                        name="parcels[${parcelIndex - 1}][items][0][dimensions][length]" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="itemOriginCountry">Width</label>
                                    <input type="text" class="form-control" id="itemOriginCountry"
                                        name="parcels[${parcelIndex - 1}][items][0][dimensions][width]" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="itemOriginCountry">Height</label>
                                    <input type="text" class="form-control" id="itemOriginCountry"
                                        name="parcels[${parcelIndex - 1}][items][0][dimensions][height]" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="itemOriginCountry">Declear Currency</label>
                                    {{-- <input type="text" class="form-control" id="itemOriginCountry"
                                        name="parcels[0][items][0][declared_currency]" required> --}}
                                    <select name="parcels[${parcelIndex - 1}][items][0][declared_currency]" id=""
                                        class="form-control">
                                        <option value="USD">USD</option>
                                        <option value="EUR">EUR</option>
                                        <option value="GBP">GBP</option>
                                        <option value="AUD">AUD</option>
                                        <option value="CAD">CAD</option>
                                        <option value="JPY">JPY</option>
                                        <option value="CNY">CNY</option>
                                        <option value="INR">INR</option>
                                        <option value="PKR">PKR</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="itemOriginCountry">Declear Custom Value</label>
                                    <input type="text" class="form-control" id="itemOriginCountry"
                                        name="parcels[${parcelIndex - 1}][items][0][declared_customs_value]" required>
                                </div>

                            </div>

                            <button type="button" class="btn btn-danger btn-sm remove-parcel-btn mt-3">Remove Parcel</button>
                </div>
                    `;
                    $('#parcels').append(newParcel);
                });


                $(document).on('click', '.remove-parcel-btn', function() {
                    $(this).closest('.parcel').remove();
                });

            });
           </script>
        @endsection
