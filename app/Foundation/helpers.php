<?php

use App\Models\Club;
use App\Models\Consumer;
use App\Models\PollAnswer;
use App\Models\User;
use Carbon\Carbon;

if (!function_exists('getClubIdBySlug')) {
    function getClubIdBySlug($slug)
    {
        $clubId = Club::where('slug', $slug)->first()->id;

        return $clubId;
    }
}

if (!function_exists('uploadImageToS3')) {
    function uploadImageToS3($image, $imagePath)
    {
        $imageFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $imageFileExtension = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
        $imageFileName = formatFileName($imageFileName);
        $imageFile = $imageFileName.'_'.now()->timestamp.'.'.$imageFileExtension;
        $s3path = $imagePath.$imageFile;
        $disk = Storage::disk('s3');
        $disk->put($s3path, file_get_contents($image), 'public');
        $imageUrl = $disk->url($s3path);
        $s3Image = [
            'url' 		    => $imageUrl,
            'file_name'	=> $imageFile,
        ];

        return $s3Image;
    }
}

if (!function_exists('uploadQRCodeToS3')) {
    function uploadQRCodeToS3($image, $imagePath,$id)
    {
        $imageFile = $id.'.png';
        $s3path = $imagePath.$imageFile;
        if (preg_match('/^data:image\/(\w+);base64,/', $image)) {
            $data = substr($image, strpos($image, ',') + 1);
            $data = base64_decode($data);
        }
        $disk = Storage::disk('s3');
        $disk->put($s3path, $data, 'public');
        $imageUrl = $disk->url($s3path);
        $s3Image = [
            'url' 		    => $imageUrl,
            'file_name'	=> $imageFile,
        ];
        return $s3Image;
    }
}

if (!function_exists('checkExistingEmail')) {
    function checkExistingEmail($email)
    {
        if ($email !== null && !empty($email)) {
            $userQuery = User::where('email', $email);
            $user = $userQuery->first();
            if ($user) {
                return 'false';
            }
        }

        return 'true';
    }
}

if (!function_exists('isUserResponded')) {
    function isUserResponded($pollId)
    {
        $user = JWTAuth::user();
        $consumerId = Consumer::where('user_id', $user->id)->first()->id;
        $pollAnswer = PollAnswer::where('poll_id', $pollId)->where('consumer_id', $consumerId)->get();
        if (count($pollAnswer) > 0) {
            return true;
        }
        return false;
    }
}

if (!function_exists('uploadImageFromUrlToS3')) {
    function uploadImageFromUrlToS3($path, $imagePath)
    {
        $imageFileExtension = pathinfo($path, PATHINFO_EXTENSION);
        $imageFileName = basename($path, '.'.$imageFileExtension);
        $imageFile = $imageFileName.'_'.now()->timestamp.'.'.$imageFileExtension;
        $s3path = $imagePath.$imageFile;
        $disk = Storage::disk('s3');
        $disk->put($s3path, file_get_contents($path), 'public');
        $imageUrl = $disk->url($s3path);
        $s3Image = [
            'url'       => $imageUrl,
            'file_name' => $imageFile,
        ];

        return $s3Image;
    }
}

