# Créateur PMP

Un outil en ligne pour créer des Patient-Management Problems (PMP) et générer des scénarios cliniques réalistes.

## Démo

Une version de démonstration est disponible ici :
https://focused-swartz.102-211-208-104.plesk.page

## Configuration

1. Clonez le repository
2. Installez les dépendances via Composer :
   ```bash
   composer install
   ```
3. Initialisez la base de données MariaDB en important le fichier initdb.sql :
   ```bash
   mysql -u your_username -p your_database_name < initdb.sql
   ```
4. Copiez le fichier `config.php.example` vers `config.php` et configurez vos paramètres :
   ```php
   // Database configuration
   define('DB_HOST', 'your_host');
   define('DB_NAME', 'your_database_name');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   define('DB_CHARSET', 'utf8mb4');

   // SMTP configuration
   define('SMTP_HOST', 'your_smtp_host');
   define('SMTP_USER', 'your_smtp_user');
   define('SMTP_PASS', 'your_smtp_password');
   define('SMTP_PORT', 465);
   define('SMTP_FROM', 'your_from_email');
   define('SMTP_FROM_NAME', 'Créateur PMP');
   define('SMTP_REPLY_TO', 'your_reply_to_email');
   define('SMTP_REPLY_TO_NAME', 'Information');
   ```

## Prérequis

- PHP 7.4 ou supérieur
- MySQL/MariaDB
- Serveur SMTP pour l'envoi d'emails
- Permissions MySQL pour créer et modifier des bases de données

## Utilisation

1. Connectez-vous à l'application
2. Créez vos scénarios PMP
3. Gérez vos problèmes cliniques

## Sécurité

Le fichier `config.php` contient des informations sensibles et est exclu du gestionnaire de version via `.gitignore`. Assurez-vous de ne jamais le commiter dans le repository.

## Licence

Ce projet est sous licence GNU General Public License v3.0 - voir le fichier [LICENSE](LICENSE) pour plus de détails.
