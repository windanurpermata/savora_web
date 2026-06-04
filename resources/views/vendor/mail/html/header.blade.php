@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{ asset('images/logo.png') }}" class="logo" alt="{{ config('app.name') }}" style="height: 75px; width: 75px; border-radius: 12px;">
</a>
</td>
</tr>
