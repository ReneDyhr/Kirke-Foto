<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Church;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $baselineLast = Carbon::parse('2023-02-22 10:00:00');

        $churches = Church::query()
            ->whereHas('images', static function (Builder $q): void {
                $q->whereNull('deleted_at');
            })
            ->with('parish:id,url')
            ->with([
                'images' => static function (Relation $q): void {
                    $q->whereNull('deleted_at')->select(['id', 'church_id', 'path', 'updated_at']);
                },
            ])
            ->select(['id', 'parish_id', 'url'])
            ->get();

        $staticPages = [
            [
                'loc' => \url('/'),
                'lastmod' => $baselineLast->toAtomString(),
            ],
            [
                'loc' => \url('/kort'),
                'lastmod' => $baselineLast->toAtomString(),
            ],
            [
                'loc' => \url('/om-os'),
                'lastmod' => $baselineLast->toAtomString(),
            ],
            [
                'loc' => \url('/kontakt'),
                'lastmod' => $baselineLast->toAtomString(),
            ],
        ];

        /** @var array<int, array{loc:string,lastmod:string,images:array<int,string>}> $dynamicPages */
        $dynamicPages = [];

        foreach ($churches as $church) {
            /** @var \Illuminate\Support\Collection<int, \App\Models\ChurchImage> $imagesCollection */
            $imagesCollection = \collect($church->images);

            /** @var null|Carbon $latestImageUpdatedAt */
            $latestImageUpdatedAt = $imagesCollection->max('updated_at');
            $lastmod = $baselineLast;

            if ($latestImageUpdatedAt instanceof Carbon) {
                $lastmod = $latestImageUpdatedAt->greaterThan($baselineLast) ? $latestImageUpdatedAt : $baselineLast;
            }

            $images = [];

            foreach ($imagesCollection as $image) {
                /** @var \App\Models\ChurchImage $image */
                $images[] = \url('/images/church/' . \ltrim($image->path, '/'));
            }

            $dynamicPages[] = [
                'loc' => \url('/kirke/' . $church->parish->url . '/' . $church->url),
                'lastmod' => $lastmod->toAtomString(),
                'images' => $images,
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        foreach ($staticPages as $page) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . \e($page['loc']) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . \e($page['lastmod']) . '</lastmod>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        foreach ($dynamicPages as $page) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . \e($page['loc']) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . \e($page['lastmod']) . '</lastmod>' . "\n";

            foreach ($page['images'] as $img) {
                $xml .= '    <image:image>' . "\n";
                $xml .= '      <image:loc>' . \e($img) . '</image:loc>' . "\n";
                $xml .= '    </image:image>' . "\n";
            }
            $xml .= '  </url>' . "\n";
        }
        $xml .= '</urlset>';

        return new Response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }
}
