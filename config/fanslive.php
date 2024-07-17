<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Fanslive configuration parameters
	|--------------------------------------------------------------------------
	|
	| This file contains all the configuration variables that can be set
	| for this application.
	|
	*/
	'RESET_PASSWORD_INTERVAL' => env('RESET_PASSWORD_INTERVAL', 60),
	'PUBLISH_STATUS'          => [
		'published' => 'Published',
		'hidden'    => 'Hidden',
	],
	'USER_STATUS' => [
		'active'    => 'Active',
		'suspended' => 'Suspended',
	],
	'TRANSACTION_STATUS' => [
		'success'   => 'successful',
		'pending'   => 'pending',
		'rejected'  => 'failed',
		'unresolved' => 'unresolved',
	],
	'PAYMENT_STATUS' => [
		'unpaid'    => 'Unpaid',
		'paid'      => 'Paid',
	],
	'QUIZ_TYPE' => [
		'multiple_choice'    => 'Multiple choice',
		'fill_in_the_blanks' => 'Fill in the blanks',
	],
	'IMAGEPATH' => [
		'club_category_logo'                          => '/club_category/',
		'competition_logo'                            => '/competition/',
		'club_logo'                                   => '/club/',
		'news_logo'                                   => '/news/',
		'cta_image'                                   => '/ctas/',
		'event_logo'                                  => '/event/',
		'category_logo'                               => '/category/',
		'product_logo'                                => '/product/',
		'travel_information_photo'                    => '/travel_information/',
		'travel_information_icon'                     => '/travel_information/icon/',
		'travel_offers_thumbnail'                     => '/travel_offers/thumbnail/',
		'travel_offers_banner'                        => '/travel_offers/banner/',
		'travel_offers_icon'                          => '/travel_offers/icon/',
		'stadium_general_setting_aerial_view_graphic' => '/stadium_general_setting/',
		'stadium_image' 							  => '/stadium_general_setting/image/',
		'hospitality_suite_image'                     => '/hospitality_suite/image/',
		'hospitality_suite_seating_plan'              => '/hospitality_suite/seating_plan/',
		'pricing_band_seats'                          => '/pricing_band/',
		'stadium_block_seating_plan'                  => '/stadium_block/',
		'match_hospitality_unavailable_seats'         => '/match/hospitality_unavailable_seats/',
		'match_event_video'                           => '/match_event_video/',
		'match_ticketing_unavailable_seats'           => '/match/ticketing_unavailable_seats/',
		'match_ticketing_sponsor_logo'                => '/match/ticketing_sponsor_logo/',
		'membership_package_icon'                     => '/membership_package_icon/',
		'club_information_icon'                       => '/club_information/icon/',
		'loyalty_reward_image'                        => '/loyalty_reward/icon/',
		'special_offer_image'                         => '/special_offer_image/',
		'video_thumbnail'                             => '/video_thumbnail/',
		'video'                                       => '/video/',
		'quiz_image'                                  => '/quiz/',
		'booked_ticket_qrcode'                        => 'qrcode/booked_ticket/',
		'booked_event_qrcode'                         => 'qrcode/booked_event/',
		'booked_hospitality_suite_qrcode'             => 'qrcode/booked_hospitality_suite/',
		'product_transaction_qrcode'                  => 'qrcode/product_transaction/',
		'loyalty_reward_transaction_qrcode'           => 'qrcode/loyalty_reward_transaction/',
		'consumer_membership_package_qrcode'          => 'qrcode/consumer_membership_package/',
	],
	'VIDEOPATH' => [
		'match_event_video' => '/match/match_event_video/',
	],
	'FOOTBALLORG' => [
		'APIURL'   => env('FOOTBALL_ORG_API_URL', ''),
		'APITOKEN' => env('FOOTBALL_ORG_API_TOKEN', ''),
	],
	'FEED_TYPES' => [
		'twitter'   => 'Twitter',
		'facebook'  => 'Facebook',
		'youtube'   => 'Youtube',
		'instagram' => 'Instagram',
		'rss'       => 'RSS',
	],
	'DATE_TIME_CMS_FORMAT' => [
		'php' => 'H:i:s d-m-Y',
		'js' => 'HH:mm:ss DD-MM-YYYY',
	],
	'DATE_CMS_FORMAT' => [
		'php' => 'd-m-Y',
		'js' => 'DD-MM-YYYY',
	],
	'DATE_APP_REQUEST_FORMAT' => 'd/m/Y',
	'DATE_TIME_APP_REQUEST_FORMAT' => 'd/m/Y H:i:s',
	'CTA_BUTTON_ACTIONS' => [
		'merchandise_category'      => 'Merchandise category',
		'food_and_drink_category'   => 'Food and drink category',
		'travel_offer_screen'       => 'Travel offer screen',
		'merchandise_screen'        => 'Merchandise screen',
		'food_and_drink_screen'     => 'Food and drink screen',
		'travel_information_screen' => 'Travel information screen',
		'ticket_details'            => 'Ticket details',
		// 'betting_screen'            => 'Betting screen',
		// 'lottery_screen'            => 'Lottery screen',
	],
	'CTA_BUTTON_ITEMS' => [
		'item1' => 'Item 1',
		'item2' => 'Item 2',
		'item3' => 'Item 3',
	],
	'CLUB_PERMISSIONS' => [
		'access.clubadmin.dashboard.own' => 'Dashboard',
		'manage.app.settings.own'        => 'App settings',
		'manage.stadium.own'             => 'Stadium',
		'manage.feeds.own'               => 'Feeds',
		'manage.news.own'                => 'News',
		'manage.ctas.own'                => 'CTAs',
		'manage.polls.own'               => 'Polls',
		'manage.pushnotifications.own'   => 'Push notifications',
		'manage.travelinformation.own'   => 'Travel information',
		'manage.membershippackages.own'  => 'Membership packages',
		'manage.events.own'              => 'Events',
		'manage.matches.own'             => 'Matches',
		'manage.commerce.own'            => 'Commerce',
		'manage.clubinformation.own'     => 'My club',
		'manage.quizzes.own'             => 'Quizzes',
		'manage.videos.own'              => 'Videos',
		'generate.reports.own'           => 'Reports',
		'access.clubadmin.user.own'      => 'Users',
		'access.clubadmin.transactions.own' => 'Transactions',

	],
	'INITIAL_ROLES' => ['superadmin', 'staff', 'consumer'],
	'MATCH_TEAM'    => [
		'home' => 'Home',
		'away' => 'Away',
	],
	'MATCH_EVENT_TYPE' => [
		'goal'            => 'Goal',
		'red_card'        => 'Red card',
		'yellow_card'     => 'Yellow card',
		'yellow_red_card' => 'Yellow red card',
		'substitution'    => 'Substitution',
		'Half_time'       => 'Half time',
		'full_time'       => 'Full time',
	],
	'MARKERS_IMAGE' => [
		'green' => '/img/backend/green.png',
		'red'   => '/img/backend/red.png',
		'yellow'=> '/img/backend/yellow.png',
	],
	'GOOGLE_AUTH_KEY' => [
		'key'=> env('GOOGLE_API_KEY'),
	],
	'TICKET_RESALE_FEE_TYPE' => [
		'fixed_fee'                => 'Fixed fee',
		'percentage_of_face_value' => 'Percentage of face value',
	],
	'APP_VERSION' => [
		'consumer' => [
			'android' => env('CONSUMER_ANDROID_APP_VERSION'),
			'ios'     => env('CONSUMER_IOS_APP_VERSION')
		],
		'staff' => [
			'android' => env('STAFF_ANDROID_APP_VERSION'),
			'ios'     => env('STAFF_IOS_APP_VERSION')
		]
	],
	'TESTFAIRY' => [
		'consumer' => [
			'android' => [
				'enable_testfairy' => env('ENABLE_TESTFAIRY_CONSUMER_ANDROID'),
				'enable_testfairy_video' => env('ENABLE_TESTFAIRY_VIDEO_CAPTURE_CONSUMER_ANDROID'),
				'enable_testfairy_feedback' => env('ENABLE_TESTFAIRY_FEEDBACK_CONSUMER_ANDROID')
			],
			'ios' => [
				'enable_testfairy' => env('ENABLE_TESTFAIRY_CONSUMER_IOS'),
				'enable_testfairy_video' => env('ENABLE_TESTFAIRY_VIDEO_CAPTURE_CONSUMER_IOS'),
				'enable_testfairy_feedback' => env('ENABLE_TESTFAIRY_FEEDBACK_CONSUMER_IOS')
			]
		],
		'staff' => [
			'android' => [
				'enable_testfairy' => env('ENABLE_TESTFAIRY_STAFF_ANDROID'),
				'enable_testfairy_video' => env('ENABLE_TESTFAIRY_VIDEO_CAPTURE_STAFF_ANDROID'),
				'enable_testfairy_feedback' => env('ENABLE_TESTFAIRY_FEEDBACK_STAFF_ANDROID')
			],
			'ios' => [
				'enable_testfairy' => env('ENABLE_TESTFAIRY_STAFF_IOS'),
				'enable_testfairy_video' => env('ENABLE_TESTFAIRY_VIDEO_CAPTURE_STAFF_IOS'),
				'enable_testfairy_feedback' => env('ENABLE_TESTFAIRY_FEEDBACK_STAFF_IOS')
			]
		]
	],
	'FEED_URL' => [
		'twitter'=> env('FEED_URL_TWITTER'),
		'youtube'=> env('FEED_URL_YOUTUBE'),
		'instagram' => env('FEED_URL_INSTAGRAM'),
	],
	'APP_URL'                        => env('APP_URL'),

	'CURRENCY_TYPE'                  => [
		'EUR' => html_entity_decode("EUR &#8364;"),
		'GBP' => html_entity_decode("GBP &#163;"),
	],
	'CURRENCY_SYMBOL'                => [
		'EUR' => html_entity_decode("&#8364;"),
		'GBP' => html_entity_decode("&#163;"),
	],

	'SUPPORTED_CURRENCIES'           => ['EUR'],
	'ALL_FANS_MEMBERSHIP_PACKAGE_ID' => env('ALL_FANS_MEMBERSHIP_PACKAGE_ID', null),
	'CATEGORY_TYPE'                  => [
		'food_and_drink' => 'Food and drink',
		'merchandise'    => 'Merchandise',
	],
	'TEAM_REQUEST_TO_EMAIL' => env('team_request_to_email'),
	'LEAGUE_REQUEST_TO_EMAIL' => env('league_request_to_email'),
	'WARNING_COLORS' => [
		'red' => 'Red',
		'green' => 'Green',
		'amber' => 'Amber',
	],
	'SWIPE_ACTION_CATEGORIES' => [
		'merchandise_category' => 'Merchandise category',
		'food_and_drink_category' => 'Food and drink category',
		'travel_offer' => 'Travel Offer',
		'merchandise_screen' => 'Merchandise screen',
		'food_and_drink_screen' => 'Food and drink screen',
		'travel_information_screen' => 'Travel information screen',
		'ticket_details' => 'Ticket details',
		// 'betting_screen' => 'Betting screen',
		// 'lottery_screen' => 'Lottery screen'
	],
	'TRANSACTION_TYPE_TEXT' => [
		'event'   => 'Event purchase',
		'food_and_drink'   => 'Food & Drink purchase',
		'merchandise'   => 'Merchandise purchase',
		'membership'   => 'Membership purchase',
		'hospitality'   => 'Hospitality purchase',
		'ticket'   => 'Ticket Purchase',
		'loyalty_reward'   => 'Points redeemed',
	],
    'PUBLISH_STATUS_SPECIAL_OFFER'          => [
        'published' => 'Published',
        'hidden'    => 'Hidden',
        'archived'  => 'Archived',
    ],
      'CLUB_PERMISSIONS_URL' => [
        'manage.app.settings.own'        => 'backend.clubappsetting.edit',
        'manage.stadium.own'             => 'backend.stadiumgeneralsettings.edit',
        'manage.feeds.own'               => 'backend.contentfeed.index',
        'manage.news.own'                => 'backend.news.index',
        'manage.ctas.own'                => 'backend.cta.index',
        'manage.polls.own'               => 'backend.poll.index',
        'manage.pushnotifications.own'   => 'backend.pushnotification.index',
        'manage.travelinformation.own'   => 'backend.traveloffers.index',
        'manage.membershippackages.own'  => 'backend.membershippackages.index',
        'manage.events.own'              => 'backend.event.index',
        'manage.matches.own'             => 'backend.matches.index',
        'manage.commerce.own'            => 'backend.collectionpoint.index',
        'manage.clubinformation.own'     => 'backend.clubinformationpages.index',
        'manage.quizzes.own'             => 'backend.quizzes.index',
        'manage.videos.own'              => 'backend.video.index',
        'access.clubadmin.user.own'      => 'backend.cms.club.index',
        'access.clubadmin.transactions.own' => 'backend.transaction.index',

    ],
    'IS_RESTRICTED_TO_OVER_AGE' => env('IS_RESTRICTED_TO_OVER_AGE', NULL),
    'MERCHANT_ID' =>env('MERCHANT_ID'),
    'STADIUM_BLOCK_TYPE' => ['Seat','Stairwell','Disabled'],
    'S3_URL' => env('S3_URL'),
    'TRANSACTION_EMAILS'=> env('TRANSACTION_EMAILS'),
];
