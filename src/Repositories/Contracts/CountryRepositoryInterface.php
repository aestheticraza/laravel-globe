<?php

namespace Yourname\LaravelGlobe\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Yourname\LaravelGlobe\Models\Country;

interface CountryRepositoryInterface
{
    public function findByIso(string $iso2): ?Country;
    public function getActive(): Collection;
    public function search(string $term): Collection;
    public function getActiveWithStats(): Collection;
}
