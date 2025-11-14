# Projet E-commerce Chaussures - Symfony 6

## Vue d'ensemble
Boutique en ligne premium de chaussures avec design hybride - page d'accueil dynamique style Footlocker (avec couleurs Apple) et reste du site minimaliste Apple.

## Stack Technique
- **Framework**: Symfony 6.4
- **Base de données**: MySQL/MariaDB
- **ORM**: Doctrine
- **Templates**: Twig
- **Assets**: Webpack Encore
- **Frontend**: Tailwind CSS + SCSS custom + Alpine.js/Stimulus
- **Animations**: AOS/GSAP
- **Icônes**: Heroicons ou Lucide

## Architecture

### Entités principales
- `User` - Utilisateurs (clients et admins)
- `Product` - Produits (chaussures)
- `Category` - Catégories de produits
- `Brand` - Marques
- `ProductVariant` - Variations (tailles, couleurs, stock)
- `Cart` / `CartItem` - Panier
- `Order` / `OrderItem` - Commandes
- `Address` - Adresses de livraison
- `Review` - Avis clients
- `Coupon` - Codes promotionnels

### Services clés
- `CartService` - Gestion du panier
- `OrderService` - Gestion des commandes
- `PaymentService` - Traitement des paiements
- `InventoryService` - Gestion des stocks
- `EmailService` - Notifications par email

## Fonctionnalités

### Frontend Public
- **Accueil**: Collections mises en avant, nouveautés, promotions
- **Catalogue**: Filtres dynamiques (marque, prix, taille, couleur, catégorie)
- **Recherche**: Recherche avancée avec suggestions
- **Page Produit**: Galerie images, sélection variantes, avis, produits similaires
- **Panier**: Mise à jour temps réel, calcul automatique
- **Checkout**: Processus en 3 étapes (adresse, livraison, paiement)
- **Compte client**: Profil, commandes, adresses, wishlist, avis
- **Wishlist**: Sauvegarde favoris

### Backend Admin
- **Dashboard**: Statistiques ventes, graphiques, KPIs
- **Produits**: CRUD complet avec upload images multiples
- **Catégories & Marques**: Gestion complète
- **Commandes**: Suivi et gestion des statuts
- **Clients**: Liste et détails
- **Coupons**: Création et gestion codes promo
- **Paramètres**: Configuration boutique

## Design System - Hybride Footlocker + Apple

### Palette de couleurs (unifiée)
- **Background**: `#ffffff` (blanc pur)
- **Background Alt**: `#f5f5f7` (gris très clair Apple)
- **Text Primary**: `#1d1d1f` (noir intense)
- **Text Secondary**: `#6e6e73` (gris moyen)
- **Accent Blue**: `#007AFF` (bleu Apple - remplace orange Footlocker)
- **Accent Hover**: `#0051D5` (bleu foncé)
- **Border**: `#d2d2d7` (gris bordure)
- **Success**: `#34C759` (vert badges "New")

### Page d'accueil - Style Footlocker énergique
**Principes** :
- Typographie Oswald (700) - bold, uppercase, impactant
- Hero sombre avec overlay et image background
- Grilles denses de produits (gap 2rem)
- Hover dynamique : translateY(-5px) + bordure bleue
- Zoom d'image prononcé (scale 1.08)
- Badges en haut à gauche
- Labels uppercase avec spacing généreux

**Composants** :
- Buttons rectangulaires uppercase avec padding large
- Cards produits avec bordures qui deviennent bleues au hover
- Bannière bleue pleine largeur
- Features avec bordures 2px

### Reste du site - Style Apple minimaliste
**Principes** :
- Typographie Inter/SF Pro - subtile et lisible
- Espacements généreux et respiration
- Transitions fluides (0.25s)
- Ombres légères
- Buttons arrondis en pilule

