<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StoreAdminPlanRequest;
use App\Http\Requests\Admin\UpdateAdminPlanRequest;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPlanController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->isAdmin(), 403);

        $familyOrder = ['sup' => 1, 'max' => 2, 'trex' => 3];

        $plans = Plan::query()
            ->get()
            ->sortBy(fn (Plan $plan) => sprintf(
                '%02d-%02d-%05d',
                $familyOrder[$plan->family_slug] ?? 99,
                $plan->duration_months,
                $plan->sort_order ?? 0
            ))
            ->groupBy('family_slug');

        return view('admin.plans.index', [
            'planGroups' => $plans,
            'enabledCount' => Plan::query()->where('is_enabled', true)->count(),
            'totalCount' => Plan::query()->count(),
            'familyLabels' => $this->familyLabels(),
        ]);
    }

    public function store(StoreAdminPlanRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Plan::updateOrCreate(
            [
                'family_slug' => $data['family_slug'],
                'duration_months' => $data['duration_months'],
            ],
            $this->payload($data)
        );

        return redirect()
            ->route('admin.plans.index')
            ->with('status', 'support-plan-saved');
    }

    public function update(UpdateAdminPlanRequest $request, Plan $plan): RedirectResponse
    {
        $plan->update($this->payload($request->validated(), $plan));

        return redirect()
            ->route('admin.plans.index')
            ->with('status', 'support-plan-updated');
    }

    protected function payload(array $data, ?Plan $existing = null): array
    {
        $familySlug = $data['family_slug'];
        $duration = (int) $data['duration_months'];

        return [
            'family' => $this->familyLabels()[$familySlug] ?? strtoupper($familySlug),
            'family_slug' => $familySlug,
            'name' => $data['name'] ?: "{$duration} Months",
            'duration_months' => $duration,
            'price_mad' => $data['price_mad'],
            'features' => $this->parseFeatures($data['features_text'] ?? implode(PHP_EOL, $existing?->features ?? [])),
            'is_featured' => $this->booleanValue($data, 'is_featured', $existing?->is_featured ?? false),
            'is_enabled' => $this->booleanValue($data, 'is_enabled', $existing?->is_enabled ?? false),
            'badge_text' => data_get($data, 'badge_text') ?: null,
            'sort_order' => $data['sort_order'] ?? ($existing?->sort_order ?? 0),
        ];
    }

    protected function parseFeatures(?string $featuresText): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $featuresText))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    protected function booleanValue(array $data, string $key, bool $fallback): bool
    {
        if (array_key_exists($key, $data)) {
            return (bool) $data[$key];
        }

        return $fallback;
    }

    protected function familyLabels(): array
    {
        return [
            'sup' => 'Basic / SUP',
            'max' => 'Advanced / MAX',
            'trex' => 'Premium / TREX',
        ];
    }
}
