<?php

namespace Josephdlmd\FilamentUx\Entries;

use Closure;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\Size;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use libphonenumber\NumberParseException;
use Propaganistas\LaravelPhone\PhoneNumber;

/**
 * Contact details that can be acted on where they stand: a phone number people can call (and message on Viber, where
 * it is a mobile), an email address that opens a new email, an address that opens a search on Google Maps or Waze.
 * Each action is a small link under the value, shown only when there is a value to act on; a missing detail reads
 * "—", as it is worth filling in.
 */
final class ContactEntry
{
    /**
     * A phone number, read as $region's unless stored with its country code, shown as people read it ("+63 917 123
     * 4567"), with Call, and Viber when $viber (a mobile; Viber needs one).
     */
    public static function phone(string $name, string $region = 'PH', bool $viber = true): TextEntry
    {
        $dialled = fn (?string $state): ?string => self::e164($state, $region);

        return TextEntry::make($name)
            ->formatStateUsing(fn (?string $state): ?string => self::readable($state, $region))
            ->placeholder('—')
            ->belowContent([
                self::link("{$name}Call", 'Call', Heroicon::OutlinedPhone, fn (Model $record): ?string => ($number = $dialled($record->getAttribute($name))) === null ? null : "tel:{$number}"),
                ...($viber ? [self::link("{$name}Viber", 'Viber', Heroicon::OutlinedChatBubbleLeftRight, fn (Model $record): ?string => ($number = $dialled($record->getAttribute($name))) === null ? null : 'viber://chat?number='.rawurlencode($number))] : []),
            ]);
    }

    /**
     * An email address that opens a new email to it.
     */
    public static function email(string $name): TextEntry
    {
        return TextEntry::make($name)
            ->url(fn (?string $state): ?string => blank($state) ? null : "mailto:{$state}")
            ->color('primary')
            ->placeholder('—');
    }

    /**
     * An address with Google Maps, and Waze when $waze, each opening a search for it in a new tab. $country, given the
     * record, is added to the search ("331 Rizal Ave Ext, Caloocan, Philippines") so a street name finds the right
     * city.
     *
     * @param  ?Closure(Model): ?string  $country
     */
    public static function address(string $name, ?Closure $country = null, bool $waze = true): TextEntry
    {
        $place = function (Model $record) use ($name, $country): ?string {
            $address = $record->getAttribute($name);

            return blank($address) ? null : implode(', ', array_filter([$address, $country === null ? null : $country($record)], filled(...)));
        };

        return TextEntry::make($name)
            ->placeholder('—')
            ->belowContent([
                self::link("{$name}GoogleMaps", 'Google Maps', Heroicon::OutlinedMap, fn (Model $record): ?string => ($query = $place($record)) === null ? null : 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($query), newTab: true),
                ...($waze ? [self::link("{$name}Waze", 'Waze', Heroicon::OutlinedMapPin, fn (Model $record): ?string => ($query = $place($record)) === null ? null : 'https://waze.com/ul?q='.rawurlencode($query).'&navigate=yes', newTab: true)] : []),
            ]);
    }

    /**
     * A phone number as it is dialled, E.164 ("+639171234567"); null when blank or unreadable.
     */
    public static function e164(?string $number, string $region = 'PH'): ?string
    {
        if (blank($number)) {
            return null;
        }

        try {
            return (new PhoneNumber($number, $region))->formatE164();
        } catch (NumberParseException) {
            return null;
        }
    }

    /**
     * A phone number as people read and say it: one in the region in its national form ("0917 123 4567",
     * "(02) 8123 4567"), as an input mask for the region writes it; one abroad in international form
     * ("+86 138 0013 8000"). As stored when it can't be read, null when blank.
     */
    public static function readable(?string $number, string $region = 'PH'): ?string
    {
        if (blank($number)) {
            return null;
        }

        try {
            $phoneNumber = new PhoneNumber($number, $region);

            return $phoneNumber->isOfCountry($region) ? $phoneNumber->formatNational() : $phoneNumber->formatInternational();
        } catch (NumberParseException) {
            return $number;
        }
    }

    /**
     * @param  Closure(Model): ?string  $url
     */
    private static function link(string $name, string $label, Heroicon $icon, Closure $url, bool $newTab = false): Action
    {
        return Action::make($name)
            ->label($label)
            ->icon($icon)
            ->link()
            ->size(Size::Small)
            ->url($url, shouldOpenInNewTab: $newTab)
            ->visible(fn (Model $record): bool => $url($record) !== null);
    }
}
