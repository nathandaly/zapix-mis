<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contact_details', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('email, phone, linkedin, etc.');
            $table->string('label')->nullable()->index();
            $table->string('value')->index()->comment('Can hold email, phone, linkedin, etc.');
            $table->morphs('contactable');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_details');
    }
};
