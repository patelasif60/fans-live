<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
        <title>Event Tickets</title>

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
    	@foreach($bookedEventTickets->bookedEvents as $ticket)
    		<div class="page">
		        <div style="background-color: {{$clubDetail->primary_colour}}; padding: 30px; border-radius: 20px; box-shadow: 0 2px 15px 0 rgba(0,0,0,0.35); width: 370px; margin: 0 auto;">
		            <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
		                <tr>
		                    <td width="100%" align="center">{{ $bookedEventTickets->event->title }}</td>
		                </tr>
                        <tr>
                            <td width="100%" align="center">
                                <p style="color: {{$clubDetail->secondary_colour}}">{{ convertDateTimezone($bookedEventTickets->event->date_time, null, $clubDetail->time_zone, 'l jS F Y') }} ({{ $clubDetail->time_zone }})</p>
                                <p>Kick off {{ convertDateTimezone($bookedEventTickets->event->date_time, null, $clubDetail->time_zone, 'h:i A') }}</p>
                            </td>
                        </tr>
                        <tr width="100%" align="center">
                            <td>{{ $bookedEventTickets->event->location }}</td>
                        </tr>
		            </table>

                    <div style="height: 50px;">&nbsp;</div>
                    <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
                        <tr>
                            <td width="100%" align="center">Seat : {{ $ticket->seat }}</td>
                        </tr>
                    </table>

                    <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
                        <tr>
                            <td width="100%" align="center"><p style="font-weight: 400;"><small>{{ $bookedEventTickets->event->receipt_number }}</small></p></td>
                        </tr>
                        <tr>
                            <td width="100%" align="center">
                                <div style="height: 10px;">&nbsp;</div>
                            </td>
                        </tr>
                        <tr>
                            <td width="100%" align="center">
                                <div style="background-color: #fff; padding: 20px; height: 160px; width: 160px;">
                                    <img src="{{ \Storage::disk('s3')->url('')}}{{config('fanslive.IMAGEPATH.booked_event_qrcode').$ticket->id}}.png" style="height: 100%; width: 100%; object-fit: contain; object-position: center;">
                                </div>
                            </td>
                        </tr>
                    </table>
		        </div>
	        </div>
        @endforeach
    </body>
</html>