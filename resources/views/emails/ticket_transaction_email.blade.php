<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta content="telephone=no" name="format-detection" />
        <title>Match booking</title>
        <style type="text/css">
            html,
            body { 
                padding:0 !important; 
                margin:0 !important; 
                display:block !important; 
                width:100% !important; 
                background:#f9f9f9; 
                -webkit-text-size-adjust:none; 
                -ms-text-size-adjust: none;
            }
            /* Mobile styles */
            @media only screen and (max-device-width: 640px), only screen and (max-width: 640px) { 
                table[class=devicewidth] {
                    width: 99%!important;
                }  
                td[class=column] { 
                    float: left !important; 
                    display: block !important; 
                    width:100% !important;
                    margin:0 auto!important;
                }
                div[class=text-center] {
                    text-align: center !important;
                }
            }
            .primary-color
            {
                color: {{$clubDetail->primary_colour}} !important;
            }
            .secondary-color
            {
                color: {{$clubDetail->secondary_colour}} !important;
            }
        </style>
    </head>
    <body style="padding:0 !important; margin:0 !important; display:block !important; width:100% !important; background:#f9f9f9; -webkit-text-size-adjust:none">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#eceef1" align="center" class="devicewidth">
            <tr>
                <td align="center" valign="top">
                    <table width="680" border="0" cellspacing="0" cellpadding="0" class="devicewidth" bgcolor="#ffffff">
                        <tr>
                            <td>
                                <!-- Section Logo -->
                                <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#eceef1">
                                    <tr>
                                        <td style="font-size:0pt; line-height:0pt; text-align:left" width="30"></td> 

                                        <td width="620" align="center">
                                            <div style="display:block; font-size:0pt; line-height:0pt; height:20px;"></div>

                                            <div style="font-size:0pt; line-height:0pt;">
                                                <img src="{{asset(config('mail.mail_config.logo'))}}" border="0" alt="qrCode" width="100%" style="width:100%; max-width: 300px;">
                                            </div>

                                            <div style="display:block; font-size:0pt; line-height:0pt; height:20px;"></div>
                                        </td>

                                        <td style="font-size:0pt; line-height:0pt; text-align:left" width="30"></td>    
                                    </tr>
                                </table>
                                <!-- end Section Logo --> 

                                <!-- Section -->
                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="font-size:0pt; line-height:0pt; text-align:left" width="30"></td> 

                                        <td width="620" align="center">
                                            <!-- spacer -->
                                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td>
                                                        <div style="display:block; font-size:0pt; line-height:0pt; height:30px;"></div>
                                                    </td>    
                                                </tr>
                                            </table>
                                            <!-- end spacer -->

                                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td width="30" class="column" style="font-size:0pt; line-height:0pt; text-align:left"></td> 

                                                    <td width="560" class="column" align="center">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tr>
                                                                <td width="100%" align="center">
                                                                    <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; font-weight: 600;">
                                                                        You’re going to the match!
                                                                    </div>

                                                                    <div style="display:block; font-size:0pt; line-height:0pt; height:20px;"></div>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                                            <tr>
                                                                <td width="45%">
                                                                    <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
                                                                        <tr>
                                                                            <td width="60%">
                                                                                <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 18px;  text-align: right;">
                                                                                    {{$ticketTransacton->match->homeTeam->name}}
                                                                                </div>
                                                                                </td>
                                                                            <td width="40%" align="center">@if($ticketTransacton->match->homeTeam->logo)<img src="{{$ticketTransacton->match->homeTeam->logo}}" style="height: 50px;"> @else <img src="{{asset(config('mail.mail_config.noimage'))}}" style="height: 50px;width:50px"> @endif </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td width="10%" align="center">
                                                                    <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; ">
                                                                        V
                                                                    </div>
                                                                </td>

                                                                <td width="45%">
                                                                    <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
                                                                        <tr>
                                                                            <td width="40%" align="center">@if($ticketTransacton->match->awayTeam->logo) <img src="{{$ticketTransacton->match->awayTeam->logo}}" style="height: 50px;"> @else <img src="{{asset(config('mail.mail_config.noimage'))}}" style="height: 50px;width:50px"> @endif</td>
                                                                            <td width="60%">
                                                                                <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; ">
                                                                                    {{$ticketTransacton->match->awayTeam->name}}
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tr>
                                                                <td width="100%" align="center">
                                                                    <div style="display:block; font-size:0pt; line-height:0pt; height:20px;"></div>

                                                                    <div class="secondary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 16px; font-weight: 600;">
                                                                        {{ Carbon\Carbon::parse($ticketTransacton->match->kickoff_time)->format('l dS F Y') }}
                                                                    </div>

                                                                    <div style="display:block; font-size:0pt; line-height:0pt; height:10px;"></div>

                                                                    <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 16px;  font-weight: 600;">
                                                                        {{$ticketTransacton->match->homeTeam->stadium->name }}  {{ Carbon\Carbon::parse($ticketTransacton->match->kickoff_time)->format('g:i a')}}
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>

                                                    <td width="30" class="column" align="right" valign="bottom">
                                                        <div style="display:block; font-size:0pt; line-height:0pt; height:15px;"></div>
                                                        <div class="text-center">
                                                            @if(json_decode($ticketTransacton->match->competition)->logo)
                                                            <img src="{{json_decode($ticketTransacton->match->competition)->logo}}" border="0" alt="" width="100%" style="width:100%; max-width: 23px;">
                                                            @else
                                                            {{json_decode($ticketTransacton->match->competition)->name}}
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>

                                            <!-- spacer -->
                                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td>
                                                        <div style="display:block; font-size:0pt; line-height:0pt; height:30px;"></div>
                                                    </td>    
                                                </tr>
                                            </table>
                                            <!-- end spacer -->
                                        </td>

                                        <td style="font-size:0pt; line-height:0pt; text-align:left" width="30"></td> 
                                    </tr>
                                </table>
                                <!-- end Section --> 

                                <!-- Section -->
                                {{-- <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-top: 1px solid #F1F1F1;">
                                    <tr>
                                        <td style="font-size:0pt; line-height:0pt; text-align:left" width="30"></td> 

                                        <td width="620" align="center">
                                            <div style="display:block; font-size:0pt; line-height:0pt; height:30px;"></div>

                                            <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 18px;  font-weight: 600;">
                                                Please present this code on collection
                                            </div>

                                            <div style="display:block; font-size:0pt; line-height:0pt; height:20px;"></div>

                                            <div style="font-size:0pt; line-height:0pt;">
                                                <img src="{{ \Storage::disk('s3')->url('')}}{{config('fanslive.IMAGEPATH.booked_ticket_qrcode').$ticketTransacton->id}}.png" border="0" alt="qrCode" width="100%" style="width:100%; max-width: 220px;">
                                            </div>

                                            <div style="display:block; font-size:0pt; line-height:0pt; height:30px;"></div>
                                        </td>

                                        <td style="font-size:0pt; line-height:0pt; text-align:left" width="30"></td>    
                                    </tr>
                                </table> --}}
                                <!-- end Section --> 

                                <!-- Section -->
                                <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-top: 1px solid #F1F1F1; border-bottom: 1px solid #F1F1F1;">
                                    <tr>
                                        <td style="font-size:0pt; line-height:0pt; text-align:left" width="30"></td> 

                                        <td width="620" align="center">
                                            <!-- spacer -->
                                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td>
                                                        <div style="display:block; font-size:0pt; line-height:0pt; height:30px;"></div>
                                                    </td>    
                                                </tr>
                                            </table>
                                            <!-- end spacer -->

                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td style="font-size:0pt; line-height:0pt; text-align:left" width="40"></td> 

                                                    <td width="430">
                                                        <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; font-weight: 600;">
                                                            Receipt {{$ticketTransacton->receipt_number}}
                                                        </div>
                                                    </td>

                                                    <td style="font-size:0pt; line-height:0pt; text-align:left" width="150"></td> 
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div style="display:block; font-size:0pt; line-height:0pt; height:15px;"></div>
                                                    </td>    
                                                </tr>
                                                @foreach($ticketTransacton->bookedTickets as $key=>$val)
                                                <tr>
                                                    <td width="40" valign="top">
                                                        <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; font-weight: 600;">
                                                           {{$loop->iteration}}
                                                        </div>
                                                    </td> 

                                                    <td width="430">
                                                        <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; ">
                                                            {{$val->pricingBand->display_name}} ticket  {{ $val->stadium_block_seat_id ? $val->stadiumBlockSeat->stadiumBlock->name : null}} - {{$val->stadium_block_seat_id ? $val->stadiumBlockSeat->row : null}}{{$val->stadium_block_seat_id ? $val->stadiumBlockSeat->seat : $val->seat}}
                                                        </div>
                                                    </td>

                                                    <td width="150" align="right" valign="top">
                                                        <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; ">
                                                             @if($ticketTransacton->currency === 'GBP')   &#163; @else € @endif{{ formatNumber($val->price)}}
                                                        </div>
                                                    </td> 
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div style="display:block; font-size:0pt; line-height:0pt; height:15px;"></div>
                                                    </td>    
                                                </tr>
                                                @endforeach
                                                <tr>
                                                    <td style="font-size:0pt; line-height:0pt; text-align:left" width="40"></td> 

                                                    <td width="430">
                                                        <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; font-weight: 600;">
                                                            Total:
                                                        </div>
                                                    </td>

                                                    <td width="150" align="right">
                                                        <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 18px;  font-weight: 600;">
                                                             @if($ticketTransacton->currency === 'GBP')   &#163; @else € @endif {{formatNumber($ticketTransacton->price)}}
                                                        </div>
                                                    </td> 
                                                </tr>                                                
                                            </table>

                                            <!-- spacer -->
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td>
                                                        <div style="display:block; font-size:0pt; line-height:0pt; height:15px;"></div>
                                                    </td>    
                                                </tr>
                                            </table>
                                            <!-- end spacer -->

                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td width="100%" align="right">
                                                        @if($ticketTransacton->card_details)
                                                        <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; ">
                                                            Paid with <span style="display: inline-block; vertical-align: middle;"></span> <span>{{json_decode($ticketTransacton->card_details)->maskedAccount}}</span>
                                                        </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>

                                            <!-- spacer -->
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td>
                                                        <div style="display:block; font-size:0pt; line-height:0pt; height:20px;"></div>
                                                    </td>    
                                                </tr>
                                            </table>
                                            <!-- end spacer -->
                                        </td>

                                        <td style="font-size:0pt; line-height:0pt; text-align:left" width="30"></td> 
                                    </tr>
                                </table>
                                <!-- end Section --> 

                                <!-- Section -->
                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="font-size:0pt; line-height:0pt; text-align:left" width="30"></td> 

                                        <td width="620" align="center">
                                            <div style="display:block; font-size:0pt; line-height:0pt; height:30px;"></div>

                                            <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 18px;  font-weight: 600;">
                                                You earned @if(isset($ticketTransacton->loyaltyRewardPoints)) {{$ticketTransacton->loyaltyRewardPoints->points}} @else 0 @endif loyalty points 
                                               
                                            </div>

                                            <div style="display:block; font-size:0pt; line-height:0pt; height:30px;"></div>
                                        </td>

                                        <td style="font-size:0pt; line-height:0pt; text-align:left" width="30"></td>    
                                    </tr>
                                </table>
                                <!-- end Section --> 

                                <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#eceef1">
                                    <tr>
                                        <td align="center">
                                            <div style="display:block; font-size:0pt; line-height:0pt; height:20px;"></div>
                                            <font face="Arial, Helvetica, sans-serif" size="3" color="#96a5b5" style="font-size: 13px;">
                                                <span style="font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #96a5b5;">
                                                    &copy; developed by 
                                                    <a href="http://aecordigital.com" target="_blank">
                                                        <font color="#2196f3">aecor</font>
                                                    </a>
                                                </span>
                                            </font>
                                            <div style="display:block; font-size:0pt; line-height:0pt; height:20px;"></div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>