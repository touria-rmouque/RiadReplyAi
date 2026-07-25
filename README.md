# RiadReply AI

Application SaaS de gestion des réponses aux avis clients pour riads
et restaurants marocains. Analyse automatique des avis (sentiment,
langue, thématiques), génération de réponses personnalisées par
GPT-4o-mini, système de flagging des avis critiques.

## Fonctionnalités

- **Auth** — Inscription / connexion / déconnexion
- **Profil établissement** — Nom, type (Riad / Restaurant), ton (Chaleureux / Formel / Enthousiaste)
- **Paste & Go** — Colle un avis brut → analyse IA asynchrone
- **Analyse IA (GPT-4o-mini)** :
  - Sentiment : Positif / Neutre / Négatif
  - Langue détectée automatiquement (fr, en, es, ar, de…)
  - Thématiques extraites (Personnel, Propreté, Cuisine, WiFi…)
  - Réponse draft dans la langue de l'avis, avec le nom de l'établissement et le bon ton
  - Flagging automatique si avis négatif ou note ≤ 2/5
- **Copier en 1 clic** — Pour coller sur Google / TripAdvisor / Booking
- **Dashboard** — Stats globales, top thématiques, répartition sentiments
- **Historique** — Liste paginée, filtres sentiment / flagué / recherche

## Installation

```bash
# 1. Installer les dépendances
composer install

# 2. Configuration
cp .env.example .env
php artisan key:generate

# 3. Créer la base de données "riadreply" dans phpMyAdmin (XAMPP)
# Puis vérifier DB_HOST, DB_DATABASE, DB_USERNAME dans .env

# 4. Clé OpenAI dans .env
# OPENAI_API_KEY=sk-...
# Récupère une clé sur https://platform.openai.com/api-keys

# 5. Publier la config OpenAI
php artisan vendor:publish --provider="OpenAI\Laravel\ServiceProvider"

# 6. Migrations + seeder (crée les tags et le compte démo)
php artisan migrate --seed

# 7. Lancer (2 terminaux)
php artisan serve
php artisan queue:listen
```

**Compte démo :** `demo@riadreply.test` / `password`

## Architecture

```
app/
  Enums/        Sentiment, ReviewStatus, EstablishmentType, EstablishmentTone
  Models/       User, Establishment, Review, Tag
  Services/     ReviewAnalysisService   ← prompt engineering + JSON strict
  Jobs/         AnalyseReviewJob        ← traitement asynchrone (queue)
  Http/
    Controllers/
      Auth/     RegisteredUserController, AuthenticatedSessionController
      DashboardController, ReviewController, SettingsController
    Requests/   StoreReviewRequest, StoreEstablishmentRequest
database/
  migrations/   establishments, tags, reviews, review_tag
  seeders/      Tags prédéfinis + compte démo
resources/views/
  layouts/app   Sidebar amber, header, flash messages
  auth/         login, register
  dashboard/    index (stats + thématiques + avis récents)
  reviews/      create (Paste & Go), show (réponse + copier), index (liste + filtres)
  settings/     index (profil établissement)
  vendor/pagination/tailwind.blade.php (pagination custom thème sombre)
```

## Coût IA

Le modèle `gpt-4o-mini` est très économique.
Estimation : **~$0.001 par avis** (input + output).
Pour 1000 avis/mois : ~$1.
