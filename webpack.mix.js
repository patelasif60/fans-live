const mix = require('laravel-mix');
require('laravel-mix-purgecss');
if (!mix.inProduction()) {
	mix.webpackConfig({
		devtool: 'source-map'
	})
	.sourceMaps();
}

if (mix.inProduction()) {
	mix.version();
}

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.sass('resources/sass/backend/main.scss', 'public/css/backend')
	.sass('resources/sass/frontend/main.scss', 'public/css/frontend')
	.options({
		processCssUrls: false,
		postCss: [
			require('autoprefixer')({
				browsers: [
					"> 1%",
					"last 40 versions",
					"IE 10"
				],
				cascade: false
			})
		]
	});

mix.scripts([
	'resources/js/backend/core/jquery.min.js',
	'resources/js/backend/core/bootstrap.bundle.min.js',
	'resources/js/backend/core/jquery.slimscroll.min.js',
	'resources/js/backend/core/jquery-scrollLock.min.js',
	'resources/js/backend/core/jquery.appear.min.js',
	'resources/js/backend/core/jquery.countTo.min.js',
	'resources/js/backend/core/js.cookie.min.js',
	'resources/js/backend/codebase.js'
], 'public/js/backend/core.js');

mix.js('resources/js/backend/pages/users/change_password.js', 'public/js/backend/pages/users/change_password.js');
mix.js('resources/js/backend/pages/users/cms/index.js', 'public/js/backend/pages/users/cms/index.js');
mix.js('resources/js/backend/pages/users/cms/create.js', 'public/js/backend/pages/users/cms/create.js');
mix.js('resources/js/backend/pages/users/cms/edit.js', 'public/js/backend/pages/users/cms/edit.js');
mix.js('resources/js/backend/pages/users/cms/set_password.js', 'public/js/backend/pages/users/cms/set_password.js');
mix.js('resources/js/backend/pages/users/consumer/index.js', 'public/js/backend/pages/users/consumer/index.js');
mix.js('resources/js/backend/pages/users/consumer/create.js', 'public/js/backend/pages/users/consumer/create.js');
mix.js('resources/js/backend/pages/users/consumer/edit.js', 'public/js/backend/pages/users/consumer/edit.js');
mix.js('resources/js/backend/pages/users/staff/index.js', 'public/js/backend/pages/users/staff/index.js');
mix.js('resources/js/backend/pages/users/staff/create.js', 'public/js/backend/pages/users/staff/create.js');
mix.js('resources/js/backend/pages/users/staff/edit.js', 'public/js/backend/pages/users/staff/edit.js');
mix.js('resources/js/backend/pages/clubcategories/index.js', 'public/js/backend/pages/clubcategories/index.js');
mix.js('resources/js/backend/pages/clubcategories/create.js', 'public/js/backend/pages/clubcategories/create.js');
mix.js('resources/js/backend/pages/clubcategories/edit.js', 'public/js/backend/pages/clubcategories/edit.js');
mix.js('resources/js/backend/pages/clubs/index.js', 'public/js/backend/pages/clubs/index.js');
mix.js('resources/js/backend/pages/clubs/create.js', 'public/js/backend/pages/clubs/create.js');
mix.js('resources/js/backend/pages/clubs/edit.js', 'public/js/backend/pages/clubs/edit.js');
mix.js('resources/js/backend/pages/competitions/index.js', 'public/js/backend/pages/competitions/index.js');
mix.js('resources/js/backend/pages/competitions/create.js', 'public/js/backend/pages/competitions/create.js');
mix.js('resources/js/backend/pages/competitions/edit.js', 'public/js/backend/pages/competitions/edit.js');
mix.js('resources/js/backend/pages/users/role/index.js', 'public/js/backend/pages/users/role/index.js');
mix.js('resources/js/backend/pages/users/role/create.js', 'public/js/backend/pages/users/role/create.js');
mix.js('resources/js/backend/pages/users/role/edit.js', 'public/js/backend/pages/users/role/edit.js');
mix.js('resources/js/backend/pages/contentfeeds/index.js', 'public/js/backend/pages/contentfeeds/index.js');
mix.js('resources/js/backend/pages/contentfeeds/create.js', 'public/js/backend/pages/contentfeeds/create.js');
mix.js('resources/js/backend/pages/contentfeeds/edit.js', 'public/js/backend/pages/contentfeeds/edit.js');
mix.js('resources/js/backend/pages/feeditems/index.js', 'public/js/backend/pages/feeditems/index.js');
mix.js('resources/js/backend/pages/feeditems/detail.js', 'public/js/backend/pages/feeditems/detail.js');
mix.js('resources/js/backend/pages/news/index.js', 'public/js/backend/pages/news/index.js');
mix.js('resources/js/backend/pages/news/create.js', 'public/js/backend/pages/news/create.js');
mix.js('resources/js/backend/pages/news/edit.js', 'public/js/backend/pages/news/edit.js');
mix.js('resources/js/backend/pages/polls/index.js', 'public/js/backend/pages/polls/index.js');
mix.js('resources/js/backend/pages/polls/create.js', 'public/js/backend/pages/polls/create.js');
mix.js('resources/js/backend/pages/polls/edit.js', 'public/js/backend/pages/polls/edit.js');
mix.js('resources/js/backend/pages/ctas/index.js', 'public/js/backend/pages/ctas/index.js');
mix.js('resources/js/backend/pages/ctas/create.js', 'public/js/backend/pages/ctas/create.js');
mix.js('resources/js/backend/pages/ctas/edit.js', 'public/js/backend/pages/ctas/edit.js');
mix.js('resources/js/backend/pages/travelinformationpages/index.js', 'public/js/backend/pages/travelinformationpages/index.js');
mix.js('resources/js/backend/pages/travelinformationpages/create.js', 'public/js/backend/pages/travelinformationpages/create.js');
mix.js('resources/js/backend/pages/travelinformationpages/edit.js', 'public/js/backend/pages/travelinformationpages/edit.js');
mix.js('resources/js/backend/pages/traveloffers/index.js', 'public/js/backend/pages/traveloffers/index.js');
mix.js('resources/js/backend/pages/traveloffers/create.js', 'public/js/backend/pages/traveloffers/create.js');
mix.js('resources/js/backend/pages/traveloffers/edit.js', 'public/js/backend/pages/traveloffers/edit.js');
mix.js('resources/js/backend/pages/hospitalitysuites/index.js', 'public/js/backend/pages/hospitalitysuites/index.js');
mix.js('resources/js/backend/pages/hospitalitysuites/create.js', 'public/js/backend/pages/hospitalitysuites/create.js');
mix.js('resources/js/backend/pages/hospitalitysuites/edit.js', 'public/js/backend/pages/hospitalitysuites/edit.js');
mix.js('resources/js/backend/pages/stadiumgeneralsettings/edit.js', 'public/js/backend/pages/stadiumgeneralsettings/edit.js');
mix.js('resources/js/backend/pages/pricingbands/index.js', 'public/js/backend/pages/pricingbands/index.js');
mix.js('resources/js/backend/pages/pricingbands/create.js', 'public/js/backend/pages/pricingbands/create.js');
mix.js('resources/js/backend/pages/pricingbands/edit.js', 'public/js/backend/pages/pricingbands/edit.js');
mix.js('resources/js/backend/pages/stadiumentrances/edit.js', 'public/js/backend/pages/stadiumentrances/edit.js');
mix.js('resources/js/backend/pages/stadiumblocks/index.js', 'public/js/backend/pages/stadiumblocks/index.js');
mix.js('resources/js/backend/pages/stadiumblocks/create.js', 'public/js/backend/pages/stadiumblocks/create.js');
mix.js('resources/js/backend/pages/stadiumblocks/edit.js', 'public/js/backend/pages/stadiumblocks/edit.js');
mix.js('resources/js/backend/pages/membershippackages/index.js', 'public/js/backend/pages/membershippackages/index.js');
mix.js('resources/js/backend/pages/membershippackages/create.js', 'public/js/backend/pages/membershippackages/create.js');
mix.js('resources/js/backend/pages/membershippackages/edit.js', 'public/js/backend/pages/membershippackages/edit.js');
mix.js('resources/js/backend/pages/matches/index.js', 'public/js/backend/pages/matches/index.js');
mix.js('resources/js/backend/pages/matches/create.js', 'public/js/backend/pages/matches/create.js');
mix.js('resources/js/backend/pages/matches/edit.js', 'public/js/backend/pages/matches/edit.js');
mix.js('resources/js/backend/common.js', 'public/js/backend/common.js');
mix.js('resources/js/backend/googlemap.js', 'public/js/backend/googlemap.js');
mix.js('resources/js/backend/pages/events/index.js', 'public/js/backend/pages/events/index.js');
mix.js('resources/js/backend/pages/events/create.js', 'public/js/backend/pages/events/create.js');
mix.js('resources/js/backend/pages/events/edit.js', 'public/js/backend/pages/events/edit.js');
mix.js('resources/js/backend/pages/categories/index.js', 'public/js/backend/pages/categories/index.js');
mix.js('resources/js/backend/pages/categories/create.js', 'public/js/backend/pages/categories/create.js');
mix.js('resources/js/backend/pages/categories/edit.js', 'public/js/backend/pages/categories/edit.js');
mix.js('resources/js/backend/pages/products/index.js', 'public/js/backend/pages/products/index.js');
mix.js('resources/js/backend/pages/products/create.js', 'public/js/backend/pages/products/create.js');
mix.js('resources/js/backend/pages/products/edit.js', 'public/js/backend/pages/products/edit.js');
mix.js('resources/js/backend/pages/collectionpoints/index.js', 'public/js/backend/pages/collectionpoints/index.js');
mix.js('resources/js/backend/pages/collectionpoints/create.js', 'public/js/backend/pages/collectionpoints/create.js');
mix.js('resources/js/backend/pages/collectionpoints/edit.js', 'public/js/backend/pages/collectionpoints/edit.js');
mix.js('resources/js/backend/pages/clubinformationpages/index.js', 'public/js/backend/pages/clubinformationpages/index.js');
mix.js('resources/js/backend/pages/clubinformationpages/create.js', 'public/js/backend/pages/clubinformationpages/create.js');
mix.js('resources/js/backend/pages/clubinformationpages/edit.js', 'public/js/backend/pages/clubinformationpages/edit.js');
mix.js('resources/js/backend/pages/clubpagesettings/edit.js', 'public/js/backend/pages/clubpagesettings/edit.js');
mix.js('resources/js/backend/pages/travelwarnings/index.js', 'public/js/backend/pages/travelwarnings/index.js');
mix.js('resources/js/backend/pages/travelwarnings/create.js', 'public/js/backend/pages/travelwarnings/create.js');
mix.js('resources/js/backend/pages/travelwarnings/edit.js', 'public/js/backend/pages/travelwarnings/edit.js');
mix.js('resources/js/backend/pages/loyaltyrewards/index.js', 'public/js/backend/pages/loyaltyrewards/index.js');
mix.js('resources/js/backend/pages/loyaltyrewards/create.js', 'public/js/backend/pages/loyaltyrewards/create.js');
mix.js('resources/js/backend/pages/loyaltyrewards/edit.js', 'public/js/backend/pages/loyaltyrewards/edit.js');
mix.js('resources/js/backend/pages/specialoffer/index.js', 'public/js/backend/pages/specialoffer/index.js');
mix.js('resources/js/backend/pages/specialoffer/create.js', 'public/js/backend/pages/specialoffer/create.js');
mix.js('resources/js/backend/pages/specialoffer/edit.js', 'public/js/backend/pages/specialoffer/edit.js');
mix.js('resources/js/backend/pages/pushnotifications/index.js', 'public/js/backend/pages/pushnotifications/index.js');
mix.js('resources/js/backend/pages/pushnotifications/create.js', 'public/js/backend/pages/pushnotifications/create.js');
mix.js('resources/js/backend/pages/pushnotifications/edit.js', 'public/js/backend/pages/pushnotifications/edit.js');
mix.js('resources/js/backend/pages/videos/index.js', 'public/js/backend/pages/videos/index.js');
mix.js('resources/js/backend/pages/videos/create.js', 'public/js/backend/pages/videos/create.js');
mix.js('resources/js/backend/pages/videos/edit.js', 'public/js/backend/pages/videos/edit.js');
mix.js('resources/js/backend/pages/quizzes/index.js', 'public/js/backend/pages/quizzes/index.js');
mix.js('resources/js/backend/pages/quizzes/create.js', 'public/js/backend/pages/quizzes/create.js');
mix.js('resources/js/backend/pages/quizzes/edit.js', 'public/js/backend/pages/quizzes/edit.js');
mix.js('resources/js/backend/pages/transactions/index.js', 'public/js/backend/pages/transactions/index.js');
mix.js('resources/js/backend/pages/settings/settings.js', 'public/js/backend/pages/settings/settings.js');
mix.js('resources/js/backend/pages/transactions/review.js', 'public/js/backend/pages/transactions/review.js');
mix.js('resources/js/frontend/common.js', 'public/js/frontend/common.js');
mix.js('resources/js/frontend/pages/matchticketbooking/pickblock.js', 'public/js/frontend/pages/matchticketbooking/pickblock.js');
mix.js('resources/js/frontend/pages/payment/payment.js', 'public/js/frontend/pages/payment/payment.js');

mix.copyDirectory('resources/js/backend/plugins', 'public/plugins');
mix.copyDirectory('node_modules/@fortawesome/fontawesome-pro/webfonts', 'public/fonts/fontawesome5-pro');


