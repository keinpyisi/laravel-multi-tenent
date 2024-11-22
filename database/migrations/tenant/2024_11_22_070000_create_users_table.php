<?php

use App\Models\Base\Tenant;
use App\Models\Tenant\MstUserAuth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('login_id');
            $table->string('email')->unique();
            $table->string('user_name');
            $table->string('password');
            $table->foreignIdFor(MstUserAuth::class)->default('0');
            $table->foreignIdFor(Tenant::class)->default('0');
            $table->string('note')->nullable();
            $table->boolean('del_flag')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('users');
    }
};