**Composants** :
- Cards produits avec bordures fines et radius 16px
- Buttons arrondis (border-radius: 980px)
- Grilles aérées
- Badges arrondis en haut à droite

## Structure des dossiers
```
src/
├── Controller/
│   ├── Admin/
│   │   ├── DashboardController.php
│   │   ├── ProductController.php
│   │   ├── OrderController.php
│   │   └── ...
│   ├── CartController.php
│   ├── CheckoutController.php
│   ├── ProductController.php
│   └── ...
├── Entity/
├── Repository/
├── Form/
├── Service/
└── Security/

templates/
├── admin/
│   ├── dashboard/
│   ├── product/
│   └── ...
├── front/
│   ├── home/
│   ├── product/
│   ├── cart/
│   ├── checkout/
│   └── ...
├── components/
│   ├── navbar.html.twig
│   ├── footer.html.twig
│   ├── product-card.html.twig
│   └── ...
└── base.html.twig

assets/
├── styles/
│   ├── app.scss
│   ├── components/
│   ├── pages/
│   └── utils/
├── scripts/
│   ├── app.js
│   ├── cart.js
│   └── ...
└── images/
```

## Sécurité
- **Authentication**: Security component Symfony
- **Password**: Argon2id hashing
- **CSRF**: Protection sur tous les formulaires
- **Roles**: ROLE_USER, ROLE_ADMIN
- **Validation**: Assertions Symfony sur toutes les entités
- **XSS**: Échappement automatique Twig
- **SQL Injection**: Doctrine prepared statements

## Standards de code
- **PSR-12**: Code style
- **PHPDoc**: Documentation complète
- **Type hints**: Strict sur tous les paramètres/retours
- **Services**: Autowiring et injection de dépendances
- **Formulaires**: Form types pour tous les forms
- **Validation**: Contraintes Symfony

## Configuration recommandée

### .env
```env
DATABASE_URL="mysql://user:password@127.0.0.1:3306/shoe_store?serverVersion=8.0"
MAILER_DSN=smtp://localhost:1025
```

### Fixtures
- Au moins 30 produits de chaussures variés
- 5 catégories (Running, Casual, Sport, Luxe, Sneakers)
- 10 marques populaires
- 3 utilisateurs admin
- 20 commandes de test avec différents statuts

## Pages essentielles
1. **Homepage** (`/`) - Vitrine attractive
2. **Catalogue** (`/products`) - Tous les produits avec filtres
3. **Produit** (`/product/{slug}`) - Détails complet
4. **Panier** (`/cart`) - Gestion panier
5. **Checkout** (`/checkout`) - Processus commande
6. **Compte** (`/account`) - Espace client
7. **Admin** (`/admin`) - Panel administration

## Priorités de développement
1. ✅ Setup projet + entities + relations
2. ✅ Authentication système
3. ✅ CRUD admin produits
4. ✅ Frontend catalogue + filtres
5. ✅ Système panier
6. ✅ Checkout process
7. ✅ Design liquid glass appliqué partout
8. ✅ Animations et micro-interactions
9. ✅ Tests et fixtures
10. ✅ Documentation finale

## Notes importantes
- **Mobile-first**: Responsive sur tous les écrans
- **Performance**: Lazy loading images, optimisation assets
- **SEO**: Meta tags, structured data pour produits
- **Accessibilité**: ARIA labels, navigation clavier
- **UX**: Messages flash, loading states, error handling
- **Design hybride**: Page d'accueil énergique style Footlocker avec couleurs Apple harmonisées + reste du site minimaliste Apple

## Commandes utiles
```bash
# Installation
composer install
npm install

# Base de données
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load

# Assets
npm run dev
npm run watch
npm run build

# Serveur
symfony server:start
```

---

**Objectif**: Créer une expérience d'achat attractive et professionnelle - page d'accueil énergique pour capter l'attention (style Footlocker) + parcours d'achat épuré et rassurant (style Apple) avec une palette de couleurs unifiée.