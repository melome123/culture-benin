@component('mail::message')
# Bienvenue sur Culture Bénin !

Bonjour {{ $user->prenom }} {{ $user->nom }},

Nous sommes heureux de vous informer que votre demande d'inscription a été **approuvée**. 

Vous pouvez maintenant accéder à votre compte sur notre plateforme.

@component('mail::button', ['url' => route('login')])
Se connecter
@endcomponent

Cordialement,  
**L'équipe Culture Bénin**
@endcomponent
