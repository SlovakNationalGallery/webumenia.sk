<?php

namespace App\Http\Controllers\Admin\Authority;

use App\Authority;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use League\Csv\Writer;

class RoleTranslationsController extends Controller
{
    public function index()
    {
        $translations = $this->getTranslations();

        $missingCount = $translations->reduce(function (int $carry, $translations) {
            if ($translations->sk === null) {
                $carry++;
            }
            if ($translations->cs === null) {
                $carry++;
            }
            if ($translations->en === null) {
                $carry++;
            }
            return $carry;
        }, 0);

        return view('authorities.role-translations.index', compact('translations', 'missingCount'));
    }

    public function download()
    {
        $csv = Writer::createFromString();
        $csv->insertOne(['id', 'sk', 'cs', 'en']);

        $rows = $this->getTranslations()
            ->map(fn($t) => [$t->id, $t->sk, $t->cs, $t->en])
            ->toArray();

        $csv->insertAll($rows);

        return response($csv->toString())
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename=role_translations.csv');
    }

    private function getTranslations()
    {
        return Authority::query()
            ->select('roles')
            ->whereNotNull('roles')
            ->distinct()
            ->pluck('roles')
            ->reduce(fn(Collection $carry, array $row) => $carry->concat($row), collect())
            ->unique()
            ->sort()
            ->map(function (string $id) {
                return (object) [
                    'id' => $id,
                    'sk' => Lang::hasForLocale("authority.roles.{$id}", 'sk')
                        ? Lang::get("authority.roles.{$id}", [], 'sk')
                        : null,
                    'cs' => Lang::hasForLocale("authority.roles.{$id}", 'cs')
                        ? Lang::get("authority.roles.{$id}", [], 'cs')
                        : null,
                    'en' => Lang::hasForLocale("authority.roles.{$id}", 'en')
                        ? Lang::get("authority.roles.{$id}", [], 'en')
                        : null,
                ];
            });
    }
}
