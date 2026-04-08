<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\FastmailDraftService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

final class FastmailDraftServiceTest extends TestCase
{
    public function test_it_creates_draft_and_returns_id(): void
    {
        \config()->set('services.fastmail.token', 'token-123');
        \config()->set('services.fastmail.session_url', 'https://api.fastmail.com/jmap/session');
        \config()->set('services.fastmail.api_url', 'https://api.fastmail.com/jmap/api');
        \config()->set('services.fastmail.from_email', 'noreply@example.com');

        Http::fake([
            'https://api.fastmail.com/jmap/session' => Http::response([
                'primaryAccounts' => [
                    'urn:ietf:params:jmap:mail' => 'account-1',
                ],
            ]),
            'https://api.fastmail.com/jmap/api' => Http::sequence()
                ->push([
                    'methodResponses' => [
                        ['Mailbox/query', ['ids' => ['mailbox-drafts']], 'a'],
                    ],
                ])
                ->push([
                    'methodResponses' => [
                        ['Email/set', ['created' => ['draft' => ['id' => 'draft-42']]], 'a'],
                    ],
                ]),
        ]);

        $draftId = \app(FastmailDraftService::class)->createDraft(
            'Subject line',
            'Body line',
            ['user@example.com'],
        );

        $this->assertSame('draft-42', $draftId);
    }

    public function test_it_throws_when_fastmail_is_not_configured(): void
    {
        \config()->set('services.fastmail.token', '');
        \config()->set('services.fastmail.session_url', 'https://api.fastmail.com/jmap/session');
        \config()->set('services.fastmail.api_url', 'https://api.fastmail.com/jmap/api');
        \config()->set('services.fastmail.from_email', 'noreply@example.com');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('FastMail is not configured.');

        \app(FastmailDraftService::class)->createDraft(
            'Subject line',
            'Body line',
            ['user@example.com'],
        );
    }
}
