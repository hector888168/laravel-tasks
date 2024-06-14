<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 檢查表是否已存在
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email');
                $table->string('token');
                $table->timestamp('created_at')->nullable();

                // 檢查是否已經有其他主鍵，然後再設置主鍵
                $existingPrimaryKey = DB::select("SHOW KEYS FROM password_reset_tokens WHERE Key_name = 'PRIMARY'");
                if (empty($existingPrimaryKey)) {
                    $table->primary('email');
                }
            });
        } else {
            // 如果表已存在，檢查並更新結構
            Schema::table('password_reset_tokens', function (Blueprint $table) {
                // 檢查email列是否已經存在，如果不存在則創建
                if (!Schema::hasColumn('password_reset_tokens', 'email')) {
                    $table->string('email');
                }

                // 檢查token列是否已經存在，如果不存在則創建
                if (!Schema::hasColumn('password_reset_tokens', 'token')) {
                    $table->string('token');
                }

                // 檢查created_at列是否已經存在，如果不存在則創建
                if (!Schema::hasColumn('password_reset_tokens', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }

                // 如果表中已經有主鍵且不是email，則跳過設置主鍵的步驟
                $existingPrimaryKey = DB::select("SHOW KEYS FROM password_reset_tokens WHERE Key_name = 'PRIMARY'");
                if (empty($existingPrimaryKey)) {
                    $table->primary('email');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};