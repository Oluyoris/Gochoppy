<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableNames = config('permission.table_names', [
            'roles'                 => 'roles',
            'permissions'           => 'permissions',
            'model_has_permissions' => 'model_has_permissions',
            'model_has_roles'       => 'model_has_roles',
            'role_has_permissions'  => 'role_has_permissions',
        ]);

        $teams = config('permission.teams', false);

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $teams) {
            $table->unsignedBigInteger('permission_id');

            if ($teams) {
                $table->unsignedBigInteger('team_id')->nullable();
            }

            $table->unsignedBigInteger('model_id');
            $table->string('model_type');

            $table->foreign('permission_id')
                  ->references('id')
                  ->on($tableNames['permissions'])
                  ->onDelete('cascade');

            $primary = ['model_id', 'model_type', 'permission_id'];
            if ($teams) {
                $primary[] = 'team_id';
            }
            $table->primary($primary);

            $table->index('permission_id');
            if ($teams) {
                $table->index('team_id');
            }
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $teams) {
            $table->unsignedBigInteger('role_id');

            if ($teams) {
                $table->unsignedBigInteger('team_id')->nullable();
            }

            $table->unsignedBigInteger('model_id');
            $table->string('model_type');

            $table->foreign('role_id')
                  ->references('id')
                  ->on($tableNames['roles'])
                  ->onDelete('cascade');

            $primary = ['model_id', 'model_type', 'role_id'];
            if ($teams) {
                $primary[] = 'team_id';
            }
            $table->primary($primary);

            $table->index('role_id');
            if ($teams) {
                $table->index('team_id');
            }
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('permission_id')
                  ->references('id')
                  ->on($tableNames['permissions'])
                  ->onDelete('cascade');

            $table->foreign('role_id')
                  ->references('id')
                  ->on($tableNames['roles'])
                  ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');

        Schema::dropIfExists($tableNames['role_has_permissions']);
        Schema::dropIfExists($tableNames['model_has_roles']);
        Schema::dropIfExists($tableNames['model_has_permissions']);
        Schema::dropIfExists($tableNames['roles']);
        Schema::dropIfExists($tableNames['permissions']);
    }
};