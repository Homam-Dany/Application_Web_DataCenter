# <div align="center"><img src="https://raw.githubusercontent.com/FortAwesome/Font-Awesome/6.x/svgs/solid/server.svg" width="40" height="40" style="margin-right: 10px; vertical-align: middle;" /> DC-Manager : Next-Gen Infrastructure Orchestrator</div>

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-Bundler-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![AI Ready](https://img.shields.io/badge/AI-Ready-10A37F?style=for-the-badge&logo=openai&logoColor=white)

**La solution de gestion de Data Center ultime, propulsée par l'Intelligence Artificielle.**
*Performance native. Architecture Événementielle. Sécurité de niveau entreprise.*

[🚀 Démo](#) • [📚 Documentation](#) • [🐛 Signaler un bug](https://github.com/Homam-Dany/Application_Web_DataCenter/issues)

---
</div>

## 🌟 Vision du Projet

**DC-Manager** n'est pas qu'un simple outil de gestion ; c'est une refonte complète de l'expérience d'administration IT. Conçu pour le **Data Center FST Tanger**, il remplace les processus manuels et les interfaces datées par une plateforme fluide, réactive et intelligente.

> **Notre philosophie :** "La complexité du backend doit être invisible derrière une élégance frontend absolue."

## 🔥 Fonctionnalités "Flagship"

| Module | Description | Innovation |
| :--- | :--- | :--- |
| **🤖 Assistant DataCenter** | Chatbot IA intégré nativement (sans iFrame) pour le support 24/7. | Détection de contexte, suggestions dynamiques, architecture *Event-Delegated*. |
| **⚡ Performance UI** | Interface **Glassmorphism** sans frameworks lourds (No Bootstrap/jQuery). | Score Lighthouse > 95. Chargement instantané via Vite.js. |
| **🛡️ Sécurité IAM** | Gestion des identités et des accès (RBAC) granulaire. | Workflow d'approbation stricte, Logs d'audit immuables, Hard Delete RGPD. |
| **📅 Smart Booking** | Moteur de réservation de ressources avec détection de conflits. | Algorithme de vérification temporelle en temps réel. |
| **🔔 Live Notifications** | Système proactif d'alertes (Email SMTP + In-App). | Rappels d'expiration, confirmations de compte, alertes incidents. |

## 🛠 Architecture Technique

Ce projet démontre une maîtrise avancée de l'écosystème **Laravel Fullstack** :

- **Backend :** Laravel 10 (MVC, Service Layer Pattern, Mailables, Notifications, Policies).
- **Frontend :** Vanilla JS (ES6+) pour une légèreté maximale, CSS Custom (Variables, Grid/Flexbox).
- **Build System :** Vite pour le HMR (Hot Module Replacement) et la compilation optimisée des assets.
- **Base de Données :** MySQL avec schéma relationnel normalisé (3NF).

## 🚀 Guide d'Installation Rapide

### Prérequis
- PHP 8.1 ou supérieur
- Composer & NPM
- Serveur de base de données (MySQL/MariaDB)

### Déploiement

```bash
# 1. Cloner le repository
git clone https://github.com/Homam-Dany/Application_Web_DataCenter.git
cd Application_Web_DataCenter

# 2. Installer les dépendances Backend & Frontend
composer install
npm install

# 3. Configuration de l'environnement
cp .env.example .env
php artisan key:generate

# 4. Configuration Base de données (dans .env)
# DB_DATABASE=votre_db
# DB_USERNAME=votre_user
# DB_PASSWORD=votre_password

# 5. Migration et Seed (Données de test)
php artisan migrate --seed

# 6. Compilation et Lancement
npm run build
php artisan serve
```

## 👥 L'Équipe (La "Tech Team")

Projet réalisé dans le cadre de la **Licence en Ingénierie de Développement d'Applications Informatiques (IDAI)** à la **FST Tanger**.

<div align="center">

| Membre | Rôle | Expertise Clé |
| :--- | :--- | :--- |
| **Homam Dany** | Lead Developer | Fullstack Architecture, AI Integration, Security |

</div>

---

<div align="center">
    <i>"Code is poetry." - Une réalisation académique d'excellence.</i>
    <br>
    © 2026 Université Abdelmalek Essaâdi
</div>