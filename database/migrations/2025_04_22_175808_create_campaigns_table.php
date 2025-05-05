<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->enum('status', ['active', 'paused', 'deleted'])->default('paused');
            $table->string('ad_title');
            $table->string('ad_description');
            $table->enum('device', ['all', 'desktop', 'mobile']);
            $table->enum('ad_format', ['display', 'video']);
            $table->enum('ad_type', ['static_banner', 'video_banner']);
            $table->integer('ad_width');
            $table->integer('ad_height');
            $table->enum('ad_category', ['ristoranti', 'tecnologia', 'immobiliare', 'bar', 'aziende', 'supermercati', 'scuole', 'negozi', 'intrattenimento', 'altro']);
            $table->string('geo_targeting')->nullable();
            $table->string('isp_targeting')->nullable();
            $table->enum('os_targeting', ['android', 'ios', 'windows', 'mac', 'linux', 'all']);
            $table->enum('browser_targeting', ['chrome', 'firefox', 'safari', 'opera', 'edge', 'all']);
            $table->string('browser_language_targeting')->nullable();
            $table->text('keyword_targeting')->nullable()->array();
            $table->decimal('max_bid', 10, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('daily_budget', 10, 2)->nullable();
            $table->string('target_url');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('creative_id')->nullable()->constrained('creatives')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
