<?php

namespace Josephdlmd\FilamentUx\Layout;

use Illuminate\Contracts\Support\Htmlable;

/**
 * The record page's heading: the record's identifier, its name and its status as a Filament badge. Together with the
 * page's header actions (one primary action, the rest under "…") it replaces the heading and breadcrumbs.
 */
final class IdentityBar implements Htmlable
{
    private ?string $statusLabel = null;

    /**
     * @var string|array<int|string, string>
     */
    private string|array $statusColor = 'gray';

    private function __construct(
        private readonly string $identifier,
        private readonly string $name,
    ) {}

    /**
     * The record's identifier (such as its code) and its name.
     */
    public static function make(string $identifier, string $name): self
    {
        return new self($identifier, $name);
    }

    /**
     * The record's status, shown as a badge after its name; none when the label is null.
     *
     * @param  string|array<int|string, string>|null  $color
     */
    public function status(?string $label, string|array|null $color = 'gray'): self
    {
        $this->statusLabel = $label;
        $this->statusColor = $color ?? 'gray';

        return $this;
    }

    /**
     * The heading markup: Filament's own badge inside the package's one view.
     */
    public function toHtml(): string
    {
        return view('filament-ux::identity-bar', [
            'identifier' => $this->identifier,
            'name' => $this->name,
            'statusLabel' => $this->statusLabel,
            'statusColor' => $this->statusColor,
        ])->render();
    }
}
