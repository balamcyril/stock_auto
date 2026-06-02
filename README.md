# Stock Auto Backend

## Installation base de donnees

```bash
php bin/console doctrine:migrations:migrate
```

Un compte administrateur local a ete cree en base pour EasyAdmin :

- email: `admin@example.com`
- mot de passe: `admin`

## API JWT

### 1. Demarrer le serveur local

```bash
php -S 127.0.0.1:8016 -t public public/index.php
```

### 2. Creer un compte client

```bash
curl -X POST http://127.0.0.1:8016/api/register \
  -H "Content-Type: application/json" \
  -d "{\"firstName\":\"Jean\",\"lastName\":\"Dupont\",\"email\":\"jean@example.com\",\"password\":\"TestPass123!\",\"phone\":\"+33612345678\"}"
```

Le mot de passe est hashe avant enregistrement dans la colonne `password_hash`.

### 3. Generer un token JWT

```bash
curl -X POST http://127.0.0.1:8016/api/login_check \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"jean@example.com\",\"password\":\"TestPass123!\"}"
```

La reponse contient un champ `token`.

### 4. Appeler une API protegee

```bash
curl http://127.0.0.1:8016/api/products \
  -H "Accept: application/ld+json" \
  -H "Authorization: Bearer VOTRE_TOKEN"
```

### 5. Tester toutes les ressources exposees

```powershell
$base = "http://127.0.0.1:8016"
$login = Invoke-RestMethod "$base/api/login_check" -Method POST -ContentType "application/json" -Body '{"email":"jean@example.com","password":"TestPass123!"}'
$headers = @{ Authorization = "Bearer $($login.token)"; Accept = "application/ld+json" }
$entry = Invoke-RestMethod "$base/api" -Headers $headers
$entry.PSObject.Properties |
  Where-Object { $_.Name -notlike "@*" } |
  ForEach-Object {
    $response = Invoke-WebRequest "$base$($_.Value)" -Headers $headers -TimeoutSec 15
    "$($_.Value) => $($response.StatusCode)"
  }
```

Resultat verifie localement : 29 ressources API exposees, toutes en HTTP `200` avec un token JWT valide.

## Images

Les uploads Vich sont configures pour :

- produits: `public/uploads/products`
- marques: `public/uploads/brands`
- categories: `public/uploads/categories`
- sous-categories: `public/uploads/sub_categories`

Les tailles Liip Imagine ajoutees sont :

- `standard_800x600` pour les images produit
- `square_400x400` pour marques, categories et sous-categories
- `thumbnail_800x570` conserve comme format generique si necessaire
