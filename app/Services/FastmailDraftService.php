<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FastmailDraftService
{
    /**
     * @param array<int, string> $toEmails
     */
    public function createDraft(string $subject, string $body, array $toEmails): string
    {
        $token = $this->stringConfig('services.fastmail.token');
        $sessionUrl = $this->stringConfig('services.fastmail.session_url');
        $apiUrl = $this->stringConfig('services.fastmail.api_url');
        $fromEmail = $this->stringConfig('services.fastmail.from_email');

        if ($token === '' || $sessionUrl === '' || $apiUrl === '' || $fromEmail === '') {
            throw new \RuntimeException('FastMail is not configured.');
        }

        $accountId = $this->resolvePrimaryMailAccountId($sessionUrl, $token);
        $draftsMailboxId = $this->resolveDraftsMailboxId($apiUrl, $token, $accountId);

        $to = \array_values(\array_map(
            static fn(string $email): array => ['email' => $email],
            $toEmails,
        ));

        $requestBody = [
            'using' => ['urn:ietf:params:jmap:core', 'urn:ietf:params:jmap:mail'],
            'methodCalls' => [[
                'Email/set',
                [
                    'accountId' => $accountId,
                    'create' => [
                        'draft' => [
                            'mailboxIds' => [$draftsMailboxId => true],
                            'keywords' => ['$draft' => true],
                            'from' => [['email' => $fromEmail]],
                            'to' => $to,
                            'subject' => $subject,
                            'bodyValues' => [
                                'body' => [
                                    'charset' => 'utf-8',
                                    'value' => $body,
                                ],
                            ],
                            'textBody' => [[
                                'partId' => 'body',
                                'type' => 'text/plain',
                            ]],
                        ],
                    ],
                ],
                'a',
            ]],
        ];

        $response = Http::acceptJson()
            ->asJson()
            ->withToken($token)
            ->post($apiUrl, $requestBody);

        if (!$response->successful()) {
            throw new \RuntimeException('FastMail rejected draft creation.');
        }

        $json = $response->json();

        if (!\is_array($json)) {
            throw new \RuntimeException('FastMail returned an invalid draft response.');
        }

        $methodResponses = $json['methodResponses'] ?? null;

        if (!\is_array($methodResponses)) {
            throw new \RuntimeException('FastMail draft response was missing method data.');
        }

        $firstInvocation = $methodResponses[0] ?? null;

        if (!\is_array($firstInvocation)) {
            throw new \RuntimeException('FastMail draft response was missing method data.');
        }

        $setResult = $firstInvocation[1] ?? null;

        if (!\is_array($setResult)) {
            throw new \RuntimeException('FastMail draft response was missing method data.');
        }
        $created = $setResult['created'] ?? null;

        if (!\is_array($created) || !isset($created['draft']) || !\is_array($created['draft'])) {
            throw new \RuntimeException('FastMail did not return a created draft.');
        }

        $draftId = $created['draft']['id'] ?? null;

        return \is_string($draftId) && $draftId !== ''
            ? $draftId
            : throw new \RuntimeException('FastMail did not return a draft id.');
    }

    private function resolvePrimaryMailAccountId(string $sessionUrl, string $token): string
    {
        $response = Http::acceptJson()
            ->withToken($token)
            ->get($sessionUrl);

        if (!$response->successful()) {
            throw new \RuntimeException('Could not authenticate with FastMail session endpoint.');
        }

        $json = $response->json();

        if (!\is_array($json)) {
            throw new \RuntimeException('FastMail session response was invalid.');
        }

        $primaryAccounts = $json['primaryAccounts'] ?? null;

        if (!\is_array($primaryAccounts)) {
            throw new \RuntimeException('FastMail session did not include primary accounts.');
        }

        $mailCapability = 'urn:ietf:params:jmap:mail';
        $accountId = $primaryAccounts[$mailCapability] ?? null;

        return \is_string($accountId) && $accountId !== ''
            ? $accountId
            : throw new \RuntimeException('FastMail mail account id could not be resolved.');
    }

    private function resolveDraftsMailboxId(string $apiUrl, string $token, string $accountId): string
    {
        $response = Http::acceptJson()
            ->asJson()
            ->withToken($token)
            ->post($apiUrl, [
                'using' => ['urn:ietf:params:jmap:core', 'urn:ietf:params:jmap:mail'],
                'methodCalls' => [[
                    'Mailbox/query',
                    [
                        'accountId' => $accountId,
                        'filter' => ['role' => 'drafts'],
                    ],
                    'a',
                ]],
            ]);

        if (!$response->successful()) {
            throw new \RuntimeException('Could not query FastMail mailboxes.');
        }

        $json = $response->json();

        if (!\is_array($json)) {
            throw new \RuntimeException('FastMail mailbox response was invalid.');
        }

        $methodResponses = $json['methodResponses'] ?? null;

        if (!\is_array($methodResponses)) {
            throw new \RuntimeException('FastMail mailbox query response was missing method data.');
        }

        $firstInvocation = $methodResponses[0] ?? null;

        if (!\is_array($firstInvocation)) {
            throw new \RuntimeException('FastMail mailbox query response was missing method data.');
        }

        $queryResult = $firstInvocation[1] ?? null;

        if (!\is_array($queryResult)) {
            throw new \RuntimeException('FastMail mailbox query response was missing method data.');
        }
        $ids = $queryResult['ids'] ?? null;

        if (!\is_array($ids) || !isset($ids[0]) || !\is_string($ids[0]) || $ids[0] === '') {
            throw new \RuntimeException('FastMail drafts mailbox was not found.');
        }

        return $ids[0];
    }

    private function stringConfig(string $key): string
    {
        $value = \config($key);

        return \is_string($value) ? \trim($value) : '';
    }
}
