<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Site;
use Illuminate\Support\Facades\Route;

// ── Language ──────────────────────────────────────────────────
Route::get('/lang/{locale}', function (string $locale) {
    if (array_key_exists($locale, config('olr.locales', []))) {
        session(['locale' => $locale]);
        cookie()->queue('locale', $locale, 60 * 24 * 365);
    }
    return back();
})->name('lang.switch');

// ── Public ────────────────────────────────────────────────────
Route::get('/', Site\HomeController::class)->name('home');
Route::get('/results', [Site\FlightController::class, 'index'])->name('flights.index');
Route::get('/results/{flight}', [Site\FlightController::class, 'show'])->name('flights.show');
Route::get('/api/analysis', [Site\FlightController::class, 'analysisData'])->name('analysis.data');
// Legacy redirects
Route::get('/flights', fn () => redirect()->route('flights.index'))->name('flights.redirect');
Route::get('/analysis', fn () => redirect()->route('flights.index'))->name('analysis');
Route::get('/teams', [Site\TeamController::class, 'index'])->name('teams.index');
Route::get('/teams/{team}', [Site\TeamController::class, 'show'])->name('teams.show');
Route::get('/pigeons/search', [Site\PigeonController::class, 'search'])->name('pigeons.search');
Route::get('/pigeons/{pigeon}', [Site\PigeonController::class, 'show'])->name('pigeons.show');
Route::get('/news', [Site\NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [Site\NewsController::class, 'show'])->name('news.show');
Route::get('/enter', [Site\EntryController::class, 'index'])->name('enter');
Route::post('/enter', [Site\EntryController::class, 'store'])->name('enter.store');
Route::get('/page/{slug}', [Site\PageController::class, 'show'])->name('pages.show');
Route::get('/pools/create', [Site\PoolController::class, 'create'])->name('pools.create');
Route::post('/pools', [Site\PoolController::class, 'store'])->name('pools.store');
Route::post('/contact', [Site\ContactController::class, 'send'])->name('contact.send');

// ── Admin ─────────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function (\Illuminate\Http\Request $request) {
        $loft = \App\Models\Loft::find(config('olr.loft_id'));
        $seasons = $loft?->seasons()->latest('id')->get() ?? collect();
        $season = $request->has('season')
            ? $seasons->firstWhere('id', $request->get('season'))
            : $seasons->first();

        return view('admin.dashboard', [
            'loft' => $loft,
            'seasons' => $seasons,
            'season' => $season,
            'recentPosts' => \App\Models\Post::published()->limit(5)->get(),
        ]);
    })->name('dashboard');
    Route::post('/sync', function () {
        $service = app(\App\Services\SyncService::class);
        $stats = $service->sync();

        return back()->with('sync_stats', $stats);
    })->name('sync');

    // Posts CRUD
    Route::get('/posts', [Admin\PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [Admin\PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [Admin\PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [Admin\PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [Admin\PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [Admin\PostController::class, 'destroy'])->name('posts.destroy');

    // Pages CRUD
    Route::get('/pages', [Admin\PageController::class, 'index'])->name('pages.index');
    Route::get('/pages/create', [Admin\PageController::class, 'create'])->name('pages.create');
    Route::post('/pages', [Admin\PageController::class, 'store'])->name('pages.store');
    Route::get('/pages/{page}/edit', [Admin\PageController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{page}', [Admin\PageController::class, 'update'])->name('pages.update');
    Route::delete('/pages/{page}', [Admin\PageController::class, 'destroy'])->name('pages.destroy');

    // Entries
    Route::get('/entries', [Admin\EntryController::class, 'index'])->name('entries.index');
    Route::get('/entries/{entry}', [Admin\EntryController::class, 'show'])->name('entries.show');
    Route::patch('/entries/{entry}/status', [Admin\EntryController::class, 'updateStatus'])->name('entries.updateStatus');
    Route::delete('/entries/{entry}', [Admin\EntryController::class, 'destroy'])->name('entries.destroy');

    // Gallery
    Route::get('/gallery', [Admin\GalleryController::class, 'index'])->name('gallery.index');
    Route::post('/gallery', [Admin\GalleryController::class, 'store'])->name('gallery.store');
    Route::put('/gallery/{photo}', [Admin\GalleryController::class, 'update'])->name('gallery.update');
    Route::delete('/gallery/{photo}', [Admin\GalleryController::class, 'destroy'])->name('gallery.destroy');

    // Agents CRUD
    Route::get('/agents', [Admin\AgentController::class, 'index'])->name('agents.index');
    Route::get('/agents/create', [Admin\AgentController::class, 'create'])->name('agents.create');
    Route::post('/agents', [Admin\AgentController::class, 'store'])->name('agents.store');
    Route::get('/agents/{agent}/edit', [Admin\AgentController::class, 'edit'])->name('agents.edit');
    Route::put('/agents/{agent}', [Admin\AgentController::class, 'update'])->name('agents.update');
    Route::delete('/agents/{agent}', [Admin\AgentController::class, 'destroy'])->name('agents.destroy');

    // Prizes
    Route::get('/prizes', [Admin\PrizeCategoryController::class, 'index'])->name('prizes.index');
    Route::post('/prizes', [Admin\PrizeCategoryController::class, 'store'])->name('prizes.store');
    Route::put('/prizes/{prizeCategory}', [Admin\PrizeCategoryController::class, 'update'])->name('prizes.update');
    Route::delete('/prizes/{prizeCategory}', [Admin\PrizeCategoryController::class, 'destroy'])->name('prizes.destroy');
    Route::post('/prizes/{prizeCategory}/positions', [Admin\PrizeCategoryController::class, 'storePosition'])->name('prizes.positions.store');
    Route::post('/prizes/{prizeCategory}/bulk', [Admin\PrizeCategoryController::class, 'bulkUpdatePositions'])->name('prizes.positions.bulk');
    Route::put('/prize-positions/{prizePosition}', [Admin\PrizeCategoryController::class, 'updatePosition'])->name('prize-positions.update');
    Route::delete('/prize-positions/{prizePosition}', [Admin\PrizeCategoryController::class, 'destroyPosition'])->name('prize-positions.destroy');
    Route::post('/prizes/reorder', [Admin\PrizeCategoryController::class, 'reorder'])->name('prizes.reorder');

    // Entry Offers
    Route::get('/offers', [Admin\EntryOfferController::class, 'index'])->name('offers.index');
    Route::post('/offers', [Admin\EntryOfferController::class, 'store'])->name('offers.store');
    Route::put('/offers/{entryOffer}', [Admin\EntryOfferController::class, 'update'])->name('offers.update');
    Route::delete('/offers/{entryOffer}', [Admin\EntryOfferController::class, 'destroy'])->name('offers.destroy');

    // Entry Settings
    Route::get('/entry-settings', [Admin\EntrySettingController::class, 'index'])->name('entry-settings');
    Route::post('/entry-settings', [Admin\EntrySettingController::class, 'update'])->name('entry-settings.update');
    Route::post('/entry-settings/generate-pdf', [Admin\EntrySettingController::class, 'generatePdf'])->name('entry-settings.generate-pdf');

    // Entry PDF
    Route::get('/entry-pdf', [Admin\EntrySettingController::class, 'pdf'])->name('entry-pdf');
    Route::post('/entry-pdf', [Admin\EntrySettingController::class, 'updatePdf'])->name('entry-pdf.update');

    // Pool PDFs
    Route::get('/pool-pdf', [Admin\PoolSettingController::class, 'index'])->name('pool-pdf');
    Route::post('/pool-pdf', [Admin\PoolSettingController::class, 'update'])->name('pool-pdf.update');
    Route::post('/pool-pdf/generate', [Admin\PoolSettingController::class, 'generate'])->name('pool-pdf.generate');

    // Pool Entries
    Route::get('/pool-entries', [Admin\PoolEntryController::class, 'index'])->name('pool-entries.index');
    Route::get('/pool-entries/{poolEntry}', [Admin\PoolEntryController::class, 'show'])->name('pool-entries.show');
    Route::patch('/pool-entries/{poolEntry}/status', [Admin\PoolEntryController::class, 'updateStatus'])->name('pool-entries.updateStatus');
    Route::delete('/pool-entries/{poolEntry}', [Admin\PoolEntryController::class, 'destroy'])->name('pool-entries.destroy');

    // Settings (individual pages)
    Route::get('/settings/homepage', [Admin\SettingController::class, 'homepage'])->name('settings.homepage');
    Route::get('/settings/header', [Admin\SettingController::class, 'header'])->name('settings.header');
    Route::get('/settings/race-map', [Admin\SettingController::class, 'raceMap'])->name('settings.race-map');
    Route::get('/settings/footer', [Admin\SettingController::class, 'footer'])->name('settings.footer');
    Route::post('/settings', [Admin\SettingController::class, 'update'])->name('settings.update');
    // Legacy redirect
    Route::get('/settings', function () { return redirect()->route('admin.settings.homepage'); })->name('settings');
});

require __DIR__.'/auth.php';
