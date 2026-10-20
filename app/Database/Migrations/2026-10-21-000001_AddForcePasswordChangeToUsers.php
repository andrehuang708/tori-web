<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForcePasswordChangeToUsers extends Migration
{
    public function up()
    {
        // NOTE: implemented as raw SQL because Forge::addColumn() was a
        // silent no-op in this environment (verified via migrate/rollback).
        $this->db->query(
            'ALTER TABLE `users` ADD COLUMN `force_password_change` TINYINT(1) NOT NULL DEFAULT 0 ' .
            "COMMENT '1 = must set new password after admin reset'"
        );
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `users` DROP COLUMN `force_password_change`');
    }
}
