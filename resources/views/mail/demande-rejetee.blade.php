@component('mail::message')
# Demande d'inscription rejetée

Bonjour {{ $user->prenom }} {{ $user->nom }},

Nous regrettons de vous informer que votre demande d'inscription a été **rejetée**.

Si vous pensez qu'il y a une erreur, veuillez nous contacter via notre formulaire de support.

@component('mail::button', ['url' => route('home')])
Retour à l'accueil
@endcomponent

Cordialement,  
**L'équipe Culture Bénin**
@endcomponent
