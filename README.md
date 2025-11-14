# 👟 Esmer Shop - Boutique de Chaussures Premium

Projet e-commerce moderne développé avec **Symfony 6.4** et design **Liquid Glass**.

## 🎨 Caractéristiques

- ✅ **Symfony 6.4** (LTS)
- ✅ **PHP 8.3**
- ✅ **Bootstrap 5.3.2** via CDN
- ✅ **Design Liquid Glass** personnalisé
- ✅ **MySQL** (Base de données)
- ✅ **Doctrine ORM**
- ✅ **Système d'authentification** complet
- ✅ **Interface Admin** avec dashboard
- ✅ **11 Entités** complètes

## 📦 Entités disponibles

1. **User** - Utilisateurs (clients et admins)
2. **Product** - Produits (chaussures)
3. **Category** - Catégories
4. **Brand** - Marques
5. **ProductVariant** - Variations (tailles, couleurs, stock)
6. **Cart** / **CartItem** - Panier
7. **Order** / **OrderItem** - Commandes
8. **Address** - Adresses de livraison
9. **Review** - Avis clients
10. **Coupon** - Codes promotionnels

## 🚀 Installation

### Prérequis
- PHP 8.1+ (8.3 recommandé)
- MySQL/MariaDB
- Composer

### Étapes

1. **Cloner le projet**
```bash
cd C:\laragon\www\esmer_shop
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configurer la base de données**
Le fichier `.env` est déjà configuré:
```env
DATABASE_URL="mysql://root:@127.0.0.1:3306/esmer_shop?serverVersion=8.0&charset=utf8mb4"
```

4. **Créer la base de données**
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. **Créer les utilisateurs de test**
```bash
php bin/console app:create-admin
```

Cela créera deux utilisateurs:
- **Admin**: `admin@esmer.shop` / `admin123`
- **User**: `user@esmer.shop` / `user123`

6. **Démarrer le serveur**
```bash
symfony server:start
# ou
php -S localhost:8000 -t public
```

7. **Accéder au projet**
- Frontend: http://localhost:8000
- Admin: http://localhost:8000/admin
- Login: http://localhost:8000/login

## 📁 Structure du projet

```
esmer_shop/
├── src/
│   ├── Command/
│   │   └── CreateAdminCommand.php
│   ├── Controller/
│   │   ├── Admin/
│   │   │   └── DashboardController.php
│   │   ├── HomeController.php
│   │   └── SecurityController.php
│   ├── Entity/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Brand.php
│   │   ├── ProductVariant.php
│   │   ├── Cart.php & CartItem.php
│   │   ├── Order.php & OrderItem.php
│   │   ├── Address.php
│   │   ├── Review.php
│   │   └── Coupon.php
│   └── Repository/ (tous auto-générés)
├── templates/
│   ├── base.html.twig
│   ├── components/
│   │   ├── navbar.html.twig
│   │   └── footer.html.twig
│   ├── home/
│   │   └── index.html.twig
│   ├── front/
│   │   ├── product/
│   │   ├── cart/
│   │   └── account/
│   ├── admin/
│   │   └── dashboard/
│   └── security/
│       ├── login.html.twig
│       └── register.html.twig
├── public/
│   └── styles/
│       └── app.css (Design Liquid Glass)
└── config/packages/security.yaml
```

## 🎨 Design System - Liquid Glass

Le projet utilise un design system **Liquid Glass** unique:

### Palette de couleurs
- Background: Dégradés sombres `#0a0a0a` → `#1a1a2e`
- Primaire: Violet `#8b5cf6`
- Secondaire: Bleu électrique `#6366f1`
- Accent: Cyan `#06b6d4`

### Composants disponibles
- `.glass-card` - Cards avec effet glass
- `.btn-glass` - Boutons avec effet glass
- `.btn-primary-glow` - Boutons avec glow effect
- `.form-control-glass` - Inputs avec glass effect
- `.navbar-glass` - Navigation avec backdrop blur
- `.product-card` - Cards produits spéciales
- `.text-gradient` - Texte avec gradient

### Fonts
- Headings: **Outfit** (700-800)
- Body: **Inter** (400-500)

## 🔐 Sécurité

- **Authentification**: Email + Password
- **Hashage**: Argon2id
- **Protection CSRF**: Activée sur tous les formulaires
- **Rôles**:
  - `ROLE_USER` - Utilisateurs standards
  - `ROLE_ADMIN` - Administrateurs
- **Routes protégées**:
  - `/admin/*` → ROLE_ADMIN
  - `/account/*` → ROLE_USER

## 🛣️ Routes disponibles

| Route | URL | Accès |
|-------|-----|-------|
| Page d'accueil | `/` | Public |
| Produits | `/products` | Public |
| Panier | `/cart` | Public |
| Login | `/login` | Public |
| Register | `/register` | Public |
| Mon compte | `/account` | ROLE_USER |
| Mes commandes | `/account/orders` | ROLE_USER |
| Ma wishlist | `/account/wishlist` | ROLE_USER |
| Dashboard Admin | `/admin` | ROLE_ADMIN |

## 🛠️ Commandes utiles

```bash
# Vider le cache
php bin/console cache:clear

# Voir toutes les routes
php bin/console debug:router

# Créer une nouvelle entité
php bin/console make:entity

# Créer une migration
php bin/console make:migration

# Exécuter les migrations
php bin/console doctrine:migrations:migrate

# Créer un contrôleur
php bin/console make:controller

# Créer les utilisateurs de test
php bin/console app:create-admin
```

## ✅ Corrections apportées

### Problèmes résolus

1. ✅ **Templates manquants** - Tous les templates Twig créés
2. ✅ **CSS non accessible** - Copié de `assets/` vers `public/`
3. ✅ **Routes manquantes** - Toutes les routes configurées
4. ✅ **Utilisateurs de test** - Commande créée pour générer admin et user
5. ✅ **Namespace Admin** - Corrigé dans DashboardController
6. ✅ **Configuration sécurité** - Firewall et providers configurés
7. ✅ **Erreur manifest.json / Webpack Encore** - WebpackEncoreBundle désinstallé, AssetMapper seul (voir [FIX_WEBPACK_ENCORE.md](FIX_WEBPACK_ENCORE.md))

### Fichiers créés/corrigés

**Templates créés:**
- `templates/front/product/list.html.twig`
- `templates/front/cart/index.html.twig`
- `templates/front/account/index.html.twig`
- `templates/front/account/orders.html.twig`
- `templates/front/account/wishlist.html.twig`
- `templates/security/login.html.twig`
- `templates/security/register.html.twig`
- `templates/admin/dashboard/index.html.twig`

**Commandes créées:**
- `src/Command/CreateAdminCommand.php`

**Fichiers corrigés:**
- `src/Controller/Admin/DashboardController.php` (namespace)
- `public/styles/app.css` (copié depuis assets)

## 📝 Prochaines étapes

Pour continuer le développement:

1. **Fixtures** - Créer des données de test pour les produits
2. **Formulaires** - Implémenter les forms d'inscription/login fonctionnels
3. **CRUD Admin** - Développer les interfaces de gestion
4. **Système panier** - Implémenter la logique métier
5. **Checkout** - Créer le processus de commande
6. **Services** - Développer CartService, OrderService, etc.
7. **API REST** - Optionnel pour le panier temps réel

## 📄 Licence

MIT

## 👤 Auteur

Développé pour **Esmer Shop**

---

**Enjoy coding! 🚀**
