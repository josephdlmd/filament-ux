<span class="fux-identity-bar inline-flex flex-wrap items-center gap-x-3 gap-y-1">
    @if (filled($identifier))
        <span class="font-normal text-gray-500 dark:text-gray-400">{{ $identifier }}</span>
    @endif
    <span>{{ $name }}</span>
    @if (filled($statusLabel))
        <x-filament::badge :color="$statusColor">{{ $statusLabel }}</x-filament::badge>
    @endif
</span>
