# Configuration KKiaPay

## Étapes de configuration

### 1. Créer un compte KKiaPay
1. Allez sur https://kkiapay.me
2. Créez un compte marchand
3. Récupérez vos clés API (Public Key et Secret Key)

### 2. Configuration dans Symfony

Ajoutez les variables suivantes dans votre fichier `.env.local` :

```env
# KKiaPay Configuration
KKIAPAY_PUBLIC_KEY=votre_cle_publique_ici
KKIAPAY_SANDBOX=true  # false en production
```

### 3. Mise à jour du template

Le template `templates/front/checkout/index.html.twig` utilise ces variables:
- `{{ kkiapay_public_key }}` - Votre clé publique KKiaPay
- `{{ kkiapay_sandbox }}` - Mode sandbox (true/false)

Pour les rendre disponibles, ajoutez dans `config/packages/twig.yaml`:

```yaml
twig:
    globals:
        kkiapay_public_key: '%env(KKIAPAY_PUBLIC_KEY)%'
        kkiapay_sandbox: '%env(bool:KKIAPAY_SANDBOX)%'
```

### 4. Conversion de devise

Le système convertit automatiquement EUR → XOF (Franc CFA):
- Taux utilisé : 1 EUR ≈ 655 XOF
- Si votre boutique utilise une autre devise, modifiez le taux dans le fichier `checkout/index.html.twig` ligne 130

### 5. Mode Sandbox

En mode sandbox, vous pouvez tester avec ces numéros:
- **Mobile Money réussi**: +229 97000001
- **Mobile Money échoué**: +229 97000002

### 6. Flux de paiement

1. Client remplit le formulaire de livraison
2. Clique sur "Payer avec KKiaPay"
3. Widget KKiaPay s'ouvre
4. Client entre son numéro et valide
5. En cas de succès:
   - Notification de succès
   - Création de la commande
   - Redirection vers la page de confirmation
6. En cas d'échec:
   - Notification d'erreur
   - Possibilité de réessayer

## Informations techniques

### Intégration
- SDK: https://cdn.kkiapay.me/k.js
- Documentation: https://docs.kkiapay.me

### Sécurité
- Les paiements sont traités directement par KKiaPay
- Aucune information bancaire n'est stockée sur votre serveur
- Transaction ID est stocké dans la commande pour traçabilité

### Support
- Email KKiaPay: support@kkiapay.me
- Discord: https://discord.gg/kkiapay