if (!function_exists('getDateDiff')) {
    function getDateDiff($date, $timezone = 'UTC', $isFutureDate = 0)
    {
        $timeAgo       = Carbon::parse($date, $timezone)->timestamp;
        $curTime       = Carbon::now($timezone)->timestamp;

        if($isFutureDate == 1 || $timeAgo > $curTime) {
            $timeElapsed = $timeAgo - $curTime;
            $timeString = "to go";
        } else {
            $timeElapsed = $curTime - $timeAgo;
            $timeString = "ago";
        }

        $seconds        = $timeElapsed ;
        $minutes        = round($timeElapsed / 60 );
        $hours          = round($timeElapsed / 3600);
        $days           = round($timeElapsed / 86400 );
        $weeks          = round($timeElapsed / 604800);
        $months         = round($timeElapsed / 2600640 );
        $years          = round($timeElapsed / 31207680 );
        // Seconds
        if($seconds <= 60) {
            return "just now";
        }
        //Minutes
        else if($minutes <= 60) {
            if($minutes == 1) {
                return "one minute ".$timeString;
            }
            else{
                return "$minutes minutes ".$timeString;
            }
        }
        //Hours
        else if($hours <=24) {
            if($hours == 1) {
                return "an hour ".$timeString;
            } else {
                return "$hours hrs ".$timeString;
            }
        }
        //Days
        else if($days <= 7) {
            if($days == 1) {
                if($isFutureDate == 1) {
                    return "tomorrow";
                } else {
                    return "yesterday";
                }
            } else {
                return "$days days ".$timeString;
            }
        }
        //Weeks
        else if($weeks <= 4.3) {
            if($weeks == 1) {
                return "a week ".$timeString;
            } else {
                return "$weeks weeks ".$timeString;
            }
        }
        //Months
        else if($months <=12) {
            if($months == 1) {
                return "a month ".$timeString;
            } else {
                return "$months months ".$timeString;
            }
        }
        //Years
        else {
            if($years == 1) {
                return "one year ".$timeString;
            } else {
                return "$years years ".$timeString;
            }
        }
    }
}

if (!function_exists('convertDateTimezone')) {
    function convertDateTimezone($dateTime, $fromTimezone = NULL, $toTimezone = NULL, $toFormat = NULL, $fromFormat = NULL)
    {
        if (!empty($dateTime)) {
            if ($fromFormat == NULL) {
                $fromFormat = 'Y-m-d H:i:s';
            }
            if ($toFormat == NULL) {
                $toFormat = 'Y-m-d H:i:s';
            }
            if ($fromTimezone == NULL) {
                $fromTimezone = 'UTC';
            }
            if ($toTimezone == NULL) {
                $toTimezone = 'UTC';
            }
            $date = Carbon::createFromFormat($fromFormat, $dateTime, $fromTimezone);
            $date->setTimezone($toTimezone);
            return $date->format($toFormat);
        } else {
            return NULL;
        }
    }
}

if (!function_exists('convertDateFormat')) {
    function convertDateFormat($date, $fromFormat = NULL, $toFormat = NULL)
    {
        if ($fromFormat == NULL) {
            $fromFormat = 'Y-m-d';
        }
        if ($toFormat == NULL) {
            $toFormat = 'Y-m-d';
        }
        $date = Carbon::createFromFormat($fromFormat, $date);
        return $date->format($toFormat);
    }
}

if (!function_exists('getLoggedinConsumer')) {
    function getLoggedinConsumer()
    {
        $user = \JWTAuth::user();
        return Consumer::where('user_id', $user->id)->first();
    }
}

if (!function_exists('getBrightness')) {
	function getBrightness($hex) {
		$hex = str_replace('#', '', $hex);
		$r = hexdec(substr($hex, 0, 2));
		$g = hexdec(substr($hex, 2, 2));
		$b = hexdec(substr($hex, 4, 2));

		$brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

		if ($brightness > 130) {
			return 'dark';
		} else {
			return 'light';
		}
	}
}

if (!function_exists('colorLuminance')) {
	function colorLuminance($hex, $percent) {
		// validate hex string
		$hex = preg_replace( '/[^0-9a-f]/i', '', $hex );
		$newHex = '#';

		if ( strlen( $hex ) < 6 ) {
			$hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
		}

		// convert to decimal and change luminosity
		for ($i = 0; $i < 3; $i++) {
			$dec = hexdec( substr( $hex, $i*2, 2 ) );
			$dec = min( max( 0, $dec + $dec * $percent ), 255 );
			$newHex .= str_pad( dechex( $dec ) , 2, 0, STR_PAD_LEFT );
		}

		return $newHex;
	}
}

if (!function_exists('formatNumber')) {
	function formatNumber($number) {
		return number_format($number, 2, '.', '');
	}
}
if (!function_exists('formatFileName')) {
    function formatFileName($fileName) {
        return preg_replace("![^a-z0-9]+!i", "_",  $fileName);
    }
}

