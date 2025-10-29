<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CommunicationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('church_communications')->delete();

        \DB::table('old.church_communications')->get()->each(function ($communication) {
            \DB::table('church_communications')->insert([
                'id' => $communication->id,
                'church_id' => $communication->church_id,
                'subject' => '',
                'message' => $communication->type,
                'sent_at' => $communication->date,
                'created_at' => $communication->created_at,
                'updated_at' => $communication->updated_at,
                'deleted_at' => $communication->deleted_at,
            ]);
        });
    }
}
