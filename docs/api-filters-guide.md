# Guide des filtres API Stock Auto

Les ressources API exposent des filtres utiles pour chercher et trier les donnees sans passer par le dashboard.

## Produit

`GET /api/products`

Filtres utiles :

- `sku`
- `barcode`
- `oemReference`
- `name`
- `description`
- `brand.name`
- `category.name`
- `subCategory.name`
- `warehouse.name`
- `warehouse.city`
- `shelfCode`
- `status`
- `volumeSize`
- tri par `name`, `price`, `quantity`, `createdAt`, `updatedAt`

Exemples :

```text
/api/products?name=frein
/api/products?brand.name=bosch
/api/products?category.name=moteur
/api/products?warehouse.city=paris&status=active
/api/products?order[name]=asc
```

## Recherche avancee produit

`GET /api/products/search?q=plaquette+frein+bosch`

La recherche dediee prend un ou plusieurs mots cles, puis retourne les produits les plus proches.

Champs ponderes dans la recherche :

- `sku`
- `barcode`
- `oemReference`
- `name`
- `description`
- `brand.name`
- `category.name`
- `subCategory.name`
- `warehouse.name`
- `warehouse.city`
- `shelfCode`

Exemples :

```text
/api/products/search?q=plaquette+frein
/api/products/search?q=bosch+disque
/api/products/search?q=amortisseur+paris&limit=10
```

Exemple de réponse JSON :

```json
[
  {
    "id": "12",
    "sku": "SKU-000123",
    "barcode": "3567891234567",
    "oemReference": "7701478543",
    "name": "Plaquettes de frein avant",
    "brand": {
      "id": "3",
      "name": "Bosch",
      "image": "brands/bosch.png"
    },
    "category": {
      "id": "1",
      "name": "Freinage",
      "image": "categories/freinage.jpg",
      "subCategories": [
        {
          "id": "8",
          "name": "Plaquettes",
          "category": "/api/categories/1",
          "image": "sub_categories/plaquettes.jpg"
        }
      ]
    },
    "subCategory": {
      "id": "8",
      "name": "Plaquettes",
      "category": "/api/categories/1",
      "image": "sub_categories/plaquettes.jpg"
    },
    "description": "Plaquettes neuves jamais montees",
    "price": "29.90",
    "quantity": 24,
    "weightKg": "1.20",
    "volumeSize": "small",
    "warehouse": {
      "id": "2",
      "name": "Entrepot Paris Nord",
      "address": "12 rue de Paris, 75018 Paris",
      "city": "Paris"
    },
    "shelfCode": "A3-B2-04",
    "status": "active",
    "createdAt": "2026-06-04T10:15:00+02:00",
    "updatedAt": "2026-06-04T10:30:00+02:00",
    "images": [
      {
        "id": "45",
        "image": "products/12/img-1.webp",
        "isPrimary": true,
        "sortOrder": 1
      },
      {
        "id": "46",
        "image": "products/12/img-2.webp",
        "isPrimary": false,
        "sortOrder": 2
      },
      {
        "id": "47",
        "image": null,
        "isPrimary": false,
        "sortOrder": 3
      },
      {
        "id": "48",
        "image": null,
        "isPrimary": false,
        "sortOrder": 4
      },
      {
        "id": "49",
        "image": null,
        "isPrimary": false,
        "sortOrder": 5
      }
    ]
  }
]
```

## Marques

`GET /api/brands`

Filtres :

- `name`
- tri par `name`, `id`

Exemple :

```text
/api/brands?name=valeo
```

## Categories

`GET /api/categories`

Filtres :

- `name`
- tri par `name`, `id`

Exemple :

```text
/api/categories?name=frein
```

## Sous-categories

`GET /api/sub_categories`

Filtres :

- `name`
- `category.name`
- tri par `name`, `id`

Exemple :

```text
/api/sub_categories?category.name=moteur
```

## Entrepots

`GET /api/warehouses`

Filtres :

- `name`
- `city`
- `address`
- tri par `name`, `city`, `id`

Exemple :

```text
/api/warehouses?city=lyon
```

## Emplacements produits

`GET /api/product_locations`

Filtres :

- `shelfCode`
- `product.name`
- `warehouse.name`
- tri par `quantity`, `shelfCode`, `id`

Exemple :

```text
/api/product_locations?warehouse.name=paris
```

## Utilisateurs

`GET /api/users`

Filtres :

- `firstName`
- `lastName`
- `email`
- `phone`
- `role`

Exemple :

```text
/api/users?role=warehouse
```

## Commandes

`GET /api/orders`

Filtres :

- `orderNumber`
- `status`
- `paymentStatus`
- `fulfillmentType`
- `user.email`
- tri par `createdAt`, `updatedAt`, `totalAmount`, `orderNumber`, `status`, `paymentStatus`

Exemple :

```text
/api/orders?status=pending&paymentStatus=paid
```

## Paiements

`GET /api/payments`

Filtres :

- `method`
- `status`
- `provider`
- `transactionId`
- `order.orderNumber`

Exemple :

```text
/api/payments?status=pending
```

## Paniers et mouvements

`GET /api/carts`

- filtres sur `status` et `user.email`

`GET /api/cart_items`

- filtres sur `product.name` et `product.sku`

`GET /api/stock_movements`

- filtres sur `type`, `reason`, `product.name`, `product.sku`

## Notes

- Les reponses relationnelles utilisent une profondeur limitee pour eviter les boucles.
- Le endpoint `/api/products/search` est le plus adapte pour la recherche front de type barre de recherche.
