@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level == 'error')
# Whoops!
@else
# Witaj, dobry człowieku!
@endif
@endif

{{-- Intro Lines --}}
<?php // @foreach ($introLines as $line)
// {{ $line }}
//@endforeach
?>

Wysłałem Ci tego maila, bo otrzymałem prośbę o zmianę hasła przypisanego do tego maila.

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
            $color = 'green';
            break;
        case 'error':
            $color = 'red';
            break;
        default:
            $color = 'blue';
    }
    $actionText='Zmień hasło';
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{$actionText}}
@endcomponent
@endisset

{{-- Outro Lines --}}
<?php //@foreach ($outroLines as $line)
// {{ $line }}
//@endforeach
?>
Jeśli to nie Ty wysłałeś to żądanie - zignoruj tego maila i nic się nie zmieni.<br>
Ale któż inny mógłby chcieć zmienić hasło do Twojego konta?...

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
Pozdrawiam, <br>{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
@component('mail::subcopy')
Jeśli masz problem z kliknięciem przycisku "{{ $actionText }}", skopiuj i wklej poniższy adres URL
w Twojej przeglądarce internetowej: [{{ $actionUrl }}]({!! $actionUrl !!})
@endcomponent
@endisset
@endcomponent
