# 📡 Guide Ngrok - Partage du site à distance

## Qu'est-ce que Ngrok ?
Ngrok crée un tunnel sécurisé depuis internet vers votre serveur local, permettant à n'importe qui d'accéder à votre site en développement via une URL publique.

## 🚀 Installation et Configuration

### Étape 1 : Créer un compte Ngrok (gratuit)

1. Allez sur [https://ngrok.com](https://ngrok.com)
2. Cliquez sur **Sign up** (gratuit)
3. Créez votre compte
4. Une fois connecté, allez dans **Your Authtoken** : [https://dashboard.ngrok.com/get-started/your-authtoken](https://dashboard.ngrok.com/get-started/your-authtoken)
5. **Copiez votre authtoken**

### Étape 2 : Configurer l'authtoken

**Option A : Via script automatique**
```bash
# Double-cliquez sur le fichier :
setup_ngrok.bat

# Collez votre authtoken quand demandé
```

**Option B : Manuellement**
```bash
C:\laragon\bin\ngrok\ngrok config add-authtoken VOTRE_AUTHTOKEN_ICI
```

## 🌐 Démarrer le tunnel

### Méthode simple (recommandée)

1. **Démarrez Laragon** (si pas déjà fait)
2. **Double-cliquez sur** : `start_ngrok.bat`
3. Une fenêtre s'ouvrira avec les informations du tunnel
4. **Gardez cette fenêtre ouverte** tant que vous voulez que le site soit accessible

### Méthode manuelle

Ouvrez un terminal et exécutez :

```bash
# Pour HTTP sur port 80 (par défaut Laragon)
ngrok http 80 --host-header=rewrite

# OU si votre site tourne sur un autre port (ex: 8000)
ngrok http 8000 --host-header=rewrite
```

## 📋 Récupérer l'URL publique

Une fois ngrok démarré, vous verrez quelque chose comme :

```
Session Status                online
Account                       votre_email@example.com
Version                       3.x.x
Region                        Europe (eu)
Latency                       45ms
Web Interface                 http://127.0.0.1:4040
Forwarding                    https://xxxx-xxxx-xxxx.ngrok-free.app -> http://localhost:80

Connections                   ttl     opn     rt1     rt5     p50     p90
                              0       0       0.00    0.00    0.00    0.00
```

✅ **L'URL à partager est** : `https://xxxx-xxxx-xxxx.ngrok-free.app`

Cette URL change à chaque fois que vous redémarrez ngrok (gratuit).

## 🎯 Partager votre site

1. **Copiez l'URL** qui commence par `https://` (ligne "Forwarding")
2. **Envoyez-la** à la personne qui doit voir le site
3. Elle pourra accéder au site directement depuis son navigateur
4. **Gardez ngrok ouvert** tant qu'elle doit accéder au site

## 🔧 Options avancées

### URL personnalisée (Plan payant)

Pour avoir une URL fixe comme `https://mon-site.ngrok.app` :
```bash
ngrok http 80 --domain=mon-site.ngrok.app
```

### Authentification basique

Pour protéger l'accès avec un mot de passe :
```bash
ngrok http 80 --basic-auth="utilisateur:motdepasse"
```

### Interface web de monitoring

Ngrok fournit une interface web pour voir les requêtes en temps réel :
- URL : `http://localhost:4040`
- Ouvrez cette URL dans votre navigateur pendant que ngrok tourne

## ⚠️ Important

### À faire AVANT de démarrer ngrok :

1. ✅ **Laragon doit être démarré**
2. ✅ **Votre site doit être accessible localement** (`http://localhost` ou `http://esmer_shop.test`)
3. ✅ **La base de données doit être démarrée** (MySQL/MariaDB)

### Limitations compte gratuit :

- ⏰ Session limitée (expiration après 8h)
- 🔄 URL change à chaque redémarrage
- 👥 1 tunnel actif à la fois
- 📊 40 connexions/minute

### Sécurité :

- 🔒 Utilisez HTTPS (fourni automatiquement par ngrok)
- 🚫 **NE PARTAGEZ PAS** l'URL publiquement (seulement avec personnes de confiance)
- 🔐 Ajoutez une authentification pour données sensibles
- ⏹️ **Arrêtez ngrok** quand vous ne l'utilisez plus

## 🛑 Arrêter le tunnel

- Appuyez sur `Ctrl + C` dans le terminal ngrok
- Ou fermez simplement la fenêtre

L'URL deviendra immédiatement inaccessible.

## 🔍 Dépannage

### "ERR_NGROK_108"
- ➡️ Vous n'avez pas configuré l'authtoken
- ➡️ Solution : Exécutez `setup_ngrok.bat`

### "Connection refused"
- ➡️ Laragon n'est pas démarré
- ➡️ Mauvais port (vérifiez sur quel port tourne votre site)

### "Host header does not match"
- ➡️ Ajoutez `--host-header=rewrite` à votre commande

### Le site s'affiche mal / erreurs CSS/JS
- ➡️ Vérifiez que les assets sont bien compilés
- ➡️ Videz le cache : `php bin/console cache:clear`

## 📞 Support

- Documentation officielle : [https://ngrok.com/docs](https://ngrok.com/docs)
- Dashboard ngrok : [https://dashboard.ngrok.com](https://dashboard.ngrok.com)

---

**Astuce** : Pour un partage longue durée, considérez :
- Déploiement temporaire sur Heroku (gratuit)
- Compte ngrok Pro (URL fixe + plus de fonctionnalités)
- Serveur VPS temporaire
