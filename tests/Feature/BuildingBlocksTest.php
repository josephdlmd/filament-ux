<?php

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\Testing\TestAction;
use Josephdlmd\FilamentUx\Entries\ContactEntry;
use Josephdlmd\FilamentUx\Tests\Fixtures\Gadget;
use Josephdlmd\FilamentUx\Tests\Fixtures\GadgetResource;
use Josephdlmd\FilamentUx\Tests\Fixtures\Pages\ListGadgets;
use Josephdlmd\FilamentUx\Tests\Fixtures\Pages\ViewGadget;
use Josephdlmd\FilamentUx\Tests\Fixtures\RelationManagers\PartsRelationManager;
use Josephdlmd\FilamentUx\Tests\Fixtures\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    actingAs(User::query()->create(['name' => 'Ana', 'email' => 'ana@example.com', 'password' => 'secret']));
});

/**
 * @param  array<string, mixed>  $attributes
 */
function contactGadget(array $attributes = []): Gadget
{
    return Gadget::query()->create(['code' => 'G-0002', 'name' => 'Sprocket', 'status' => 'active', ...$attributes]);
}

it('groups the Details aside under headings, a hairline above every group but the first', function () {
    $html = get(GadgetResource::getUrl('view', ['record' => contactGadget()]))
        ->assertSeeTextInOrder(['Details', 'Identity', 'Aside code', 'Contact', 'Mobile'])
        ->getContent();

    expect(substr_count($html, 'fux-details-group'))->toBe(2)
        ->and(substr_count($html, 'fux-details-group border-t'))->toBe(1);
});

it('makes contact details usable: Call and Viber on a mobile, Call on a landline, a new email, and Google Maps and Waze on an address', function () {
    $place = rawurlencode('331 Rizal Ave Ext, Caloocan, Philippines');

    $page = get(GadgetResource::getUrl('view', ['record' => contactGadget([
        'mobile' => '0917 123 4567',
        'landline' => '(02) 8123 4567',
        'email' => 'sales@example.com',
        'address' => '331 Rizal Ave Ext, Caloocan',
        'country' => 'Philippines',
    ])]))->assertSeeTextInOrder(['0917 123 4567', 'Call', 'Viber', '(02) 8123 4567', 'Call', 'sales@example.com', '331 Rizal Ave Ext, Caloocan', 'Google Maps', 'Waze']);

    foreach (['href="tel:+639171234567"', 'href="viber://chat?number=%2B639171234567"', 'href="tel:+63281234567"', 'href="mailto:sales@example.com"', "href=\"https://www.google.com/maps/search/?api=1&amp;query={$place}\"", "href=\"https://waze.com/ul?q={$place}&amp;navigate=yes\""] as $link) {
        $page->assertSee($link, false);
    }
    expect(substr_count($page->getContent(), 'viber://'))->toBe(1);
});

it('offers no contact link for a detail that is missing, and reads it as a dash', function () {
    get(GadgetResource::getUrl('view', ['record' => contactGadget()]))
        ->assertDontSee(['tel:', 'viber://', 'mailto:', 'google.com/maps', 'waze.com'], false);
});

it('stores a phone number in international form, and reads one in the region in national form, one abroad in international form, and one it cannot read as stored', function () {
    expect(ContactEntry::e164('0917 123 4567'))->toBe('+639171234567')
        ->and(ContactEntry::readable('+639171234567'))->toBe('0917 123 4567')
        ->and(ContactEntry::readable('+63281234567'))->toBe('(02) 8123 4567')
        ->and(ContactEntry::readable('+8613800138000'))->toBe('+86 138 0013 8000')
        ->and(ContactEntry::readable('call the office'))->toBe('call the office')
        ->and(ContactEntry::e164('call the office'))->toBeNull();
});

it('puts a Related list\'s tabs on its search row as one button group labelled with the counts, the chosen one primary and pressed', function () {
    $gadget = contactGadget();
    $kept = $gadget->parts()->create(['name' => 'Kept part', 'is_active' => true]);
    $retired = $gadget->parts()->create(['name' => 'Retired part', 'is_active' => false]);

    $parts = Livewire::test(PartsRelationManager::class, ['ownerRecord' => $gadget, 'pageClass' => ViewGadget::class]);
    $choice = $parts->instance()->getTable()->getToolbarActions()[0];

    expect($choice)->toBeInstanceOf(ActionGroup::class)
        ->and(collect($choice->getActions())->map(fn (Action $action): string => $action->getLabel())->values()->all())->toBe(['All 2', 'Active 1']);
    $parts->assertDontSeeHtml('fi-tabs')
        ->assertActionHasColor(TestAction::make('showAll')->table(), 'primary')
        ->assertCanSeeTableRecords([$kept, $retired])
        ->callAction(TestAction::make('showActive')->table())
        ->assertSet('activeTab', 'active')
        ->assertCanSeeTableRecords([$kept])
        ->assertCanNotSeeTableRecords([$retired])
        ->assertActionHasColor(TestAction::make('showActive')->table(), 'primary');

    expect(substr_count($parts->html(), 'aria-pressed="true"'))->toBe(1)
        ->and(substr_count($parts->html(), 'aria-pressed="false"'))->toBe(1);
});

it('puts a List page\'s tabs on its search row, leaving out a tab with nothing waiting unless it is open', function () {
    $toCheck = contactGadget(['name' => 'Unchecked gadget', 'status' => 'to_check']);
    $active = contactGadget(['name' => 'Working gadget']);

    Livewire::test(ListGadgets::class)
        ->assertDontSeeHtml('fi-tabs')
        ->assertActionVisible(TestAction::make('showAll')->table())
        ->assertActionVisible(TestAction::make('showToCheck')->table())
        ->assertActionHidden(TestAction::make('showToRetire')->table())
        ->callAction(TestAction::make('showToCheck')->table())
        ->assertCanSeeTableRecords([$toCheck])
        ->assertCanNotSeeTableRecords([$active]);

    Livewire::withQueryParams(['tab' => 'to_retire'])
        ->test(ListGadgets::class)
        ->assertActionVisible(TestAction::make('showToRetire')->table())
        ->assertCanNotSeeTableRecords([$toCheck, $active]);
});

it('leaves out the view choice when nothing is waiting, as one tab is no choice', function () {
    contactGadget();

    Livewire::test(ListGadgets::class)
        ->assertActionHidden(TestAction::make('showAll')->table())
        ->assertDontSee('To check');
});
