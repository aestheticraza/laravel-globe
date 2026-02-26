<?php

namespace Yourname\LaravelGlobe\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Yourname\LaravelGlobe\Repositories\Contracts\CountryRepositoryInterface;
use Yourname\LaravelGlobe\Http\Resources\CountryResource;
use Yourname\LaravelGlobe\Models\State;

class CountryController extends Controller
{
    protected CountryRepositoryInterface $countryRepository;

    public function __construct(CountryRepositoryInterface $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function index(Request $request)
    {
        if ($request->has('search')) {
            $countries = $this->countryRepository->search($request->search);
        } else {
            $countries = $this->countryRepository->getActiveWithStats();
        }

        return CountryResource::collection($countries);
    }

    public function show(string $iso2)
    {
        $country = $this->countryRepository->findByIso(strtoupper($iso2));
        if (!$country) {
            return response()->json(['message' => 'Country not found.'], 404);
        }
        return new CountryResource($country);
    }

    public function states(string $iso2)
    {
        $country = $this->countryRepository->findByIso(strtoupper($iso2));
        if (!$country) {
            return response()->json(['message' => 'Country not found.'], 404);
        }

        return response()->json([
            'data' => State::where('country_id', $country->id)->where('is_active', true)->get()
        ]);
    }
}
