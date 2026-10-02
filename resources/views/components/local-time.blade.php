{{-- Renders a date/time that public/js/local-time.js converts to the
     visitor's time zone (12-hour clock). The text inside is the UTC fallback
     in the same style, shown until the script runs or if it can't.
     Usage: <x-local-time :value="$wish->granted_date" style="datetime" /> --}}
@props(['value' => null, 'style' => 'datetime'])
@php
  $phpFormats = [
    'datetime' => 'M j, Y g:i A',
    'datetime-long' => 'F j, Y g:i a',
    'datetime-short' => 'M d, g:i A',
    'date' => 'M d, Y',
    'day' => 'M d',
    'month' => 'M Y',
  ];
  $moment = $value ? \Illuminate\Support\Carbon::parse($value) : null;
@endphp
@if ($moment)<time datetime="{{ $moment->toIso8601ZuluString() }}" data-local-time="{{ $style }}" {{ $attributes }}>{{ $moment->format($phpFormats[$style] ?? $phpFormats['datetime']) }}</time>@endif
