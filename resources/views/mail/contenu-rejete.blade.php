@component('mail::message')

# Contenu rejeté


Bonjour {{ $user?->prenom ?? $user?->nom ?? 'Utilisateur' }},

Nous vous informons que votre contenu intitulé **"{{ $contenu->titre }}"** a été rejeté par l'équipe de modération.

@if(!empty($reason))
**Motif fourni :**  
{{ $reason }}
@else
Motif possible : non-respect de la charte ou contenu inapproprié.
@endif

Si vous pensez qu'il s'agit d'une erreur, veuillez répondre à cet email ou contacter l'équipe d'administration.

@component('mail::button', ['url' => route('home')])
Retour au site
@endcomponent

Cordialement,  
**L'équipe Culture Bénin**

@endcomponent
