<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (\Qubiqx\QcommercePages\Models\Page::get() as $page) {
            $content = [];
            foreach (\Qubiqx\QcommerceCore\Classes\Locales::getLocales() as $locale) {
                $content['title'][$locale['id']] = $page->getTranslation('meta_title', $locale['id']);
                $content['description'][$locale['id']] = $page->getTranslation('meta_description', $locale['id']);
                $content['image'][$locale['id']] = $page->getTranslation('meta_image', $locale['id']);
            }
            $page->metadata()->updateOrCreate([], $content);
        }

        Schema::table('qcommerce__pages', function (Blueprint $table) {
            $table->dropColumn('meta_title');
            $table->dropColumn('meta_description');
            $table->dropColumn('meta_image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};