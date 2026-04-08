<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Church;
use App\Models\ChurchCommunication;
use App\Models\Parish;
use App\Services\FastmailDraftService;
use App\Support\DanishListFormatter;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(name: 'layouts.admin')]
class OutreachMailPage extends Component
{
    /** @var list<int> */
    public array $selectedChurchIds = [];

    public ?int $parishPickerId = null;

    public string $churchSearch = '';

    public string $recipientFirstName = '';

    public string $sentTo = '';

    public function updatedSelectedChurchIds(): void
    {
        $this->selectedChurchIds = \array_values(
            \array_unique(\array_map(\intval(...), $this->selectedChurchIds)),
        );
    }

    public function addChurch(int $churchId): void
    {
        if (!\in_array($churchId, $this->selectedChurchIds, true)) {
            $this->selectedChurchIds[] = $churchId;
        }
    }

    public function removeChurch(int $churchId): void
    {
        $this->selectedChurchIds = \array_values(\array_diff($this->selectedChurchIds, [$churchId]));
    }

    public function registerCommunication(): void
    {
        $this->validate([
            'selectedChurchIds' => 'required|array|min:1',
            'selectedChurchIds.*' => 'integer|exists:churches,id',
        ], [
            'selectedChurchIds.required' => 'Vælg mindst én kirke.',
            'selectedChurchIds.min' => 'Vælg mindst én kirke.',
        ]);

        $subject = $this->buildSubject();
        $body = $this->buildBody();

        if ($subject === '' || $body === '') {
            return;
        }

        $recipient = \trim($this->sentTo);
        $message = $recipient !== '' ? 'Skrevet til ' . $recipient : $body;

        $count = $this->storeCommunicationEntries($subject, $message);
        $this->dispatch('notify', message: 'Kommunikation registreret for ' . $count . ' ' . ($count === 1 ? 'kirke' : 'kirker') . '.');
    }

    public function createFastmailDraft(): void
    {
        $this->validate([
            'selectedChurchIds' => 'required|array|min:1',
            'selectedChurchIds.*' => 'integer|exists:churches,id',
            'sentTo' => 'required|email',
        ], [
            'selectedChurchIds.required' => 'Vælg mindst én kirke.',
            'selectedChurchIds.min' => 'Vælg mindst én kirke.',
            'sentTo.required' => 'Indtast en modtageradresse.',
            'sentTo.email' => 'Indtast en gyldig e-mailadresse.',
        ]);

        $subject = $this->buildSubject();
        $body = $this->buildBody();

        if ($subject === '' || $body === '') {
            return;
        }

        $recipient = \trim($this->sentTo);

        try {
            $draftId = \app(FastmailDraftService::class)->createDraft($subject, $body, [$recipient]);
        } catch (\Throwable) {
            $this->dispatch('notify', message: 'Kunne ikke oprette kladde i FastMail. Kontrollér opsætning og prøv igen.');

            return;
        }

        $count = $this->storeCommunicationEntries($subject, 'FastMail kladde oprettet til ' . $recipient . ' (id: ' . $draftId . ')');
        $this->dispatch('notify', message: 'FastMail-kladde oprettet og kommunikation gemt for ' . $count . ' ' . ($count === 1 ? 'kirke' : 'kirker') . '.');
    }

    public function render(): \Illuminate\View\View
    {
        $parishes = Parish::query()->orderBy('name')->get(['id', 'name']);

        $parishChurches = $this->parishChurchesForPicker();

        $searchResults = $this->searchChurches();

        $selectedChurches = $this->selectedChurchesCollection();

        $subject = $this->buildSubject();
        $body = $this->buildBody();

        return \view('livewire.admin.outreach-mail-page', [
            'parishes' => $parishes,
            'parishChurches' => $parishChurches,
            'searchResults' => $searchResults,
            'selectedChurches' => $selectedChurches,
            'generatedSubject' => $subject,
            'generatedBody' => $body,
        ]);
    }

    /**
     * @return Collection<int, Church>
     */
    private function selectedChurchesCollection(): Collection
    {
        if ($this->selectedChurchIds === []) {
            return \collect();
        }

        return Church::query()
            ->whereIn('id', $this->selectedChurchIds)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * @return Collection<int, Church>
     */
    private function searchChurches(): Collection
    {
        $term = \trim($this->churchSearch);

        if (\mb_strlen($term) < 2) {
            return \collect();
        }

        $like = '%' . \addcslashes($term, '%_\\') . '%';

        return Church::query()
            ->with([
                'parish:id,name,deanery_id',
                'parish.deanery:id,name',
            ])
            ->where('name', 'like', $like)
            ->orderBy('name')
            ->limit(25)
            ->get(['id', 'name', 'parish_id']);
    }

    private function buildSubject(): string
    {
        $list = $this->churchListFormatted();

        return $list === '' ? '' : 'Fotografering af ' . $list;
    }

    private function buildBody(): string
    {
        $list = $this->churchListFormatted();

        if ($list === '') {
            return '';
        }

        $first = \trim($this->recipientFirstName);
        $greetingLine = $first === '' ? 'Hej,' : 'Hej ' . $first . ',';

        $count = $this->selectedChurchesCollection()->count();
        $churchNoun = $count > 1 ? 'kirker' : 'kirke';

        return \view('mail.outreach-drone-body', [
            'greetingLine' => $greetingLine,
            'churchList' => $list,
            'churchNoun' => $churchNoun,
        ])->render();
    }

    private function churchListFormatted(): string
    {
        $names = \array_values(
            $this->selectedChurchesCollection()
                ->pluck('name')
                ->map(static fn(mixed $name): string => \is_string($name) ? $name : '')
                ->all(),
        );

        return DanishListFormatter::formatDanishAndChurchList($names);
    }

    private function storeCommunicationEntries(string $subject, string $message): int
    {
        foreach ($this->selectedChurchIds as $churchId) {
            ChurchCommunication::create([
                'church_id' => $churchId,
                'subject' => $subject,
                'message' => $message,
                'sent_at' => \now(),
            ]);
        }

        return \count($this->selectedChurchIds);
    }

    /**
     * @return Collection<int, Church>
     */
    private function parishChurchesForPicker(): Collection
    {
        if ($this->parishPickerId === null) {
            return new Collection();
        }

        return Church::query()
            ->where('parish_id', $this->parishPickerId)
            ->orderBy('name')
            ->get(['id', 'name', 'parish_id']);
    }
}
