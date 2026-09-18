# CERN API

API REST de gestion de **projets** et de **tâches**, développée avec **Laravel 11**, avec authentification **JWT** et documentation interactive **Swagger (OpenAPI)**.

> ⚠️ **Projet présenté à titre de portfolio.**
> Ce dépôt a été réalisé dans un but purement démonstratif : il illustre ma façon de concevoir une API Laravel (authentification, validation, documentation OpenAPI, gestion d'erreurs). Il n'est **pas destiné à être déployé en production** tel quel.

---

## Fonctionnalités

- 🔐 **Authentification JWT** (`tymon/jwt-auth`) : inscription, connexion, déconnexion, protection des routes via le guard `api`
- 📁 **CRUD complet des projets** avec tri, recherche par champ et pagination
- ✅ **CRUD complet des tâches** rattachées aux projets (relation 1-N), avec filtre par statut
- 🔗 **Routes imbriquées** : tâches d'un projet (`/projects/{id}/tasks`) et projet d'une tâche (`/tasks/{id}/project`)
- 📚 **Documentation OpenAPI/Swagger** générée depuis les annotations des contrôleurs, exposée via Swagger UI
- 🧹 **Validation dédiée** via `FormRequest` avec réponses d'erreur JSON normalisées (422)
- 🚨 **Gestion d'erreurs JSON** centralisée : 401 (non authentifié), 404 (resource introuvable)
- 🌱 **Factories et seeders** de démonstration : 270 utilisateurs, 300 projets et 1 à 10 tâches par projet

## Stack technique

| Domaine | Technologie |
|---|---|
| Backend | PHP ≥ 8.2, Laravel 11 |
| Authentification | JWT (`tymon/jwt-auth`) |
| Documentation API | OpenAPI / Swagger UI (`darkaonline/l5-swagger`) |
| Base de données | SQLite par défaut (compatible MySQL, PostgreSQL…) |
| Build front | Vite 6, Tailwind CSS 3 |
| Tests | PHPUnit 11 |

## Endpoints principaux

| Méthode | URI | Auth | Description |
|---|---|---|---|
| `POST` | `/api/register` | ❌ | Inscription, retourne un token JWT |
| `POST` | `/api/login` | ❌ | Connexion, retourne un token JWT |
| `POST` | `/api/logout` | ✅ | Invalidation du token |
| `GET` | `/api/users` | ✅ | Liste des utilisateurs |
| `GET` | `/api/projects` | ✅ | Liste des projets (tri, filtre, recherche, pagination) |
| `POST` | `/api/projects` | ✅ | Créer un projet |
| `GET` | `/api/projects/{id}` | ✅ | Détail d'un projet |
| `PUT` | `/api/projects/{id}` | ✅ | Modifier un projet |
| `DELETE` | `/api/projects/{id}` | ✅ | Supprimer un projet |
| `GET` | `/api/projects/{id}/tasks` | ✅ | Tâches d'un projet (statut, tri, pagination) |
| `GET` | `/api/tasks` | ✅ | Liste des tâches (filtre par statut, pagination) |
| `POST` | `/api/tasks` | ✅ | Créer une tâche |
| `GET` | `/api/tasks/{id}` | ✅ | Détail d'une tâche (avec son projet) |
| `PUT` | `/api/tasks/{id}` | ✅ | Modifier une tâche |
| `DELETE` | `/api/tasks/{id}` | ✅ | Supprimer une tâche |
| `GET` | `/api/tasks/{id}/project` | ✅ | Projet lié à une tâche |

**Paramètres de requête courants** : `sort_by`, `sort_order` (`asc`/`desc`), `filter` + `keyword` (projets), `status` (tâches). Les listes sont paginées (10 éléments/page, format Laravel).

Les routes protégées attendent l'en-tête `Authorization: Bearer <token>`.

## Installation

Prérequis : **PHP ≥ 8.2**, **Composer**, **Node.js/npm**.

```bash
# 1. Cloner le dépôt et installer les dépendances
git clone <url-du-depot>
cd cern
composer install
npm install

# 2. Configurer l'environnement
cp .env.example .env
php artisan key:generate
php artisan jwt:secret

# 3. Base de données (SQLite par défaut)
touch database/database.sqlite
php artisan migrate --seed

# 4. Lancer l'application
npm run dev        # assets Vite (dans un terminal séparé)
php artisan serve  # API sur http://localhost:8000
```

Pour utiliser MySQL/PostgreSQL, adapter les variables `DB_*` du fichier `.env`.

## Documentation Swagger

Une fois le serveur lancé, la documentation interactive est disponible sur :

```
http://localhost:8000/api/documentation
```

Les annotations OpenAPI sont rédigées directement dans les contrôleurs, modèles et `FormRequests`. La génération est automatique (`L5_SWAGGER_GENERATE_ALWAYS=true`), ou manuelle :

```bash
php artisan l5-swagger:generate
```

Pour tester les routes protégées : récupérer un token via `POST /api/login`, puis envoyer l'en-tête `Authorization: Bearer <token>` à chaque requête.

## Structure du projet

```
app/
├── Exceptions/Handler.php        # Réponses JSON pour 401 / 404
├── Http/
│   ├── Controllers/API/          # AuthController, ProjectController, TaskController, UserController
│   ├── Middleware/Authenticate.php
│   └── Requests/                 # Validation (ProjectCreateRequest, TaskUpdateRequest, …)
└── Models/                       # User (JWTSubject), Project, Task
config/
├── auth.php                      # Guard "api" (driver jwt)
├── jwt.php                       # Configuration JWT
└── l5-swagger.php                # Configuration Swagger
database/
├── factories/                    # UserFactory, ProjectFactory, TaskFactory
├── migrations/
└── seeders/                      # 270 users + 300 projets × 1-10 tâches
routes/api.php                    # Toutes les routes de l'API
```

## À propos de ce portfolio

Ce projet me sert de vitrine technique. Il démontre notamment :

- La mise en place d'une **authentification stateless JWT** sur un guard Laravel dédié
- L'usage de **FormRequests** pour isoler la validation et normaliser les erreurs JSON
- La **centralisation de la gestion d'exceptions** pour une API (réponses JSON cohérentes)
- La **documentation OpenAPI vivante**, générée depuis le code (annotations)
- Les **relations Eloquent**, les **factories/seeders** et le requêtage (tri, filtre, pagination)

Le code est fourni tel quel, à des fins de démonstration — n'hésitez pas à le parcourir ou à me contacter pour en discuter.
