# GitHub Actions Deployment Guide

## Configuration

Ce workflow GitHub Actions déploie automatiquement le projet sur un serveur Ubuntu via SSH.

### Méthode d'authentification

Deux options sont disponibles :

1. **Authentification par clé SSH** (recommandée et plus sécurisée)
2. **Authentification par mot de passe** (plus simple mais moins sécurisé)

---

## Option 1 : Authentification par clé SSH (Recommandée)

#### 1. Générer une clé SSH sur votre serveur (si pas déjà existante)

```bash
ssh-keygen -t ed25519 -f ~/.ssh/github_deploy -C "github-deploy"
```

#### 2. Configurer les secrets GitHub

Dans votre dépôt GitHub, allez à **Settings > Secrets and variables > Actions** et ajoutez les secrets suivants :

| Secret | Description | Exemple |
|--------|-------------|---------|
| `SSH_PRIVATE_KEY` | Clé privée SSH (contenu du fichier `~/.ssh/github_deploy`) | `-----BEGIN OPENSSH PRIVATE KEY-----...` |
| `SSH_HOST` | Adresse IP ou domaine du serveur | `192.168.1.100` ou `deploy.example.com` |
| `SSH_USER` | Utilisateur SSH | `deployer` |
| `SSH_PORT` | Port SSH (défaut: 22) | `22` |
| `DEPLOY_PATH` | Chemin d'accès au projet sur le serveur | `/var/www/stock_auto` |
| `SLACK_WEBHOOK` | (Optionnel) Webhook Slack pour les notifications | `https://hooks.slack.com/services/...` |

#### 3. Ajouter la clé publique au serveur

Connectez-vous à votre serveur Ubuntu :

```bash
mkdir -p ~/.ssh
cat ~/.ssh/github_deploy.pub >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
chmod 700 ~/.ssh
```

---

## Option 2 : Authentification par mot de passe

### ⚠️ Avertissement de sécurité

L'authentification par mot de passe en GitHub Actions présente des risques :
- Le mot de passe est stocké en texte brut dans les secrets GitHub
- Moins sécurisé qu'une clé SSH
- Il est recommandé d'utiliser une clé SSH à la place

**Utilisez cette option seulement si vous ne pouvez pas configurer les clés SSH.**

### Configuration avec mot de passe

1. Commentez/supprimez la clé `SSH_PRIVATE_KEY` du secret GitHub
2. Ajoutez les secrets suivants dans **Settings > Secrets and variables > Actions** :

| Secret | Description | Exemple |
|--------|-------------|---------|
| `SSH_PASSWORD` | Mot de passe SSH de l'utilisateur | `votre_mot_de_passe` |
| `SSH_HOST` | Adresse IP ou domaine du serveur | `192.168.1.100` |
| `SSH_USER` | Utilisateur SSH | `deployer` |
| `SSH_PORT` | Port SSH (défaut: 22) | `22` |
| `DEPLOY_PATH` | Chemin d'accès au projet | `/var/www/stock_auto` |
| `DATABASE_URL` | URL de connexion à la base de données | `mysql://user:pass@host:3306/dbname?serverVersion=8.2` |
| `APP_SECRET` | Secret Symfony unique | `cc3a5eb2061c7f5b320e2bf0e26a8581` |
| `JWT_PASSPHRASE` | Passphrase JWT pour l'authentification | `your-jwt-passphrase` |
| `CORS_ALLOW_ORIGIN` | Pattern CORS pour la production | `^https?://yourdomain\.com(:[0-9]+)?$` |

Le workflow est déjà configuré pour utiliser `sshpass` automatiquement.

### Sécurité additionnelle

Pour plus de sécurité avec les mots de passe :
- Utilisez un mot de passe fort et complexe
- Limitez l'accès SSH à GitHub (impossible, donc utilisez plutôt une clé SSH)
- Changez le port SSH par défaut (22) vers un autre port
- Activez la 2FA sur votre compte GitHub

---

## Configuration serveur (commune aux deux méthodes)

#### Vérifier les permissions sur le serveur

Assurez-vous que l'utilisateur SSH a les permissions nécessaires :

```bash
# Propriétaire du projet
sudo chown -R deployer:deployer /var/www/stock_auto

# Permissions
sudo chmod -R 755 /var/www/stock_auto
sudo chmod -R 755 /var/www/stock_auto/public
sudo chmod -R 777 /var/www/stock_auto/var
sudo chmod -R 777 /var/www/stock_auto/public/uploads
```

#### Générer les secrets de production requis

Avant le premier déploiement, générez les secrets uniques :

```bash
# APP_SECRET (chaîne hex aléatoire)
php -r 'echo bin2hex(random_bytes(16)) . "\n";'

# JWT_PASSPHRASE (chaîne aléatoire pour JWT)
php -r 'echo bin2hex(random_bytes(32)) . "\n";'
```

Copier les valeurs générées dans les secrets GitHub correspondants.

#### Installer les dépendances sur le serveur

```bash
sudo apt-get update
sudo apt-get install -y php php-cli php-fpm php-mysql git curl npm

# Composer (global)
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```

---

## Utilisation

Pour déclencher un déploiement manuellement sans pusher du code :

1. Allez à **Actions** dans votre dépôt GitHub
2. Sélectionnez **Deploy to Ubuntu Server**
3. Cliquez sur **Run workflow** > **Run workflow**

### Événements de déploiement

Le workflow se déclenche automatiquement :
- Sur un **push** vers `main` ou `master`
- Manuellement via **workflow_dispatch**

### Notifications Slack

Pour activer les notifications Slack :

1. Créez un **Incoming Webhook** : https://api.slack.com/apps
2. Ajoutez l'URL du webhook dans les secrets GitHub sous `SLACK_WEBHOOK`

### Troubleshooting

#### Erreur : "Permission denied (publickey)"
- Vérifiez que la clé publique est bien ajoutée à `~/.ssh/authorized_keys`
- Testez localement : `ssh -i ~/.ssh/github_deploy deployer@your-server-ip`

#### Erreur : "composer: command not found"
- Installez Composer sur le serveur : `curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer`

#### Erreur : "Permission denied" sur les dossiers
- Vérifiez les permissions du dossier `/var/www/stock_auto` et ses sous-dossiers

### Logs du déploiement

Consultez les logs du workflow dans l'onglet **Actions** de votre dépôt GitHub pour toutes les étapes exécutées.
