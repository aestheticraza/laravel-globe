<?php

namespace Aestheticraza\LaravelGlobe\Repositories\CacheDecorators;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Aestheticraza\LaravelGlobe\Models\Country;
use Aestheticraza\LaravelGlobe\Repositories\Contracts\CountryRepositoryInterface;

class CacheCountryRepository implements CountryRepositoryInterface
{
    protected CountryRepositoryInterface $repository;

    public function __construct(CountryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function findByIso(string $iso2): ?Country
    {
        return Cache::remember("laravelglobe:country:{$iso2}", 86400, function () use ($iso2) {
            return $this->repository->findByIso($iso2);
        });
    }

    public function getActive(): Collection
    {
        return Cache::remember('laravelglobe:countries:active', 86400, function () {
            return $this->repository->getActive();
        });
    }

    public function search(string $term): Collection
    {
        return $this->repository->search($term); // Don't typically cache open searches
    }

    public function getActiveWithStats(): Collection
    {
        return Cache::remember('laravelglobe:countries:stats', 86400, function () {
            return $this->repository->getActiveWithStats();
        });
    }
}
