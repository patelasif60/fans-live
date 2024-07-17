<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['throttle:60,1', 'bindings'])->group(function () {
	Route::post('password/email', '\App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail');
	Route::post('password/reset', '\App\Http\Controllers\Auth\ResetPasswordController@reset');

	Route::post('consumer/login', '\App\Http\Controllers\Api\AuthController@authenticateConsumer');
	Route::post('consumer/register', '\App\Http\Controllers\Api\AuthController@registerConsumer');

	// Social logins
	Route::post('social/login/token', '\App\Http\Controllers\Api\AuthController@socialLogin');

	// Card management
	Route::post('get_checkout_id_for_add_card', '\App\Http\Controllers\Api\ConsumerCardController@getCheckoutIdForAddCard');
	Route::get('registration_notification', '\App\Http\Controllers\Api\ConsumerCardController@registrationNotification');
	Route::post('add_card_in_registration', '\App\Http\Controllers\Api\ConsumerCardController@addCardInRegistration');

	Route::post('get_project_configurations', '\App\Http\Controllers\Api\ProjectConfigurationController@getProjectConfigurations');
	Route::post('get_time_zones', '\App\Http\Controllers\Api\ProjectConfigurationController@getTimeZones');

	// Staff routes
	Route::post('staff/login', '\App\Http\Controllers\Api\AuthController@authenticateStaff');

	// Validate email
	Route::post('validate_email', '\App\Http\Controllers\Api\AuthController@validateEmail');

	// Authenticated routes
	Route::group(['middleware' => 'jwt.auth'], function () {
		Route::post('club_categories', '\App\Http\Controllers\Api\ClubCategoryController@getCategories');
		Route::post('set_default_club', '\App\Http\Controllers\Api\ClubController@setDefaultClub');
		Route::post('get_club_details', '\App\Http\Controllers\Api\ClubController@getClubDetails');

		//Product routes
		Route::post('get_category_products', '\App\Http\Controllers\Api\ProductController@getCategoryProducts');
        Route::post('get_search_products', '\App\Http\Controllers\Api\ProductController@getSearchProducts');
        Route::post('prepare_checkout_for_product_purchase', '\App\Http\Controllers\Api\ProductController@prepareCheckoutForProductPurchase');
        Route::post('product_purchase_payment', '\App\Http\Controllers\Api\ProductController@productPurchasePayment');
        Route::post('get_product_orders', '\App\Http\Controllers\Api\ProductController@getProductOrders');
        Route::post('get_product_configurations', '\App\Http\Controllers\Api\ProductController@getProductConfigurations');
        Route::post('make_product_payment', '\App\Http\Controllers\Api\ProductController@makeProductPayment');
        Route::post('validate_product_payment', '\App\Http\Controllers\Api\ProductController@validateProductPayment');


		//Product Category routes
		Route::post('get_categories', '\App\Http\Controllers\Api\CategoryController@getCategories');
		Route::post('get_categories_based_on_seat', '\App\Http\Controllers\Api\CategoryController@getCategoriesBasedOnSeat');

		// Consumer routes
		Route::post('consumer/update_profile', '\App\Http\Controllers\Api\ConsumerController@updateProfile');
		Route::post('consumer/delete_account', '\App\Http\Controllers\Api\ConsumerController@deleteAccount');
		Route::post('consumer/logout', '\App\Http\Controllers\Api\AuthController@logout');
		Route::post('consumer/get_profile', '\App\Http\Controllers\Api\ConsumerController@getProfile');
		Route::post('consumer/change_password', '\App\Http\Controllers\Api\AuthController@changePassword');
		Route::post('consumer/update_settings', '\App\Http\Controllers\Api\ConsumerController@updateSettings');
		Route::post('consumer/validate_password', '\App\Http\Controllers\Api\AuthController@validatePassword');

		// Card management
		Route::get('list_cards', '\App\Http\Controllers\Api\ConsumerCardController@listCards');
		Route::post('add_card', '\App\Http\Controllers\Api\ConsumerCardController@addCard');
		Route::post('remove_card', '\App\Http\Controllers\Api\ConsumerCardController@removeCard');

		// News
		Route::post('news', '\App\Http\Controllers\Api\NewsController@getNews');
		Route::post('news_details', '\App\Http\Controllers\Api\NewsController@getNewsDetails');

		// CTAs
		Route::post('ctas', '\App\Http\Controllers\Api\CTAController@getCtas');
		Route::post('cta_details', '\App\Http\Controllers\Api\CTAController@getCTADetails');

		// Polls
		Route::post('polls', '\App\Http\Controllers\Api\PollController@getPolls');
		Route::post('poll_details', '\App\Http\Controllers\Api\PollController@getPollDetails');
		Route::post('save_poll_result', '\App\Http\Controllers\Api\PollController@savePollResult');

		// Feeds
		Route::post('get_update_feeds', '\App\Http\Controllers\Api\FeedItemController@getUpdateFeeds');
		Route::post('rss_details', '\App\Http\Controllers\Api\FeedItemController@getRSSDetails');

		// Travel informatiom
		Route::post('travel_information_pages', '\App\Http\Controllers\Api\TravelInformationPageController@getTravelInformationPages');
		Route::post('travel_offers_and_information_pages', '\App\Http\Controllers\Api\TravelInformationPageController@getTravelOfferAndInformation');

		// Travel offers
		Route::post('travel_special_offers', '\App\Http\Controllers\Api\TravelOfferController@getTravelSpecialOffers');

		// Stadium
		Route::post('get_directions_to_stadium', '\App\Http\Controllers\Api\StadiumGeneralSettingController@getDirectionsToStadium');
		Route::post('find_my_seat_to_stadium', '\App\Http\Controllers\Api\StadiumGeneralSettingController@findMySeatToStadium');

		// Matches
		Route::post('get_fixtures_list', '\App\Http\Controllers\Api\MatchController@getFixturesList');
		Route::post('match_details', '\App\Http\Controllers\Api\MatchController@getMatchDetails');
		Route::post('get_in_progress_match_details', '\App\Http\Controllers\Api\MatchController@getInProgressMatchDetails');
		Route::post('get_close_to_real_time_events', '\App\Http\Controllers\Api\MatchController@getCloseToRealTimeEvents');
		Route::post('get_upcoming_matches', '\App\Http\Controllers\Api\MatchController@getUpcomingMatch');
		Route::post('get_finished_matches', '\App\Http\Controllers\Api\MatchController@getFinishedMatch');
		Route::post('get_match_players_with_votes', '\App\Http\Controllers\Api\MatchPlayerController@getMatchPlayersWithVotes');
		Route::post('vote_match_player', '\App\Http\Controllers\Api\MatchPlayerController@voteMatchPlayer');
		Route::post('standings', '\App\Http\Controllers\Api\StandingController@getStandings');

		// Membership packages
		Route::get('get_membership_packages', '\App\Http\Controllers\Api\MembershipPackageController@getMembershipPackages');
		Route::post('prepare_checkout_for_membership_package_purchase', '\App\Http\Controllers\Api\MembershipPackageController@prepareCheckoutForMembershipPackagePurchase');
		Route::post('membership_package_purchase_payment', '\App\Http\Controllers\Api\MembershipPackageController@membershipPackagePurchasePayment');
		Route::post('make_membership_package_payment', '\App\Http\Controllers\Api\MembershipPackageController@makeMembershipPackageayment');
		Route::post('validate_membership_package_payment', '\App\Http\Controllers\Api\MembershipPackageController@validateMembershipPackagePayment');

		// Pricing bands
		Route::post('get_pricing_bands', '\App\Http\Controllers\Api\PricingBandController@getPricingBands');

		// Tickets
		Route::post('save_ticket_notification', '\App\Http\Controllers\Api\TicketController@saveTicketNotification');
		Route::post('prepare_checkout_for_ticket_purchase', '\App\Http\Controllers\Api\TicketController@prepareCheckoutForTicketPurchase');
		Route::post('ticket_purchase_payment', '\App\Http\Controllers\Api\TicketController@ticketPurchasePayment');
		Route::post('get_user_upcoming_match_ticket', '\App\Http\Controllers\Api\TicketController@getUserUpcomingMatchTicket');
		Route::post('email_match_tickets_in_pdf', '\App\Http\Controllers\Api\TicketController@emailMatchTicketsInPdf');
		Route::post('get_user_ticket_wallet_details', '\App\Http\Controllers\Api\TicketController@getUserTicketWalletDetails');
		Route::post('sell_match_ticket', '\App\Http\Controllers\Api\TicketController@sellMatchTicket');
		Route::post('scan_ticket', '\App\Http\Controllers\Api\TicketController@scanTicket');
		Route::post('make_match_ticket_payment', '\App\Http\Controllers\Api\TicketController@makeMatchTicketPayment');
		Route::post('validate_match_ticket_payment', '\App\Http\Controllers\Api\TicketController@validateMatchTicketPayment');

		//Club Information Pages
		Route::post('club_information_pages', '\App\Http\Controllers\Api\ClubInformationPageController@getClubInformationPages');

		//Club App Settings
		Route::post('club_app_settings', '\App\Http\Controllers\Api\ClubAppSettingController@getClubAppSettingData');

		//Travel Warnings
		Route::post('travel_warnings', '\App\Http\Controllers\Api\TravelWarningController@getTravelWarnings');

		// Events
		Route::post('save_event_notification', '\App\Http\Controllers\Api\EventController@saveEventNotification');
		Route::post('get_events', '\App\Http\Controllers\Api\EventController@getEvents');
		Route::post('prepare_checkout_for_event_purchase', '\App\Http\Controllers\Api\EventController@prepareCheckoutForEventPurchase');
		Route::post('event_purchase_payment', '\App\Http\Controllers\Api\EventController@eventPurchasePayment');
		Route::post('email_event_tickets_in_pdf', '\App\Http\Controllers\Api\EventController@emailEventTicketsInPdf');
		Route::post('make_event_ticket_payment', '\App\Http\Controllers\Api\EventController@makeEventTicketPayment');
		Route::post('validate_event_ticket_payment', '\App\Http\Controllers\Api\EventController@validateEventTicketPayment');

		// Hospitality
		Route::post('get_hospitality_suites','\App\Http\Controllers\Api\HospitalitySuiteController@getHospitalitySuites');
		Route::post('prepare_checkout_for_hospitality_suite_purchase','\App\Http\Controllers\Api\HospitalitySuiteController@prepareCheckoutForHospitalitySuitePurchase');
		Route::post('hospitality_suite_purchase_payment', '\App\Http\Controllers\Api\HospitalitySuiteController@hospitalitySuitePurchasePayment');
		Route::post('get_upcoming_matches_for_hospitality', '\App\Http\Controllers\Api\HospitalitySuiteController@getUpcomingMatchesForHospitality');
		Route::post('get_hospitality_suite_detail', '\App\Http\Controllers\Api\HospitalitySuiteController@getHospitalitySuiteDetail');
		Route::post('email_hospitality_suite_tickets_in_pdf', '\App\Http\Controllers\Api\HospitalitySuiteController@emailHospitalitySuiteTicketsInPdf');
		Route::post('save_hospitality_suite_notification', '\App\Http\Controllers\Api\HospitalitySuiteController@saveHospitalitySuiteNotification');
		Route::post('make_hospitality_ticket_payment', '\App\Http\Controllers\Api\HospitalitySuiteController@makeHospitalityTicketPayment');
		Route::post('validate_hospitality_ticket_payment', '\App\Http\Controllers\Api\HospitalitySuiteController@validateHospitalityTicketPayment');


		// Videos
		Route::post('videos', '\App\Http\Controllers\Api\VideoController@getVideos');

		// Quizzes
		Route::post('quizzes', '\App\Http\Controllers\Api\QuizController@getQuizzes');
		Route::post('submit_quiz', '\App\Http\Controllers\Api\QuizController@submitQuiz');

		// Account
		Route::post('get_orders', '\App\Http\Controllers\Api\AccountController@getOrders');

		// Staff
		Route::post('staff/get_profile', '\App\Http\Controllers\Api\StaffController@getProfile');
		Route::post('staff/update_profile', '\App\Http\Controllers\Api\StaffController@updateProfile');
		Route::post('staff/change_password', '\App\Http\Controllers\Api\AuthController@changeStaffUserPassword');
		Route::post('staff/logout', '\App\Http\Controllers\Api\AuthController@staffLogout');

		// Loyalty reward products
		Route::post('get_loyalty_reward_products', '\App\Http\Controllers\Api\LoyaltyRewardController@getLoyaltyRewardProducts');
		Route::post('purchase_loyalty_reward_product', '\App\Http\Controllers\Api\LoyaltyRewardController@purchaseLoyaltyRewardProduct');
		Route::post('send_device_token', '\App\Http\Controllers\Api\UserController@sendDeviceToken');
		Route::post('get_loyalty_reward_history', '\App\Http\Controllers\Api\LoyaltyRewardController@getLoyaltyRewardHistory');
		Route::post('get_loyalty_reward_based_on_seat', '\App\Http\Controllers\Api\LoyaltyRewardController@getLoyaltyRewardBasedOnSeat');
		// Collection point
		Route::post('get_collection_points', '\App\Http\Controllers\Api\CollectionPointController@getCollectionPoints');
		Route::post('get_product_and_loyalty_reward_transactions_collection_point_wise', '\App\Http\Controllers\Api\CollectionPointController@getProductAndLoyaltyRewardTransactionsCollectionPointWise');
		Route::post('change_order_status', '\App\Http\Controllers\Api\CollectionPointController@changeOrderStatus');
		Route::post('scan_order', '\App\Http\Controllers\Api\CollectionPointController@scanOrder');

		// Payment IQ Integration APIs
		Route::get('get_user_payment_accounts', '\App\Http\Controllers\Api\AccountController@getUserPaymentAccounts');
		Route::delete('delete_user_payment_account', '\App\Http\Controllers\Api\AccountController@deleteUserPaymentAccounts');
	});

	// Payment IQ Integration APIs
	Route::post('paymentiq/verifyuser', '\App\Http\Controllers\Api\PaymentIQController@verifyUser');
	Route::post('paymentiq/authorize', '\App\Http\Controllers\Api\PaymentIQController@authorize');
	Route::post('paymentiq/transfer', '\App\Http\Controllers\Api\PaymentIQController@transfer');
	Route::post('paymentiq/cancel', '\App\Http\Controllers\Api\PaymentIQController@cancel');
});
