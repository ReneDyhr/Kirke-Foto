<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Admin\OutreachMailPage;
use App\Models\ChurchCommunication;
use App\Services\FastmailDraftService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

final class OutreachMailPageFastmailDraftTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_fastmail_draft_and_logs_communication(): void
    {
        $churchId = $this->createChurchWithRelations('Malt Kirke');

        \app()->instance(FastmailDraftService::class, new class extends FastmailDraftService {
            /**
             * @param array<int, string> $toEmails
             */
            public function createDraft(string $subject, string $body, array $toEmails): string
            {
                return 'draft-abc';
            }
        });

        Livewire::test(OutreachMailPage::class)
            ->set('selectedChurchIds', [$churchId])
            ->set('sentTo', 'kontakt@sogn.dk')
            ->call('createFastmailDraft');

        $communication = ChurchCommunication::query()->first();
        $this->assertNotNull($communication);
        $this->assertSame($churchId, $communication->church_id);
        $this->assertStringContainsString('FastMail kladde oprettet til kontakt@sogn.dk', $communication->message);
        $this->assertStringContainsString('(id: draft-abc)', $communication->message);
    }

    public function test_it_validates_recipient_email_before_draft_creation(): void
    {
        $churchId = $this->createChurchWithRelations('Malt Kirke');

        Livewire::test(OutreachMailPage::class)
            ->set('selectedChurchIds', [$churchId])
            ->set('sentTo', 'ikke-en-mail')
            ->call('createFastmailDraft')
            ->assertHasErrors(['sentTo' => ['email']]);

        $this->assertSame(0, ChurchCommunication::query()->count());
    }

    public function test_it_does_not_log_when_fastmail_creation_fails(): void
    {
        $churchId = $this->createChurchWithRelations('Malt Kirke');

        \app()->instance(FastmailDraftService::class, new class extends FastmailDraftService {
            /**
             * @param array<int, string> $toEmails
             */
            public function createDraft(string $subject, string $body, array $toEmails): string
            {
                throw new \RuntimeException('failure');
            }
        });

        Livewire::test(OutreachMailPage::class)
            ->set('selectedChurchIds', [$churchId])
            ->set('sentTo', 'kontakt@sogn.dk')
            ->call('createFastmailDraft');

        $this->assertSame(0, ChurchCommunication::query()->count());
    }

    private function createChurchWithRelations(string $churchName): int
    {
        $now = \now();

        $dioceseId = DB::table('dioceses')->insertGetId([
            'name' => 'Ribe Stift',
            'description' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $deaneryId = DB::table('deaneries')->insertGetId([
            'diocese_id' => $dioceseId,
            'name' => 'Malt Provsti',
            'description' => null,
            'placemark' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $parishId = DB::table('parishes')->insertGetId([
            'deanery_id' => $deaneryId,
            'url' => 'malt-sogn',
            'name' => 'Malt Sogn',
            'description' => null,
            'placemark' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return (int) DB::table('churches')->insertGetId([
            'url' => 'malt-kirke',
            'name' => $churchName,
            'description' => 'Test church',
            'seo_description' => 'SEO',
            'seo_tags' => 'tag',
            'latitude' => 55.467,
            'longitude' => 8.451,
            'parish_id' => $parishId,
            'drone_approval' => 1,
            'open_area' => 0,
            'contact_later' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
