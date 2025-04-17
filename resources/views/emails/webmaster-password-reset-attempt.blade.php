@component('mail::message')
# Webmester jelszó visszaállítási kísérlet

Valaki megpróbálta visszaállítani egy webmester jelszavát a rendszerben.

**Érintett webmester:** {{ $targetUser->charactername }}  
**Kísérletet végrehajtotta:** {{ $attemptedBy->charactername }}  
**Időpont:** {{ $timestamp->format('Y.m.d. H:i:s') }}

@component('mail::button', ['url' => url('/users/' . $targetUser->id)])
Felhasználó megtekintése
@endcomponent

Üdvözlettel,  
{{ config('app.name') }}
@endcomponent
