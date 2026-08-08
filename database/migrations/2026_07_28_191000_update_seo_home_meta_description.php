<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Seo;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $seos = Seo::all();
        foreach ($seos as $seo) {
            // Check if it contains the dummy text or is empty
            if (str_contains($seo->home_meta_description, 'long established fact') || empty($seo->home_meta_description)) {
                $seo->home_meta_description = 'Build, launch, and grow your dream online store in minutes with Launchshop. Choose from high-conversion premium templates, secure payment integrations, and lightning-fast web infrastructure.';
                $seo->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No-op
    }
};
