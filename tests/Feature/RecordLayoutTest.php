<?php

use Filament\Actions\Testing\TestAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Illuminate\View\ViewException;
use Josephdlmd\FilamentUx\Layout\DetailsAside;
use Josephdlmd\FilamentUx\Layout\Figure;
use Josephdlmd\FilamentUx\Layout\IdentityBar;
use Josephdlmd\FilamentUx\Layout\RecordLayout;
use Josephdlmd\FilamentUx\Tests\Fixtures\Gadget;
use Josephdlmd\FilamentUx\Tests\Fixtures\GadgetResource;
use Josephdlmd\FilamentUx\Tests\Fixtures\Pages\ViewGadget;
use Josephdlmd\FilamentUx\Tests\Fixtures\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    ViewGadget::$figureCount = 3;
    ViewGadget::$hiddenFigures = 0;
    actingAs(User::query()->create(['name' => 'Ana', 'email' => 'ana@example.com', 'password' => 'secret']));
});

function gadget(): Gadget
{
    return Gadget::query()->create(['code' => 'G-0001', 'name' => 'Sprocket', 'status' => 'active']);
}

/**
 * @return list<TextEntry>
 */
function figuresCounting(int $count): array
{
    return array_map(fn (int $index): TextEntry => Figure::make("figure_{$index}")->state($index), range(1, $count));
}

it('renders the Figures strip, then the Main column beside a single Details aside', function () {
    $page = get(GadgetResource::getUrl('view', ['record' => gadget()]))->assertOk();

    $page->assertSeeTextInOrder(['Stock figure', 'Reorder figure', 'Lead time figure', 'Main notes', 'Handle with care', 'Details', 'Aside code', 'G-0001']);

    expect(substr_count($page->getContent(), DetailsAside::CSS_CLASS))->toBe(1);
});

it('heads the page with the Identity bar and shows no breadcrumbs', function () {
    $record = gadget();

    $page = Livewire::test(ViewGadget::class, ['record' => $record->getRouteKey()]);

    expect($page->instance()->getBreadcrumbs())->toBe([])
        ->and((string) $page->instance()->getHeading()->toHtml())->toContain('G-0001', 'Sprocket', 'Active');

    get(GadgetResource::getUrl('view', ['record' => $record]))
        ->assertDontSee('fi-breadcrumbs', false);
});

it('starts the Identity bar with the name when the record has no identifier', function () {
    $html = IdentityBar::make(null, 'Acme Trading')->status('VAT unknown', 'warning')->toHtml();

    expect($html)->toContain('Acme Trading', 'VAT unknown')
        ->and(substr_count($html, 'text-gray-500'))->toBe(0);
});

it('packs the Figures strip\'s Figures together from the start, each at its own width, leaving out those the viewer cannot see', function (int $declared, int $hidden) {
    ViewGadget::$figureCount = $declared;
    ViewGadget::$hiddenFigures = $hidden;

    $html = get(GadgetResource::getUrl('view', ['record' => gadget()]))->assertOk()->getContent();

    expect($html)->toMatch('/class="[^"]*\\bfi-sc-flex\\b[^"]*\\bfux-figures-strip\\b[^"]*"|class="[^"]*\\bfux-figures-strip\\b[^"]*\\bfi-sc-flex\\b[^"]*"/')
        ->and(substr_count($html, 'fi-growable'))->toBe(0)
        ->and(substr_count($html, 'role="term">'))->toBeGreaterThanOrEqual($declared - $hidden);
})->with([
    'five seen' => [5, 0],
    'four of five seen' => [5, 1],
    'three seen' => [3, 0],
]);

it('shows a Figure\'s label, value and caption aligned to the end, the label still read as the entry\'s term', function () {
    $html = get(GadgetResource::getUrl('view', ['record' => gadget()]))->getContent();

    expect($html)->toMatch('/fi-sr-only"\s+role="term">\s*Stock figure/')
        ->and(substr_count($html, 'fi-align-end'))->toBeGreaterThanOrEqual(3)
        ->and($html)->toMatch('/class="(?=[^"]*\btext-end\b)(?=[^"]*\bfi-sc-text\b)[^"]*"[^>]*>\s*ex-VAT · 3 offers/');
});

it('puts the Details aside\'s actions on its heading row', function () {
    get(GadgetResource::getUrl('view', ['record' => gadget()]))
        ->assertOk()
        ->assertSeeInOrder(['fi-section-header', 'Details', 'Edit details', 'fi-section-content', 'Aside code'], escape: false);

    Livewire::test(ViewGadget::class, ['record' => gadget()->getRouteKey()])
        ->callAction(TestAction::make('editDetails')->schemaComponent('details::section', 'infolist'))
        ->assertHasNoActionErrors();
});

it('names the record as it is after an action changes it, in the same request', function () {
    Livewire::test(ViewGadget::class, ['record' => gadget()->getRouteKey()])
        ->callAction('rename')
        ->assertSee('Renamed sprocket');
});

it('leaves the Figures strip out of a record page that declares no Figures', function () {
    ViewGadget::$figureCount = 0;

    get(GadgetResource::getUrl('view', ['record' => gadget()]))
        ->assertOk()
        ->assertSeeTextInOrder(['Main notes', 'Details', 'Aside code'])
        ->assertDontSeeText('Stock figure');
});

it('refuses a Figures strip with fewer than 3 or more than 5 Figures', function (int $count) {
    RecordLayout::make()->figures(figuresCounting($count))->toSchema(Schema::make());
})->with([2, 6])->throws(InvalidArgumentException::class, 'A Figures strip holds 3 to 5 Figures');

it('refuses to render a record page declaring fewer than 3 or more than 5 Figures', function (int $count) {
    ViewGadget::$figureCount = $count;

    Livewire::test(ViewGadget::class, ['record' => gadget()->getRouteKey()]);
})->with([2, 6])->throws(ViewException::class, 'A Figures strip holds 3 to 5 Figures');

it('renders a record page declaring 5 Figures', function () {
    ViewGadget::$figureCount = 5;

    get(GadgetResource::getUrl('view', ['record' => gadget()]))->assertOk()->assertSeeText('Batch figure');
});

it('builds a Figures strip of 3 to 5 Figures', function (int $count) {
    expect(RecordLayout::make()->figures(figuresCounting($count))->toSchema(Schema::make()))->toBeInstanceOf(Schema::class);
})->with([3, 5]);
