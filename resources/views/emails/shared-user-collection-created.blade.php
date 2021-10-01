@component('mail::message')

Ahoj!

Tvoj výber diel je uložený na Webe umenia a pripravený na publikovanie.

@component('mail::panel')
**[{{ $shareUrl }}]({{ $shareUrl }})**

Zdieľaj tento odkaz na sociálnych sieťach, alebo pošli e-mailom!
@endcomponent


<br/>

🤫 Svoj výber môžeš ďalej upraviť na tomto [tajnom odkaze]({{ $editUrl }}).

@component('mail::subcopy')
Ak máš pripomienky alebo návrhy na zlepšenie, odpíš na tento e-mail.
@endcomponent

@endcomponent
