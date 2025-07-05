<tr>
    <td class="header">
    <a href="{{ $url }}" style="display: inline-block;">
    @if (trim($slot) === 'VNR')
    <img src="https://hrrec.vnress.in/assets/images/vnrlogo.png" class="logo" alt="">
    @else
    {{ $slot }}
    @endif
    </a>
    </td>
    </tr>
    