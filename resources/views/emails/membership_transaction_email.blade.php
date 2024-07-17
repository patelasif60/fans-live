<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta content="telephone=no" name="format-detection" />
        <title>Membership</title>
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
                                <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
                                    <tr>
                                        <td style="font-size:0pt; line-height:0pt; text-align:left" width="30"></td> 

                                        <td width="620" align="center">

                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td width="100%" align="center">
                                                        <div style="display:block; font-size:0pt; line-height:0pt; height:40px;"></div>

                                                        <div style="font-size:0pt; line-height:0pt;">
                                                            <img src="{{asset(config('mail.mail_config.star'))}}" border="0" alt="Star" width="100%" style="width:100%; max-width: 100px;">
                                                        </div>

                                                        <div style="display:block; font-size:0pt; line-height:0pt; height:30px;"></div>

                                                        <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; color: #57697e;font-weight: 600;">
                                                            Congratulations 
                                                        </div>

                                                        <div style="display:block; font-size:0pt; line-height:0pt; height:10px;"></div>

                                                        <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; color: #57697e; font-weight: 600;">
                                                            You are now a {{$consumerMembershipPackage->membershipPackage->title}} member
                                                        </div>

                                                        <div style="display:block; font-size:0pt; line-height:0pt; height:10px;"></div>

                                                        <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #979797;">
                                                            {{$consumerMembershipPackage->duration}} month membership
                                                        </div>

                                                        <div style="display:block; font-size:0pt; line-height:0pt; height:30px;"></div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>

                                        <td style="font-size:0pt; line-height:0pt; text-align:left" width="30"></td> 
                                    </tr>
                                </table>
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
                                                        <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; color: #57697e;font-weight: 600;">
                                                            Receipt {{$consumerMembershipPackage->receipt_number}}
                                                        </div>
                                                    </td>

                                                    <td style="font-size:0pt; line-height:0pt; text-align:left" width="150"></td> 
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div style="display:block; font-size:0pt; line-height:0pt; height:15px;"></div>
                                                    </td>    
                                                </tr>

                                                <tr>
                                                    <td width="40" valign="top">
                                                        <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; color: #57697e;font-weight: 600;">
                                                            1
                                                        </div>
                                                    </td> 

                                                    <td width="430">
                                                        <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; color: #57697e;">
                                                            {{$consumerMembershipPackage->membershipPackage->title}} membership
                                                        </div>
                                                    </td>

                                                    <td width="150" align="right" valign="top">
                                                        <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; color: #57697e;">
                                                            @if($consumerMembershipPackage->currency === 'GBP')   &#163; @else € @endif{{ $consumerMembershipPackage->final_price}}
                                                        </div>
                                                    </td> 
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div style="display:block; font-size:0pt; line-height:0pt; height:15px;"></div>
                                                    </td>    
                                                </tr>

                                                <tr>
                                                    <td style="font-size:0pt; line-height:0pt; text-align:left" width="40"></td> 

                                                    <td width="430">
                                                        <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; color: #57697e;font-weight: 600;">
                                                            Total:
                                                        </div>
                                                    </td>

                                                    <td width="150" align="right">
                                                        <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; color: #57697e; font-weight: 600;">
                                                            @if($consumerMembershipPackage->currency === 'GBP')   &#163; @else € @endif{{ $consumerMembershipPackage->final_price}}
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
                                                        @if($consumerMembershipPackage->card_details)
                                                        <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #57697e;">
                                                            Paid with <span style="display: inline-block; vertical-align: middle;"></span> <span>{{json_decode($consumerMembershipPackage->card_details)->maskedAccount}}</span>
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

                                            <div class="primary-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; color: #57697e; font-weight: 600;">
                                                You earned  @if(isset($consumerMembershipPackage->loyaltyRewardPoints)) {{$consumerMembershipPackage->loyaltyRewardPoints->points}} @else 0 @endif loyalty points
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