<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Menyelaraskan tabel `users` (yang masih berbentuk `name` tunggal warisan
 * skema lama) ke skema yang diniatkan model & AuthController:
 * username + first_name + last_name + institution.
 *
 * Backfill dari kolom `name`, lalu kolom `name` dihapus (model menyajikan
 * `name` sebagai atribut virtual dari first_name + last_name).
 */
return new class extends Migration
{
    public function up(): void
    {
        // Pada instalasi baru, create_users_table sudah membuat skema final
        // (username unique + first_name/last_name/institution). Migrasi ini
        // hanya relevan untuk DB LAMA yang masih punya kolom `name` tunggal —
        // tanpa guard ini, ia akan mencoba menambah unique('username') yang
        // sudah ada → error "Duplicate key name 'users_username_unique'".
        if (! Schema::hasColumn('users', 'name')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->after('id');
            }
            if (! Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('username');
            }
            if (! Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (! Schema::hasColumn('users', 'institution')) {
                $table->string('institution')->nullable()->after('email');
            }
        });

        // Backfill first_name/last_name dari `name`, dan username unik dari email.
        if (Schema::hasColumn('users', 'name')) {
            foreach (DB::table('users')->get() as $u) {
                $parts = preg_split('/\s+/', trim((string) ($u->name ?? '')), 2);
                $first = ($parts[0] ?? '') !== '' ? $parts[0] : 'User';
                $last  = $parts[1] ?? '';

                $username = $u->username ?? null;
                if (! $username) {
                    $base = Str::slug(explode('@', (string) $u->email)[0], '_') ?: 'user';
                    $username = $base;
                    $i = 1;
                    while (DB::table('users')->where('username', $username)
                            ->where('id', '<>', $u->id)->exists()) {
                        $username = $base.$i++;
                    }
                }

                DB::table('users')->where('id', $u->id)->update([
                    'first_name' => $first,
                    'last_name'  => $last,
                    'username'   => $username,
                ]);
            }
        }

        // Kunci username (unik, wajib) dan first_name wajib; lalu buang `name`.
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable(false)->change();
            $table->string('first_name')->nullable(false)->change();
            $table->unique('username');
        });

        if (Schema::hasColumn('users', 'name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
        });

        if (Schema::hasColumn('users', 'first_name')) {
            foreach (DB::table('users')->get() as $u) {
                DB::table('users')->where('id', $u->id)->update([
                    'name' => trim(($u->first_name ?? '').' '.($u->last_name ?? '')),
                ]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn(['username', 'first_name', 'last_name', 'institution']);
        });
    }
};
