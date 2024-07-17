<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;

class ContentFeedsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('content_feeds')->delete();
        DB::table('content_feeds')->insert([
            [
                'club_id'                       => 12,
                'type'                          => 'Twitter',
                'name'                          => 'twitter',
                'screen_name'                   => 'Mukesh76028992',
                'api_app_id'                    => '16056681',
                'api_key'                       => 'qGNySnqMIpoWvKzAF9yeNf4al',
                'api_secret_key'                => 'YKygadTjcpiFIGgWROs8Mfrfo7rrsaKUnDVucUO0pI7Dwhgoj0  ',
                'api_token'                     => '1082260269529612290-OW1TF637JHeXdzyWa4ui2xmRklr3Jg',
                'api_token_secret_key'          => 'YguhMvdNhoNiq1GK1o0R2mBjlwsAWutZK9092Uo5VN2Ye',
                'api_channel_id'                => null,
                'rss_url'                       => null,
                'last_inserted_data'            => null,
                'is_automatically_publish_items'=> 1,
                'last_imported'                 => null,
                'created_by'                    => 1,
                'created_at'                    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'club_id'                       => 12,
                'type'                          => 'Facebook',
                'name'                          => 'facebook',
                'screen_name'                   => 'Mukesh Tilokani',
                'api_app_id'                    => '2105912742803070',
                'api_key'                       => null,
                'api_secret_key'                => 'da779ba66eb4c07327b8608bb3c1b946',
                'api_token'                     => 'EAAd7UQT2dn4BAIS9IAnZBxXowyeLJFSRjszVWzPqcqihxfNZCIwVmUKVEBKSebkIOfyZBs47F0sD4Kf97PqO7rPNnSza6Ro70kzRlkI3nnVkYd1gKtqbKhH8znfpHAV8nlg8FfNWbMidUPbR7wZAUVKzXnc4m3TDZANT63PaLCpaV0RmEHU5JJT5XywsMGmfYl3fHbBHVFHUvrJq2lTiNyVAYG01kHP6hkzbqrAyZBowZDZD',
                'api_token_secret_key'          => null,
                'api_channel_id'                => null,
                'rss_url'                       => null,
                'last_inserted_data'            => null,
                'is_automatically_publish_items'=> 1,
                'last_imported'                 => null,
                'created_by'                    => 1,
                'created_at'                    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'club_id'                       => 12,
                'type'                          => 'Youtube',
                'name'                          => 'youtube',
                'screen_name'                   => 'Asif Patel',
                'api_app_id'                    => null,
                'api_key'                       => 'AIzaSyAW8PnsC9jc7SL9CHEfqq663QXMAqWVdnw',
                'api_secret_key'                => null,
                'api_token'                     => null,
                'api_token_secret_key'          => null,
                'api_channel_id'                => 'UC5EwPmwVvS8aweB8xoAdRng',
                'rss_url'                       => null,
                'last_inserted_data'            => null,
                'is_automatically_publish_items'=> 1,
                'last_imported'                 => null,
                'created_by'                    => 1,
                'created_at'                    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'club_id'                       => 12,
                'type'                          => 'RSS',
                'name'                          => 'rss',
                'screen_name'                   => null,
                'api_app_id'                    => null,
                'api_key'                       => null,
                'api_secret_key'                => null,
                'api_token'                     => null,
                'api_token_secret_key'          => null,
                'api_channel_id'                => null,
                'rss_url'                       => 'http://www.chexed.com/feeds/MainNewsFeed.xml',
                'last_inserted_data'            => null,
                'is_automatically_publish_items'=> 1,
                'last_imported'                 => null,
                'created_by'                    => 1,
                'created_at'                    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'club_id'                       => 12,
                'type'                          => 'Instagram',
                'name'                          => 'Instagram',
                'screen_name'                   => null,
                'api_app_id'                    => null,
                'api_key'                       => null,
                'api_secret_key'                => null,
                'api_token'                     => '10619317184.1677ed0.ecef68ccff484e52a251ddc3cb5b14a1',
                'api_token_secret_key'          => null,
                'api_channel_id'                => null,
                'rss_url'                       => null,
                'last_inserted_data'            => null,
                'is_automatically_publish_items'=> 1,
                'last_imported'                 => null,
                'created_by'                    => 1,
                'created_at'                    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
