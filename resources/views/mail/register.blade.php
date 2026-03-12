<x-mail::message>
# Bienvenue sur {{ config('app.name') }} 🚀

Bonjour {{ $user->prenom }},

Votre demande de création de compte sur <span style="font-weight: bold;color: #38A82F;">{{ config('app.name') }}</span> vient d'être approuvée.

Vous trouverez ci-dessous les informations associées à votre compte.


<h3 style="font-weight: 700;color: #38A82F;">👤 Informations du compte</h3>
<x-mail::panel>

📧 &nbsp;**Email** :
{{ $user->email }}

🔑 &nbsp;**Mot de passe** :
{{ $password }}

</x-mail::panel>


<!-- ## 🔎 Action recommandée

Veuillez vous connecter pour profiter des fonctionnalités de <span style="font-weight: bold;color: #38A82F;">{{ config('app.name') }}</span>. -->

<x-mail::button :url="url('login')" color="success">
    se connecter
</x-mail::button>

<!-- ## ⚠️ Sécurité

Si vous n'êtes pas à l'origine de cette inscription, veuillez contacter notre support immédiatement. -->

Merci,<br>
L'équipe {{ config('app.name') }}

</x-mail::message>