# 🚂 Guide Complet : Héberger sur Railway.app

## 🎯 **Étape 1 : Préparation (2 minutes)**

### **A. Créez un compte GitHub (si vous n'en avez pas)**
1. Allez sur [github.com](https://github.com)
2. Cliquez "Sign up"
3. Créez votre compte gratuit

### **B. Uploadez votre code sur GitHub**
1. Créez un nouveau repository
2. Uploadez tous les fichiers de votre projet
3. Notez l'URL de votre repository

## 🚀 **Étape 2 : Créer un compte Railway (3 minutes)**

### **A. Inscription**
1. Allez sur [railway.app](https://railway.app)
2. Cliquez **"Start a New Project"**
3. Connectez-vous avec votre compte GitHub
4. Autorisez Railway à accéder à vos repositories

### **B. Vérification**
- Railway vous donnera **500 heures gratuites par mois**
- Pas besoin de carte de crédit pour commencer !

## 🗄️ **Étape 3 : Créer la Base de Données (2 minutes)**

### **A. Ajout MySQL**
1. Dans votre dashboard Railway
2. Cliquez **"+ New"**
3. Sélectionnez **"Database"**
4. Choisissez **"MySQL"**
5. Attendez 30 secondes que la base soit créée

### **B. Récupérer les informations**
1. Cliquez sur votre base MySQL
2. Allez dans l'onglet **"Connect"**
3. Notez ces informations :
   - **Host** (ex: containers-us-west-xxx.railway.app)
   - **Port** (ex: 6543)
   - **Database** (ex: railway)
   - **Username** (ex: root)
   - **Password** (généré automatiquement)

## 📁 **Étape 4 : Déployer votre Site (3 minutes)**

### **A. Nouveau Service Web**
1. Retournez au dashboard
2. Cliquez **"+ New"**
3. Sélectionnez **"GitHub Repo"**
4. Choisissez votre repository avec le code du site

### **B. Configuration automatique**
- Railway détecte automatiquement que c'est du PHP
- Le déploiement commence automatiquement
- Attendez 2-3 minutes

## ⚙️ **Étape 5 : Variables d'Environnement (5 minutes)**

### **A. Accès aux variables**
1. Cliquez sur votre service web
2. Allez dans l'onglet **"Variables"**
3. Cliquez **"+ New Variable"**

### **B. Ajoutez ces variables une par une :**

```
DB_HOST = [votre-host-mysql]
DB_NAME = railway
DB_USER = root
DB_PASS = [votre-password-mysql]
DB_PORT = [votre-port-mysql]

STRIPE_PUBLISHABLE_KEY = pk_test_...
STRIPE_SECRET_KEY = sk_test_...
STRIPE_WEBHOOK_SECRET = whsec_...

SMTP_HOST = smtp.gmail.com
SMTP_PORT = 587
SMTP_USER = votre-email@gmail.com
SMTP_PASS = votre-mot-de-passe-app
```

### **C. Redémarrage**
- Après avoir ajouté les variables
- Cliquez **"Deploy"** pour redémarrer

## 🗃️ **Étape 6 : Initialiser la Base de Données (2 minutes)**

### **A. Accès à la console MySQL**
1. Retournez à votre base MySQL
2. Cliquez sur l'onglet **"Query"**
3. Copiez-collez le contenu du fichier `supabase/migrations/20250703012500_fancy_sky.sql`
4. Cliquez **"Execute"**

### **B. Vérification**
- Vos tables sont maintenant créées
- Les données d'exemple sont ajoutées

## 🎉 **Étape 7 : Tester votre Site (1 minute)**

### **A. Obtenir l'URL**
1. Dans votre service web
2. Allez dans l'onglet **"Settings"**
3. Trouvez **"Public Networking"**
4. Votre URL sera quelque chose comme : `https://votre-site.up.railway.app`

### **B. Test complet**
1. Ouvrez l'URL dans votre navigateur
2. Testez le formulaire de don
3. Vérifiez que tout fonctionne !

## 💳 **Étape 8 : Configuration Stripe (10 minutes)**

### **A. Compte Stripe**
1. Allez sur [stripe.com](https://stripe.com)
2. Créez un compte gratuit
3. Activez le mode test

### **B. Récupérer les clés**
1. Dashboard Stripe → **"Developers"** → **"API Keys"**
2. Copiez :
   - **Publishable key** (pk_test_...)
   - **Secret key** (sk_test_...)

### **C. Webhook**
1. **"Developers"** → **"Webhooks"**
2. **"Add endpoint"**
3. URL : `https://votre-site.up.railway.app/api/webhook.php`
4. Événements : `payment_intent.succeeded`, `invoice.payment_succeeded`
5. Copiez le **Signing secret** (whsec_...)

### **D. Mise à jour des variables**
- Retournez sur Railway
- Mettez à jour vos variables Stripe
- Redéployez

## 📧 **Étape 9 : Configuration Email Gmail (5 minutes)**

### **A. Mot de passe d'application**
1. Allez dans votre compte Google
2. **"Sécurité"** → **"Mots de passe des applications"**
3. Générez un mot de passe pour "Mail"
4. Notez ce mot de passe (16 caractères)

### **B. Variables email**
```
SMTP_USER = votre-email@gmail.com
SMTP_PASS = [mot-de-passe-application-16-caractères]
```

## ✅ **Vérification Finale**

### **Checklist :**
- [ ] Site accessible via l'URL Railway
- [ ] Base de données connectée
- [ ] Formulaire de don fonctionne
- [ ] Stripe configuré (mode test)
- [ ] Emails envoyés
- [ ] Responsive sur mobile

## 🎯 **Résultat Final**

Votre site sera :
- ✅ **100% gratuit** (dans les limites Railway)
- ✅ **HTTPS automatique**
- ✅ **Sauvegarde automatique**
- ✅ **Monitoring inclus**
- ✅ **Prêt pour la production**

## 🆘 **En cas de Problème**

### **Erreurs communes :**

**1. "Application failed to respond"**
- Vérifiez les variables d'environnement
- Regardez les logs dans Railway

**2. "Database connection failed"**
- Vérifiez DB_HOST, DB_USER, DB_PASS
- Assurez-vous que la base MySQL est active

**3. "Stripe error"**
- Vérifiez les clés Stripe
- Assurez-vous d'être en mode test

### **Support :**
- 💬 Discord Railway : Support communautaire
- 📧 Support Railway : help@railway.app
- 🎥 YouTube : "Railway deployment tutorial"

## 💰 **Coûts Prévisionnels**

### **Gratuit (0€/mois) :**
- Jusqu'à 500h d'utilisation
- Base de données incluse
- Parfait pour commencer

### **Si vous dépassez (5$/mois) :**
- Usage illimité
- Plus de ressources
- Support prioritaire

**Votre site de dons sera en ligne dans 30 minutes maximum !** 🚀