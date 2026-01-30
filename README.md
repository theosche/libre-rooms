# LibreRooms

**LibreRooms** est une application open source de gestion de réservation de salles, développée en Laravel. Elle permet aux organisations de mettre à disposition leurs espaces (salles de réunion, locaux associatifs, studios, etc.) et de gérer les réservations de manière simple et efficace.

## Fonctionnalités

### Gestion des salles
- Création et configuration de salles avec description, adresse et coordonnées GPS
- Galerie d'images pour chaque salle
- Tarification flexible : prix fixe ou libre participation
- Prix différenciés pour réservations courtes / journée complète
- Réductions configurables (pourcentage ou montant fixe)
- Options payantes par réservation
- Champs personnalisés pour collecter des informations supplémentaires
- Charte d'utilisation (texte ou lien)
- Visibilité publique ou restreinte

### Réservations
- Calendrier interactif avec visualisation des disponibilités
- Réservations multi-créneaux
- Workflow de validation (en attente / confirmée / annulée)
- Génération automatique de factures PDF
- Notifications par email (confirmation, rappel, annulation)

### Intégrations
- **CalDAV** : Synchronisation bidirectionnelle avec calendriers externes (Nextcloud, etc.)
- **OIDC** : Authentification SSO via fournisseurs d'identité (Keycloak, etc.)
- **WebDAV** : Stockage des factures sur serveur distant

### Gestion des utilisateurs
- Hiérarchie de rôles : administrateur global, admin propriétaire, modérateur, viewer
- Gestion des contacts (personnes / organisations)
- Accès restreint à certaines salles

## Prérequis

- PHP 8.3+
- Composer 2.x
- Node.js 20+ et npm
- MariaDB/MySQL ou PostgreSQL
- Apache ou Nginx
- (Optionnel) Serveur CalDAV pour la synchronisation calendrier
- (Optionnel) Serveur SMTP pour l'envoi d'emails

## Installation

### 1. Créer la base de données

```sql
CREATE DATABASE librerooms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'librerooms'@'localhost' IDENTIFIED BY 'votre_mot_de_passe';
GRANT ALL PRIVILEGES ON librerooms.* TO 'librerooms'@'localhost';
FLUSH PRIVILEGES;
```

### 2. Configurer le VirtualHost Apache

Créer un fichier `/etc/apache2/sites-available/librerooms.conf` :

```apache
<IfModule mod_ssl.c>
<VirtualHost *:443>
    ServerName librerooms.example.com
    ServerAdmin webmaster@localhost
    DocumentRoot "/var/www/html/libre-rooms/public"
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined

    Include /etc/letsencrypt/options-ssl-apache.conf
    <IfModule mod_headers.c>
        # Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload"
    </IfModule>

    <Directory "/var/www/html/libre-rooms/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    SSLCertificateFile /etc/letsencrypt/live/<certificate>/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/<certificate>/privkey.pem
</VirtualHost>
</IfModule>

<VirtualHost *:80>
    ServerName librerooms.example.com
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/libre-rooms/public
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined

    RewriteEngine on
    RewriteCond %{SERVER_NAME} = librerooms.example.com
    RewriteRule ^ https://%{SERVER_NAME}%{REQUEST_URI} [END,NE,R=permanent]
</VirtualHost>
```

Activer le site et les modules nécessaires :

```bash
sudo a2ensite librerooms.conf
sudo a2enmod rewrite ssl
sudo systemctl reload apache2
```

### 3. Installer l'application

```bash
# Cloner le dépôt
git clone https://github.com/theosche/libre-rooms
cd libre-rooms

# Installer les dépendances PHP
composer install --no-dev --optimize-autoloader

# Installer les dépendances Node.js et compiler les assets
npm ci
npm run build

# Configurer l'environnement
cp .env.example .env
php artisan key:generate

# Optimiser pour la production
php artisan optimize

# Configurer les permissions
sudo chown -R :www-data .
sudo chmod -R 755 .
sudo chown -R www-data:www-data storage bootstrap/cache .env
```

### 4. Configurer l'environnement

Éditer le fichier `.env` avec vos paramètres :

```env
APP_NAME=LibreRooms
APP_ENV=production
APP_DEBUG=false
APP_URL=https://librerooms.example.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=librerooms
DB_USERNAME=librerooms
DB_PASSWORD=votre_mot_de_passe
```

### 5. Initialiser la base de données

```bash
php artisan migrate
```

### 6. Configuration initiale

Accédez à l'application via votre navigateur. Un assistant de configuration vous guidera pour :

1. Créer le compte administrateur global
2. Configurer les paramètres système (fuseau horaire, devise, langue)
3. Configurer l'envoi d'emails (optionnel)
4. Configurer l'authentification OIDC (optionnel)

## Utilisation

### Premiers pas

1. **Créer un propriétaire** : Un propriétaire représente une entité (association, entreprise) qui possède des salles.

2. **Créer une salle** : Associez une salle à un propriétaire, configurez son adresse, ses tarifs et ses options.

3. **Recevoir des réservations** : Les utilisateurs peuvent réserver via le formulaire public. Selon la configuration, les réservations sont confirmées automatiquement ou nécessitent une validation.

### Rôles utilisateurs

| Rôle | Permissions |
|------|-------------|
| **Admin global** | Accès complet à toute l'application |
| **Admin propriétaire** | Gestion complète des salles du propriétaire |
| **Modérateur** | Validation et gestion des réservations |
| **Viewer** | Consultation des réservations (lecture seule) |

### Configuration CalDAV

Pour synchroniser les réservations avec un calendrier externe :

1. Dans les paramètres du propriétaire, activez CalDAV
2. Renseignez l'URL du serveur, l'utilisateur et le mot de passe
3. Pour chaque salle, spécifiez le nom du calendrier à utiliser

### Configuration OIDC

Pour permettre l'authentification via SSO :

1. Accédez à **Paramètres > Fournisseurs d'identité**
2. Ajoutez un nouveau fournisseur avec les informations de votre serveur OIDC
3. Les utilisateurs pourront se connecter via le bouton correspondant

## Développement

### Installation en mode développement

```bash
composer install
npm install
npm run dev
```

### Commandes utiles

```bash
# Lancer le serveur de développement
composer dev

# Exécuter les tests
composer test

# Vérifier le style du code
./vendor/bin/pint --dirty
```

## Licence

Ce projet est distribué sous licence [MIT](LICENSE).

## Contribution

Les contributions sont les bienvenues ! N'hésitez pas à ouvrir une issue ou une pull request sur GitHub.
