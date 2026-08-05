<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * A batch of "add_foreign_keys_to_X_table" migrations across this project
     * each accidentally defined every foreign key twice (once as
     * "{table}_{column}_fkey" and again as "{table}_{column}_fkey1"), both
     * pointing at the same column/reference. This drops every redundant
     * "_fkey1" duplicate in one pass, keeping the original "_fkey" constraint.
     *
     * Safe by construction: it only drops a "_fkey1" constraint when a
     * matching "_fkey" constraint (same table, same columns, same
     * referenced table) still exists — so it can never accidentally remove
     * the only copy of a real constraint.
     */
    public function up(): void
    {
        $duplicates = DB::select(<<<'SQL'
            SELECT
                c1.conname AS duplicate_name,
                c1.conrelid::regclass::text AS table_name
            FROM pg_constraint c1
            JOIN pg_constraint c2
                ON c1.conrelid = c2.conrelid
                AND c1.confrelid = c2.confrelid
                AND c1.conkey = c2.conkey
                AND c1.confkey = c2.confkey
                AND c1.contype = 'f'
                AND c2.contype = 'f'
                AND c1.conname <> c2.conname
                AND c1.conname = c2.conname || '1'
            WHERE c1.contype = 'f'
        SQL);

        foreach ($duplicates as $row) {
            DB::statement(sprintf(
                'ALTER TABLE %s DROP CONSTRAINT IF EXISTS %s',
                $row->table_name,
                $row->duplicate_name
            ));
        }
    }

    /**
     * Reverse the migrations.
     *
     * Not reversible in a meaningful way — these were exact duplicates with
     * no independent purpose, so recreating them would just reintroduce the
     * clutter this migration removes. Down is intentionally a no-op.
     */
    public function down(): void
    {
        // Intentionally left blank — see class docblock.
    }
};