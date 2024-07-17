<?php

/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/contentfeed/feeditems', 'FeedItemController@getFeedItems');
Route::middleware(['auth'])->group(function () {
    Route::get('/changepassword', 'UserController@changepassword')->name('backend.changepassword')->middleware('check.cms.panel');
    Route::post('/storechangepassword', 'UserController@storechangepassword')->name('backend.storechangepassword');

    Route::middleware(['role:superadmin', 'check.cms.panel'])->group(function () {
        Route::get('/dashboard', 'DashboardController@showSuperAdminDashboard')->name('backend.superadmin.dashboard');

        // CMS user routes
        Route::get('/cmsuser', 'UserController@getCmsUsers')->name('backend.cms.index');
        Route::post('/cmsuser', 'UserController@storeCmsUser')->name('backend.cms.store');
        Route::get('/cmsuser/create', 'UserController@createCmsUser')->name('backend.cms.create');
        Route::get('/cmsuser/{user}/edit', 'UserController@editCmsUser')->name('backend.cms.edit');
        Route::put('/cmsuser/{user}', 'UserController@updateCmsUser')->name('backend.cms.update');
        Route::delete('/cmsuser/{user}', 'UserController@destroyCmsUser')->name('backend.cms.destroy');
        Route::post('/getCMSUserData', 'UserController@getCMSUserData')->name('backend.cms.user.data');
        Route::post('/cmsuser/checkEmail', 'UserController@checkEmail')->name('backend.cms.checkemail');
        Route::post('/cmsuser/viewrole', 'UserController@viewrole')->name('backend.cms.viewrole');
		Route::put('/cmsuser/email/{user}','UserController@sendEmail')->name('backend.cms.email.send');

        // staff user routes
        Route::get('/staffuser', 'StaffController@index')->name('backend.staff.index');
        Route::post('/staffuser', 'StaffController@store')->name('backend.staff.store');
        Route::get('/staffuser/create', 'StaffController@create')->name('backend.staff.create');
        Route::put('/staffuser/{user}', 'StaffController@update')->name('backend.staff.update');
        Route::get('/staffuser/{user}/edit', 'StaffController@edit')->name('backend.staff.edit');
        Route::delete('/staffuser/{user}', 'StaffController@destroy')->name('backend.staff.destroy');
        Route::post('/getStaffAPPUserData', 'StaffController@getStaffAPPUserData')->name('backend.staff.user.data');
        Route::post('/staffuser/checkEmail', 'UserController@checkEmail')->name('backend.staff.checkemail');

        //Use role routes
        Route::get('/role', 'UserRoleController@index')->name('backend.role.index');
        Route::post('/role', 'UserRoleController@store')->name('backend.role.store');
        Route::get('/role/create', 'UserRoleController@create')->name('backend.role.create');
        Route::put('/role/{role}', 'UserRoleController@update')->name('backend.role.update');
        Route::get('/role/{role}/edit', 'UserRoleController@edit')->name('backend.role.edit');
        Route::delete('/role/{role}', 'UserRoleController@destroy')->name('backend.role.destroy');
        Route::post('/getRoleData', 'UserRoleController@getRoleData')->name('backend.role.data');

        // Consumer user routes
        Route::get('/consumer', 'ConsumerController@index')->name('backend.consumer.index');
        Route::post('/consumer', 'ConsumerController@store')->name('backend.consumer.store');
        Route::get('/consumer/create', 'ConsumerController@create')->name('backend.consumer.create');
        Route::put('/consumer/{user}', 'ConsumerController@update')->name('backend.consumer.update');
        Route::get('/consumer/{user}/edit', 'ConsumerController@edit')->name('backend.consumer.edit');
        Route::delete('/consumer/{user}', 'ConsumerController@destroy')->name('backend.consumer.destroy');
        Route::post('/getConsumerAPPUserData', 'ConsumerController@getConsumerAPPUserData')->name('backend.consumer.user.data');
        Route::post('/consumer/checkEmail', 'UserController@checkEmail')->name('backend.consumer.checkemail');

        // Club categories routes
        Route::get('/clubcategory', 'ClubCategoryController@index')->name('backend.clubcategory.index');
        Route::post('/clubcategory', 'ClubCategoryController@store')->name('backend.clubcategory.store');
        Route::get('/clubcategory/create', 'ClubCategoryController@create')->name('backend.clubcategory.create');
        Route::put('/clubcategory/{category}', 'ClubCategoryController@update')->name('backend.clubcategory.update');
        Route::get('/clubcategory/{category}/edit', 'ClubCategoryController@edit')->name('backend.clubcategory.edit');
        Route::delete('/clubcategory/{category}', 'ClubCategoryController@destroy')->name('backend.clubcategory.destroy');
        Route::post('/getClubCategoryData', 'ClubCategoryController@getClubCategoryData')->name('backend.clubcategory.data');

        // Club routes
        Route::get('/club', 'ClubController@index')->name('backend.club.index');
        Route::post('/club', 'ClubController@store')->name('backend.club.store');
        Route::get('/club/create', 'ClubController@create')->name('backend.club.create');
        Route::get('/club/{clubId}/edit', 'ClubController@edit')->name('backend.club.edit');
        Route::put('/club/{clubId}', 'ClubController@update')->name('backend.club.update');
        Route::delete('/club/{clubId}', 'ClubController@destroy')->name('backend.club.destroy');
        Route::post('/getClubData', 'ClubController@getClubData')->name('backend.club.data');

        // Competition routes
        Route::get('/competition', 'CompetitionController@index')->name('backend.competition.index');
        Route::post('/competition', 'CompetitionController@store')->name('backend.competition.store');
        Route::get('/competition/create', 'CompetitionController@create')->name('backend.competition.create');
        Route::put('/competition/{competition}', 'CompetitionController@update')->name('backend.competition.update');
        Route::get('/competition/{competition}/edit', 'CompetitionController@edit')
                    ->name('backend.competition.edit');
        Route::delete('/competition/{competition}', 'CompetitionController@destroy')->name('backend.competition.destroy');
        Route::post('/getCompetitionData', 'CompetitionController@getCompetitionData')->name('backend.competition.data');

        // Transaction Settings
		Route::get('/transaction/settings', 'SettingController@index')->name('backend.setting.index');
		Route::post('/transaction/settings', 'SettingController@update')->name('backend.setting.update');

        Route::get('/transactions/report/{type}', 'TransactionController@report')->where('type', 'eur|gbp')->name('backend.transaction.report');
        Route::post('/transactions/report/{type}/getData', 'TransactionController@getReportData')->where('type', 'eur|gbp')->name('backend.transaction.report.data');

        Route::get('/transactions/report/transaction/{id}/{type}', 'TransactionController@getReportTransactionDetail')->name('backend.transaction.report.detail');
        Route::get('/transactions/report/transaction/{id}/{type}/status', 'TransactionController@showReportStatusForm')->name('backend.transaction.report.showstatus');
        Route::put('/transactions/report/transaction/{id}/{type}/status', 'TransactionController@updateReportStatus')->name('backend.transaction.report.updatestatus');

        Route::get('/transactions/report/review/{type}', 'TransactionController@review')->where('type', 'eur|gbp')->name('backend.transaction.report.review');
        Route::post('/transactions/report/review/{type}/reviewData', 'TransactionController@reviewData')->where('type', 'eur|gbp')->name('backend.transaction.report.review.data');
        Route::get('/transactions/report/review/{type}/{consumer_id}', 'TransactionController@getConsumerReviewTransactions')->name('admin.transactions.report.consumer.data');
        Route::get('/transactions/report/export/{type}', 'TransactionController@export')->name('backend.transaction.report.export');
    });

    Route::group(['prefix' => '{club}',  'middleware' => ['check.cms.panel']], function () {
        
        Route::group(['middleware' => ['role_or_permission:superadmin|access.clubadmin.dashboard.own']], function () {
            Route::get('/clubdashboard', 'DashboardController@showClubAdminDashboard')->name('backend.clubadmin.dashboard');
        });
        Route::group(['middleware' => ['role_or_permission:superadmin|manage.feeds.own']], function () {
            // Content feed routes
            Route::get('/contentfeed', 'ContentFeedController@index')->name('backend.contentfeed.index');
            Route::post('/contentfeed', 'ContentFeedController@store')->name('backend.contentfeed.store');
            Route::get('/contentfeed/create', 'ContentFeedController@create')->name('backend.contentfeed.create');
            Route::put('/contentfeed/{contentFeed}', 'ContentFeedController@update')->name('backend.contentfeed.update');
            Route::get('/contentfeed/{contentFeed}/edit', 'ContentFeedController@edit')->name('backend.contentfeed.edit');
            Route::delete('/contentfeed/{contentFeed}', 'ContentFeedController@destroy')->name('backend.contentfeed.destroy');
            Route::post('/getContentFeedData', 'ContentFeedController@getContentFeedData')->name('backend.contentfeed.data');
            // Route::get('/contentfeed/facebook', 'ContentFeedController@facebook')->name('backend.contentfeed.facebook');
            //
            // Feed item routes
            Route::get('/feeditem', 'FeedItemController@index')->name('backend.feeditem.index');
            Route::get('/feeditem/{feedItem}/edit', 'FeedItemController@show')->name('backend.feeditem.show');
            Route::put('/feeditem/{feeditem}', 'FeedItemController@update')->name('backend.feeditem.update');
            Route::post('/getFeedItemData', 'FeedItemController@getFeedItemData')->name('backend.feeditem.data');
        });
        Route::group(['middleware' => ['role_or_permission:superadmin|manage.travelinformation.own']], function () {
            // Travel offer routes
            Route::get('/traveloffer', 'TravelOfferController@index')->name('backend.traveloffers.index');
            Route::get('/traveloffer/create', 'TravelOfferController@create')->name('backend.traveloffers.create');
            Route::get('/traveloffer/{travelOffers}/edit', 'TravelOfferController@edit')->name('backend.traveloffers.edit');
            Route::delete('/traveloffer/{travelOffers}', 'TravelOfferController@destroy')->name('backend.traveloffers.destroy');
            Route::post('/getTraveloffersData', 'TravelOfferController@getTraveloffersData')->name('backend.traveloffers.data');
            Route::post('/traveloffer', 'TravelOfferController@store')->name('backend.traveloffers.store');
            Route::put('/traveloffer/{travelOffers}', 'TravelOfferController@update')->name('backend.traveloffers.update');

            // Travel information routes
            Route::get('/travelinformationpage', 'TravelInformationPageController@index')->name('backend.travelinformationpages.index');
            Route::post('/travelinformationpage', 'TravelInformationPageController@store')->name('backend.travelinformationpages.store');
            Route::get('/travelinformationpage/create', 'TravelInformationPageController@create')->name('backend.travelinformationpages.create');
            Route::put('/travelinformationpage/{travelInformationPage}', 'TravelInformationPageController@update')->name('backend.travelinformationpages.update');
            Route::get('/travelinformationpage/{travelInformationPage}/edit', 'TravelInformationPageController@edit')->name('backend.travelinformationpages.edit');
            Route::delete('/travelinformationpage/{travelInformationPage}', 'TravelInformationPageController@destroy')->name('backend.travelinformationpages.destroy');
            Route::post('/getTravelInformationPageData', 'TravelInformationPageController@getTravelInformationPageData')->name('backend.travelinformationpages.data');
            // Travel warnings routes
            Route::get('/travelwarning', 'TravelWarningController@index')->name('backend.travelwarnings.index');
            Route::get('/travelwarning/create', 'TravelWarningController@create')->name('backend.travelwarnings.create');
            Route::get('/travelwarning/{travelWarning}/edit', 'TravelWarningController@edit')->name('backend.travelwarnings.edit');
            Route::delete('/travelwarning/{travelWarning}', 'TravelWarningController@destroy')->name('backend.travelwarnings.destroy');
            Route::post('/getTravelWarningsData', 'TravelWarningController@getTravelWarningsData')->name('backend.travelwarnings.data');
            Route::post('/travelwarning', 'TravelWarningController@store')->name('backend.travelwarnings.store');
            Route::put('/travelwarning/{travelWarning}', 'TravelWarningController@update')->name('backend.travelwarnings.update');
        });
        Route::group(['middleware' => ['role_or_permission:superadmin|manage.news.own']], function () {
            // News routes
            Route::get('/news', 'NewsController@index')->name('backend.news.index');
            Route::get('/news/create', 'NewsController@create')->name('backend.news.create');
            Route::get('/news/{news}/edit', 'NewsController@edit')->name('backend.news.edit');
            Route::delete('/news/{news}', 'NewsController@destroy')->name('backend.news.destroy');
            Route::post('/getNewsData', 'NewsController@getNewsData')->name('backend.news.data');
            Route::post('/news', 'NewsController@store')->name('backend.news.store');
            Route::put('/news/{news}', 'NewsController@update')->name('backend.news.update');
        });
        Route::group(['middleware' => ['role_or_permission:superadmin|manage.polls.own']], function () {
            // Poll routes
            Route::get('/poll', 'PollController@index')->name('backend.poll.index');
            Route::post('/poll', 'PollController@store')->name('backend.poll.store');
            Route::get('/poll/create', 'PollController@create')->name('backend.poll.create');
            Route::put('/poll/{poll}', 'PollController@update')->name('backend.poll.update');
            Route::get('/poll/{poll}/edit', 'PollController@edit')->name('backend.poll.edit');
            Route::delete('/poll/{poll}', 'PollController@destroy')->name('backend.poll.destroy');
            Route::post('/getPollData', 'PollController@getPollData')->name('backend.poll.data');
        });
        Route::group(['middleware' => ['role_or_permission:superadmin|manage.pushnotifications.own']], function () {
            // Push notifications routes
            Route::get('/pushnotification', 'PushNotificationController@index')->name('backend.pushnotification.index');
            Route::post('/pushnotification', 'PushNotificationController@store')->name('backend.pushnotification.store');
            Route::get('/pushnotification/create', 'PushNotificationController@create')->name('backend.pushnotification.create');
            Route::put('/pushnotification/{pushnotification}', 'PushNotificationController@update')->name('backend.pushnotification.update');
            Route::get('/pushnotification/{pushnotification}/edit', 'PushNotificationController@edit')->name('backend.pushnotification.edit');
            Route::delete('/pushnotification/{pushnotification}', 'PushNotificationController@destroy')->name('backend.pushnotification.destroy');
            Route::post('/getPushnotificationData', 'PushNotificationController@getPushnotificationData')->name('backend.pushnotification.data');
            Route::post('/pushnotification/getSwipeActionItems', 'PushNotificationController@getSwipeActionItems')->name('backend.pushnotification.getSwipeActionItems');
            Route::post('/pushnotification/{pushnotification?}/getSwipeActionItems', 'PushNotificationController@getSwipeActionItems')->name('backend.pushnotification.getSwipeActionItems');
        });
        Route::group(['middleware' => ['role_or_permission:superadmin|manage.ctas.own']], function () {
            // CTA routes
            Route::get('/cta', 'CTAController@index')->name('backend.cta.index');
            Route::post('/cta', 'CTAController@store')->name('backend.cta.store');
            Route::get('/cta/create', 'CTAController@create')->name('backend.cta.create');
            Route::put('/cta/{cta}', 'CTAController@update')->name('backend.cta.update');
            Route::get('/cta/{cta}/edit', 'CTAController@edit')->name('backend.cta.edit');
            Route::delete('/cta/{cta}', 'CTAController@destroy')->name('backend.cta.destroy');
            Route::post('/getCTAData', 'CTAController@getCTAData')->name('backend.cta.data');
            Route::post('/cta/getSwipeActionItems', 'PushNotificationController@getSwipeActionItems')->name('backend.pushnotification.getSwipeActionItems');
             Route::post('/cta/{cta?}/getSwipeActionItems', 'PushNotificationController@getSwipeActionItems')->name('backend.pushnotification.getSwipeActionItems');
        });
        Route::group(['middleware' => ['role_or_permission:superadmin|manage.stadium.own']], function () {
            //  Stadium hospitality suite routes
            Route::get('/hospitalitysuite', 'HospitalitySuiteController@index')->name('backend.hospitalitysuite.index');
            Route::get('/hospitalitysuite/create', 'HospitalitySuiteController@create')->name('backend.hospitalitysuite.create');
            Route::get('/hospitalitysuite/{hospitalitySuites}/edit', 'HospitalitySuiteController@edit')->name('backend.hospitalitysuite.edit');
            Route::delete('/hospitalitysuite/{hospitalitySuites}', 'HospitalitySuiteController@destroy')->name('backend.hospitalitysuite.destroy');
            Route::post('/getHospitalitySuitesData', 'HospitalitySuiteController@getHospitalitySuitesData')->name('backend.hospitalitysuite.data');
            Route::post('/hospitalitysuite', 'HospitalitySuiteController@store')->name('backend.hospitalitysuite.store');
            Route::put('/hospitalitysuite/{hospitalitySuites}', 'HospitalitySuiteController@update')->name('backend.hospitalitysuite.update');

            // Stadium general setting routes
            Route::put('/stadiumgeneralsetting', 'StadiumGeneralSettingController@update')->name('backend.stadiumgeneralsettings.update');
            Route::get('/stadiumgeneralsetting/edit', 'StadiumGeneralSettingController@edit')->name('backend.stadiumgeneralsettings.edit');

            // Pricing band routes
            Route::get('/pricingband', 'PricingBandController@index')->name('backend.pricingbands.index');
            Route::post('/pricingband', 'PricingBandController@store')->name('backend.pricingbands.store');
            Route::get('/pricingband/create', 'PricingBandController@create')->name('backend.pricingbands.create');
            Route::put('/pricingband/{pricingBand}', 'PricingBandController@update')->name('backend.pricingbands.update');
            Route::get('/pricingband/{pricingBand}/edit', 'PricingBandController@edit')->name('backend.pricingbands.edit');
            Route::delete('/pricingband/{pricingBand}', 'PricingBandController@destroy')->name('backend.pricingbands.destroy');
            Route::post('/getPricingBandData', 'PricingBandController@getPricingBandData')->name('backend.pricingbands.data');
            Route::post('pricingband/validateSeatData', 'PricingBandController@validateSeatData')->name('backend.pricingbands.validateSeatData');

            // Stadium entrance routes
            Route::put('/stadiumentrance', 'StadiumEntranceController@update')->name('backend.stadiumentrance.update');
            Route::get('/stadiumentrance/edit', 'StadiumEntranceController@edit')->name('backend.stadiumentrance.edit');
            Route::delete('/stadiumentrance/{stadiumEntrance}', 'StadiumEntranceController@destroy')->name('backend.stadiumentrance.destroy');
            Route::post('/getGenralSettingData', 'StadiumEntranceController@getGenralSettingData')->name('backend.stadiumentrance.data');

            // Stadium block routes
            Route::get('/stadiumblock', 'StadiumBlockController@index')->name('backend.stadiumblocks.index');
            Route::post('/stadiumblock', 'StadiumBlockController@store')->name('backend.stadiumblocks.store');
            Route::get('/stadiumblock/create', 'StadiumBlockController@create')->name('backend.stadiumblocks.create');
            Route::put('/stadiumblock/{stadiumBlock}', 'StadiumBlockController@update')->name('backend.stadiumblocks.update');
            Route::get('/stadiumblock/{stadiumBlock}/edit', 'StadiumBlockController@edit')->name('backend.stadiumblocks.edit');
            Route::delete('/stadiumblock/{stadiumBlock}', 'StadiumBlockController@destroy')->name('backend.stadiumblocks.destroy');
            Route::post('/getStadiumBlockData', 'StadiumBlockController@getStadiumBlockData')->name('backend.stadiumblocks.data');
        });
        Route::group(['middleware' => ['role_or_permission:superadmin|manage.membershippackages.own']], function (){
            // Membership package routes
            Route::get('/membershippackage', 'MembershipPackageController@index')->name('backend.membershippackages.index');
            Route::post('/membershippackage', 'MembershipPackageController@store')->name('backend.membershippackages.store');
            Route::get('/membershippackage/create', 'MembershipPackageController@create')->name('backend.membershippackages.create');
            Route::put('/membershippackage/{membershipPackage}', 'MembershipPackageController@update')->name('backend.membershippackages.update');
            Route::get('/membershippackage/{membershipPackage}/edit', 'MembershipPackageController@edit')->name('backend.membershippackages.edit');
            Route::delete('/membershippackage/{membershipPackage}', 'MembershipPackageController@destroy')->name('backend.membershippackages.destroy');
            Route::post('/getMembershipPackageData', 'MembershipPackageController@getMembershipPackageData')->name('backend.membershippackages.data');
        });
        Route::group(['middleware' => ['role_or_permission:superadmin|manage.matches.own']], function (){
            // Match routes
            Route::get('/match', 'MatchController@index')->name('backend.matches.index');
            Route::post('/match', 'MatchController@store')->name('backend.matches.store');
            Route::get('/match/create', 'MatchController@create')->name('backend.matches.create');
            Route::put('/match/{match}', 'MatchController@update')->name('backend.matches.update');
            Route::get('/match/{match}/edit', 'MatchController@edit')->name('backend.matches.edit');
            Route::delete('/match/{match}', 'MatchController@destroy')->name('backend.matches.destroy');
            Route::post('/getMatchData', 'MatchController@getMatchData')->name('backend.matches.data');
            Route::post('/match/addPlayer', 'MatchController@addPlayer')->name('backend.matches.addplayer');
        });
        Route::group(['middleware' => ['role_or_permission:superadmin|manage.events.own']], function (){
            // Event routes
            Route::get('/event', 'EventController@index')->name('backend.event.index');
            Route::get('/event/create', 'EventController@create')->name('backend.event.create');
            Route::get('/event/{event}/edit', 'EventController@edit')->name('backend.event.edit');
            Route::delete('/event/{event}', 'EventController@destroy')->name('backend.event.destroy');

            Route::post('/getEventData', 'EventController@getEventData')->name('backend.event.data');
            Route::post('/event', 'EventController@store')->name('backend.event.store');
            Route::put('/event/{event}', 'EventController@update')->name('backend.event.update');
        });
        Route::group(['middleware' => ['role_or_permission:superadmin|manage.commerce.own']], function (){
            // Category routes
            Route::get('/category', 'CategoryController@index')->name('backend.category.index');
            Route::get('/category/create', 'CategoryController@create')->name('backend.category.create');
            Route::get('/category/{category}/edit', 'CategoryController@edit')->name('backend.category.edit');
            Route::delete('/category/{category}', 'CategoryController@destroy')->name('backend.category.destroy');

            Route::post('/getCategoryData', 'CategoryController@getCategoryData')->name('backend.category.data');
            Route::post('/category', 'CategoryController@store')->name('backend.category.store');
            Route::put('/category/{category}', 'CategoryController@update')->name('backend.category.update');

            //Product routes
            Route::get('/product', 'ProductController@index')->name('backend.product.index');
            Route::get('/product/create', 'ProductController@create')->name('backend.product.create');
            Route::get('/product/{product}/edit', 'ProductController@edit')->name('backend.product.edit');
            Route::delete('/product/{product}', 'ProductController@destroy')->name('backend.product.destroy');

            Route::post('/getProductData', 'ProductController@getProductData')->name('backend.product.data');
            Route::post('/getProductCategoryData', 'ProductController@getProductCategoryData')->name('backend.product.category');

            Route::post('/product', 'ProductController@store')->name('backend.product.store');
            Route::put('/product/{product}', 'ProductController@update')->name('backend.product.update');

            //Collection Points
            Route::get('/collectionpoint', 'CollectionPointController@index')->name('backend.collectionpoint.index');
            Route::get('/collectionpoint/create', 'CollectionPointController@create')->name('backend.collectionpoint.create');
            Route::post('/collectionpoint', 'CollectionPointController@store')->name('backend.collectionpoint.store');
            Route::get('/collectionpoint/{collectionpoint}/edit', 'CollectionPointController@edit')->name('backend.collectionpoint.edit');
            Route::delete('/collectionpoint/{collectionpoint}', 'CollectionPointController@destroy')->name('backend.collectionpoint.destroy');

            Route::post('/getCollectionPointData', 'CollectionPointController@getProductData')->name('backend.collectionpoint.data');

            Route::post('/collectionpoint', 'CollectionPointController@store')->name('backend.collectionpoint.store');
            Route::put('/collectionpoint/{collectionpoint}', 'CollectionPointController@update')->name('backend.collectionpoint.update');
            //  Loyalty rewards routes
            Route::get('/loyaltyreward', 'LoyaltyRewardController@index')->name('backend.loyaltyreward.index');
            Route::post('/getLoyaltyRewardsData', 'LoyaltyRewardController@getLoyaltyRewardsData')->name('backend.loyaltyreward.data');
            Route::get('/loyaltyreward/create', 'LoyaltyRewardController@create')->name('backend.loyaltyreward.create');
            Route::post('/loyaltyreward', 'LoyaltyRewardController@store')->name('backend.loyaltyreward.store');
            Route::get('/loyaltyreward/{loyaltyRewards}/edit', 'LoyaltyRewardController@edit')->name('backend.loyaltyreward.edit');
            Route::put('/loyaltyreward/{loyaltyRewards}', 'LoyaltyRewardController@update')->name('backend.loyaltyreward.update');
            Route::delete('/loyaltyreward/{loyaltyRewards}', 'LoyaltyRewardController@destroy')->name('backend.loyaltyreward.destroy');
            //Special Offer
            Route::get('/specialoffer', 'SpecialOfferController@index')->name('backend.specialoffer.index');
            Route::get('/specialoffer/create', 'SpecialOfferController@create')->name('backend.specialoffer.create');
            Route::post('/specialoffer', 'SpecialOfferController@store')->name('backend.specialoffer.store');
            Route::post('/getSpecialOfferData', 'SpecialOfferController@getSpecialOfferData')->name('backend.specialoffer.offer');
            Route::get('/specialoffer/{specialoffer}/edit', 'SpecialOfferController@edit')->name('backend.specialoffer.edit');
            Route::put('/specialoffer/{specialoffer}/', 'SpecialOfferController@update')->name('backend.specialoffer.update');
            Route::delete('/specialoffer/{specialoffer}/', 'SpecialOfferController@destroy')->name('backend.specialoffer.destroy');
            Route::post('/specialoffer/gettypewiseproduct', 'SpecialOfferController@getTypewiseProduct')->name('backend.specialoffer.getTypewiseProduct');
        });
        Route::group(['middleware' => ['role_or_permission:superadmin|manage.clubinformation.own']], function (){
            // Club Information routes
            Route::get('/clubinformationpage','ClubInformationPageController@index')->name('backend.clubinformationpages.index');
            Route::get('/clubinformationpage/create','ClubInformationPageController@create')->name('backend.clubinformationpages.create');
            Route::post('/clubinformationpage', 'ClubInformationPageController@store')->name('backend.clubinformationpages.store');
            Route::post('/getClubInformationPageData', 'ClubInformationPageController@getClubInformationPageData')->name('backend.clubinformationpages.data');
            Route::put('/clubinformationpage/{clubInformationPage}', 'ClubInformationPageController@update')->name('backend.clubinformationpages.update');
            Route::get('/clubinformationpage/{clubInformationPage}/edit', 'ClubInformationPageController@edit')->name('backend.clubinformationpages.edit');
            Route::delete('/clubinformationpage/{clubInformationPage}', 'ClubInformationPageController@destroy')->name('backend.clubinformationpages.destroy');
        });
        Route::group(['middleware' => ['role_or_permission:superadmin|manage.app.settings.own']], function (){
            // Club App Settings
            Route::get('/clubappsetting/edit','ClubAppSettingController@edit')->name('backend.clubappsetting.edit');
            Route::put('/clubappsetting', 'ClubAppSettingController@update')->name('backend.clubappsetting.update');
        });
        Route::group(['middleware' => ['role_or_permission:superadmin|manage.videos.own']], function (){
            // Video Information routes
            Route::get('/video','VideoController@index')->name('backend.video.index');
            Route::get('/video/create','VideoController@create')->name('backend.video.create');
            Route::post('/video', 'VideoController@store')->name('backend.video.store');
            Route::post('/getVideosData', 'VideoController@getVideosData')->name('backend.video.data');
            Route::put('/video/{video}', 'VideoController@update')->name('backend.video.update');
            Route::get('/video/{video}/edit', 'VideoController@edit')->name('backend.video.edit');
            Route::delete('/video/{video}', 'VideoController@destroy')->name('backend.video.destroy');
        });
        Route::group(['middleware' => ['role_or_permission:superadmin|access.clubadmin.transactions.own']], function (){
        
            //  Transaction Reports
            Route::get('/transactions', 'TransactionController@index')->name('backend.transaction.index');
            Route::post('/getTransactionsData', 'TransactionController@getTransactionsData')->name('backend.transaction.data');
            Route::get('/transaction/{id}/{type}', 'TransactionController@getTransactionDetail')->name('backend.transaction.detail');
            Route::get('/transaction/{id}/{type}/status', 'TransactionController@showStatusForm')->name('backend.transaction.show.status');
            Route::put('/transaction/{id}/{type}/status', 'TransactionController@updateStatus')->name('backend.transaction.update.status');
        });
        Route::group(['middleware' => ['role_or_permission:superadmin|manage.quizzes.own']], function (){
            //Quiz
    		Route::get('/quiz','QuizController@index')->name('backend.quizzes.index');
    		Route::get('/quiz/create','QuizController@create')->name('backend.quizzes.create');
    		Route::post('/quiz','QuizController@store')->name('backend.quizzes.store');
    		Route::post('/getQuizData', 'QuizController@getQuizData')->name('backend.quizzes.quiz');
            Route::get('/quiz/{quiz}/edit', 'QuizController@edit')->name('backend.quizzes.edit');
            Route::put('/quiz/{quiz}', 'QuizController@update')->name('backend.quizzes.update');
            Route::delete('/quiz/{quiz}/', 'QuizController@destroy')->name('backend.quizzes.destroy');
        });
        Route::group(['middleware' => ['role_or_permission:superadmin|access.clubadmin.user.own']], function (){
            //user model
            Route::get('/cmsuser', 'UserController@getCmsUsers')->name('backend.cms.club.index');
            Route::post('/cmsuser', 'UserController@storeCmsUser')->name('backend.cms.club.store');
            Route::get('/cmsuser/create', 'UserController@createCmsUser')->name('backend.cms.club.create');
            Route::get('/cmsuser/{user}/edit', 'UserController@editCmsUser')->name('backend.cms.club.edit');
            Route::put('/cmsuser/{user}', 'UserController@updateCmsUser')->name('backend.cms.club.update');
            Route::delete('/cmsuser/{user}', 'UserController@destroyCmsUser')->name('backend.cms.club.destroy');
            Route::post('/getCMSUserData', 'UserController@getCMSUserData')->name('backend.cms.user.club.data');
            Route::post('/cmsuser/checkEmail', 'UserController@checkEmail')->name('backend.cms.club.checkemail');
            Route::post('/cmsuser/viewrole', 'UserController@viewrole')->name('backend.cms.club.viewrole');
    		Route::put('/cmsuser/email/{userId}','UserController@sendClubEmail')->name('backend.cms.club.email.send');

            // staff user routes
            Route::get('/staffuser', 'StaffController@index')->name('backend.staff.club.index');
            Route::post('/staffuser', 'StaffController@store')->name('backend.staff.club.store');
            Route::get('/staffuser/create', 'StaffController@create')->name('backend.staff.club.create');
            Route::put('/staffuser/{user}', 'StaffController@update')->name('backend.staff.club.update');
            Route::get('/staffuser/{user}/edit', 'StaffController@edit')->name('backend.staff.club.edit');
            Route::delete('/staffuser/{user}', 'StaffController@destroy')->name('backend.staff.club.destroy');
            Route::post('/getStaffAPPUserData', 'StaffController@getStaffAPPUserData')->name('backend.staff.user.club.data');
            Route::post('/staffuser/checkEmail', 'UserController@checkEmail')->name('backend.staff.club.checkemail');

            // Consumer user routes
            Route::get('/consumer', 'ConsumerController@index')->name('backend.consumer.club.index');
            Route::post('/consumer', 'ConsumerController@store')->name('backend.consumer.club.store');
            Route::get('/consumer/create', 'ConsumerController@create')->name('backend.consumer.club.create');
            Route::put('/consumer/{user}', 'ConsumerController@update')->name('backend.consumer.club.update');
            Route::get('/consumer/{user}/edit', 'ConsumerController@edit')->name('backend.consumer.club.edit');
            Route::delete('/consumer/{user}', 'ConsumerController@destroy')->name('backend.consumer.destroy');
            Route::post('/getConsumerAPPUserData', 'ConsumerController@getConsumerAPPUserData')->name('backend.consumer.user.data');
            Route::post('/consumer/checkEmail', 'UserController@checkEmail')->name('backend.consumer.checkemail');
        });

    });
});
