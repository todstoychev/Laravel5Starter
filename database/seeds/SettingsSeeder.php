<?php
use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder {

    public function run() {
        $array = [
            'locales' => 'en, bg',
            'sitename_en' => 'My site',
            'sitename_bg' => 'Моят сайт',
            'fallback_locale' => 'bg',
            'favicon' => 'favicon.ico',
            'show_contacts_page' => true
        ];

        foreach ($array as $key => $item) {
            $settings = Settings::create([
                        'param' => $key,
                        'value' => $item
            ]);

            $settings->save();
        }
    }

}
