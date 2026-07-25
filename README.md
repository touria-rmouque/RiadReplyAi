#  RiadReply AI

RiadReply AI est une application web développée avec **Laravel 12** permettant aux propriétaires de riads, hôtels et restaurants de centraliser, analyser et répondre intelligemment aux avis clients grâce à l'intelligence artificielle.

---

#  Fonctionnalités

##  Authentification

- Inscription et connexion sécurisées
- Authentification API avec Laravel Sanctum
- Gestion des tokens d'accès
- Déconnexion
- Profil utilisateur

---

##  Gestion des établissements

- Création d'un établissement
- Modification
- Archivage (Soft Delete)
- Restauration
- Suppression définitive
- Changement d'établissement actif

Chaque utilisateur peut gérer plusieurs établissements.

---

##  Gestion des avis

- Import manuel d'un avis
- Consultation des avis
- Filtrage
- Consultation d'un avis
- Marquer un avis comme répondu
- Suppression d'un avis

Chaque avis appartient à l'établissement actuellement sélectionné.

---

##  Intelligence Artificielle

Après l'ajout d'un avis :

- détection automatique de la langue
- analyse du sentiment
- génération d'une réponse personnalisée
- extraction des points importants

L'analyse est exécutée en arrière-plan grâce aux Jobs Laravel.

---

##  Tableau de bord

Le tableau de bord fournit notamment :

- nombre total d'avis
- avis positifs
- avis négatifs
- avis neutres
- avis en attente
- avis répondus

---

#  Technologies

- Laravel 12
- PHP 8.5
- MySQL
- Laravel Sanctum
- Laravel Queues
- Laravel Policies
- Laravel Resources
- Laravel Form Requests
- Eloquent ORM
- Blade
- Tailwind CSS
- AI Agents (Laravel AI)

---

#  Architecture

```
app/
│
├── Actions/
├── AI/
│   ├── Agents/
│   ├── DTO/
│   ├── Prompts/
│   └── Tools/
│
├── Enums/
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   └── Resources/
│
├── Jobs/
├── Models/
├── Policies/
└── Services/
```

Le projet suit une architecture orientée **Actions**, permettant de séparer la logique métier des contrôleurs.

---

#  Installation

## Cloner le projet

```bash
git clone https://github.com/<username>/RiadReplyAi.git

cd RiadReplyAi
```

## Installer les dépendances

```bash
composer install

npm install
```

## Copier le fichier d'environnement

```bash
cp .env.example .env
```

Sous Windows :

```bash
copy .env.example .env
```

## Générer la clé

```bash
php artisan key:generate
```

## Configurer la base de données

Modifier le fichier `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=riadreply
DB_USERNAME=root
DB_PASSWORD=
```

---

## Exécuter les migrations

```bash
php artisan migrate
```

---

## Lancer le serveur

```bash
php artisan serve
```

---

## Compiler les assets

```bash
npm run dev
```

---

#  Tests

Lancer tous les tests

```bash
php artisan test
```

Lancer un fichier spécifique

```bash
php artisan test tests/Feature/Api/AuthTest.php
```

Les tests couvrent :

- Auth API
- Dashboard API
- Establishments API
- Reviews API

---

#  API

## Auth

| Méthode | Endpoint |
|----------|----------|
| POST | /api/login |
| POST | /api/logout |
| GET | /api/me |

---

## Establishments

| Méthode | Endpoint |
|----------|----------|
| GET | /api/establishments |
| POST | /api/establishments |
| GET | /api/establishments/{id} |
| PUT | /api/establishments/{id} |
| DELETE | /api/establishments/{id} |

---

## Reviews

| Méthode | Endpoint |
|----------|----------|
| GET | /api/reviews |
| POST | /api/reviews |
| GET | /api/reviews/{id} |
| PATCH | /api/reviews/{id}/reply |
| DELETE | /api/reviews/{id} |

---

## Dashboard

| Méthode | Endpoint |
|----------|----------|
| GET | /api/dashboard |

---

#  Sécurité

Le projet utilise :

- Laravel Sanctum
- Form Requests
- Policies
- Validation des données
- Protection CSRF (Web)
- Authentification API par Token

---

#  Bonnes pratiques

- Architecture Action Pattern
- Form Requests
- API Resources
- Enums PHP
- Policies Laravel
- Soft Deletes
- Jobs pour les traitements IA
- Tests Feature
- Code conforme aux conventions Laravel 12

---

#  Aperçu

Le projet permet aux propriétaires de :

- gérer plusieurs établissements ;
- centraliser les avis clients ;
- analyser automatiquement les sentiments ;
- générer des réponses professionnelles grâce à l'IA ;
- suivre les statistiques depuis un tableau de bord.

---
