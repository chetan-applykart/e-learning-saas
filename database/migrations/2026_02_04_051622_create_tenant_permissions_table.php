<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('tenant_permissions')) {
            return;
        }

        Schema::create('tenant_permissions', function (Blueprint $table) {
            $table->id();

            $table->string('tenant_id');
            $table->unsignedBigInteger('permission_id');

            $table->timestamps();

            $table->unique(['tenant_id', 'permission_id']);
        });
    }

    public function down(): void
    {
        // Safe rollback
        Schema::dropIfExists('tenant_permissions');
    }
};
