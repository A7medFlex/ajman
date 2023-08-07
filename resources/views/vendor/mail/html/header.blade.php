@props(['url'])
<tr>
<td class="header">
<a href="/login" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="" class="logo" alt="Laravel Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
