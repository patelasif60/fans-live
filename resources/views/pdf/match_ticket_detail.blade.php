<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
        <title>Match Tickets</title>

        <style>
        	html{
        		height: 0;
        	}
            body {
                font-family: 'Open Sans', sans-serif;
                font-weight: 400;
                color: #fff;
            }
            
            td,
            th {
                border: 0;
            }

            p {
                margin: 0;
                font-weight: bold;
                line-height: 28px;
            }
            .page {
		        overflow: hidden;
		        page-break-after: always;
		    }
        </style>
    </head>
    <body>
    	@foreach($bookedTickets->bookedTickets as $ticket)
    		@if(!in_array($ticket->id,$resaleTicketId))
    		<div class="page">
		        <div style="background-color: {{$clubDetail->primary_colour}}; padding: 30px; border-radius: 20px; box-shadow: 0 2px 15px 0 rgba(0,0,0,0.35); width: 370px; margin: 0 auto;">
		            <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
		                <tr>
		                    <td width="45%">
		                        <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
		                            <tr>
		                                <td width="60%">{{ $bookedTickets->match->homeTeam->name }}</td>
		                                <td width="40%" align="center">@if($bookedTickets->match->homeTeam->logo)<img src="{{ $bookedTickets->match->homeTeam->logo }}" style="height: 50px;">@else <img src="{{asset(config('mail.mail_config.noimage'))}}" style="height: 50px;width:50px">@endif </td>
		                            </tr>
		                        </table>
		                    </td>
		                    <td width="10%" align="center">V</td>
		                    <td width="45%">
		                        <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
		                            <tr>
		                                <td width="40%" align="center">@if($bookedTickets->match->awayTeam->logo) <img src="{{ $bookedTickets->match->awayTeam->logo }}" style="height: 50px;">@else <img src="{{asset(config('mail.mail_config.noimage'))}}" style="height: 50px;width:50px">@endif </td>
		                                <td width="60%">{{ $bookedTickets->match->awayTeam->name }}</td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		            </table>

		            <div style="height: 50px;">&nbsp;</div>

		            <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
		                <tr>
		                    <td width="100%" align="center">
		                        <p>{{ $bookedTickets->match->competition->name }}</p>
		                        <p style="color: {{$clubDetail->secondary_colour}}">{{ convertDateTimezone($bookedTickets->match->kickoff_time, null, $clubDetail->time_zone, 'l jS \\of F Y') }} ({{ $clubDetail->time_zone }})</p>
		                        <p>Kick off {{ convertDateTimezone($bookedTickets->match->kickoff_time, null, $clubDetail->time_zone, 'h:i A') }}</p>
		                    </td>
		                </tr>
		            </table>

		            <div style="height: 30px;">&nbsp;</div>

		            <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
		                <tr align="center">
		                    <td width="33.33%" align="center"><p>Block {{ $ticket->stadiumBlockSeat->stadiumBlock->name }}</p></td>
		                    {{-- <td width="50%">
		                        <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
		                            <tr> --}}
		                                <td width="33.33%" align="center"><p>Row: {{ $ticket->stadiumBlockSeat->row }}</p></td>
		                                <td width="33.33%" align="center"><p>Seat: {{ $ticket->stadiumBlockSeat->seat }}</p></td>
		                           {{--  </tr>
		                        </table>
		                    </td> --}}
		                </tr>
		            </table>

		            <div style="height: 10px;">&nbsp;</div>

		            <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
		                <tr>
		                    <td width="100%" align="center">
		                        <p>Enter via: {{ $ticket->stadiumBlockSeat->stadiumBlock->stadiumEntrances->count() > 0 ? implode(', ', $ticket->stadiumBlockSeat->stadiumBlock->stadiumEntrances->pluck('name')->toArray()) : '-' }}</p>
		                    </td>
		                </tr>
		            </table>

		            <div style="height: 50px;">&nbsp;</div>

		            <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
		                <tr>
		                    <td width="100%" align="center"><p style="font-weight: 400;"><small>{{$bookedTickets->receipt_number}}</small></p></td>
		                </tr>
		                <tr>
		                    <td width="100%" align="center">
		                        <div style="height: 10px;">&nbsp;</div>
		                    </td>
		                </tr>
		                <tr>
		                    <td width="100%" align="center">
		                        <div style="background-color: #fff; padding: 20px; height: 160px; width: 160px;">
		                            {<img src="{{ \Storage::disk('s3')->url('')}}{{config('fanslive.IMAGEPATH.booked_ticket_qrcode').$ticket->id}}.png" style="height: 100%; width: 100%; object-fit: contain; object-position: center;">
		                        </div>
		                    </td>
		                </tr>
		            </table>
		        </div>
	        </div>
	        @endif
        @endforeach
    </body>
</html>