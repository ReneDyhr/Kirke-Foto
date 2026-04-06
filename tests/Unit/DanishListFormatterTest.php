<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Support\DanishListFormatter;
use PHPUnit\Framework\TestCase;

final class DanishListFormatterTest extends TestCase
{
    public function test_empty_returns_empty_string(): void
    {
        $this->assertSame('', DanishListFormatter::formatDanishAndList([]));
    }

    public function test_single_item(): void
    {
        $this->assertSame('Malt Kirke', DanishListFormatter::formatDanishAndList(['Malt Kirke']));
    }

    public function test_two_items_joined_with_og(): void
    {
        $this->assertSame(
            'Holsted Kirke og Sankt Peders Kirke',
            DanishListFormatter::formatDanishAndList(['Holsted Kirke', 'Sankt Peders Kirke']),
        );
    }

    public function test_three_items_use_commas_and_final_og(): void
    {
        $this->assertSame(
            'A Kirke, B Kirke og C Kirke',
            DanishListFormatter::formatDanishAndList(['A Kirke', 'B Kirke', 'C Kirke']),
        );
    }

    public function test_filters_whitespace_and_empty_strings(): void
    {
        $this->assertSame('X', DanishListFormatter::formatDanishAndList(['  X  ', '']));
    }

    public function test_church_list_two_strips_kirke_from_first_only(): void
    {
        $this->assertSame(
            'Holsted og Sankt Peders Kirke',
            DanishListFormatter::formatDanishAndChurchList(['Holsted Kirke', 'Sankt Peders Kirke']),
        );
    }

    public function test_church_list_three_strips_kirke_from_all_but_last(): void
    {
        $this->assertSame(
            'A, B og C Kirke',
            DanishListFormatter::formatDanishAndChurchList(['A Kirke', 'B Kirke', 'C Kirke']),
        );
    }

    public function test_church_list_single_unchanged(): void
    {
        $this->assertSame('Malt Kirke', DanishListFormatter::formatDanishAndChurchList(['Malt Kirke']));
    }

    public function test_church_list_keeps_name_without_kirke_suffix(): void
    {
        $this->assertSame(
            'Holsted og Sankt Peders Kirke',
            DanishListFormatter::formatDanishAndChurchList(['Holsted', 'Sankt Peders Kirke']),
        );
    }
}
