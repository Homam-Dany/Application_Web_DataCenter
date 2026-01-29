# <img src="https://raw.githubusercontent.com/FortAwesome/Font-Awesome/6.x/svgs/solid/server.svg" width="30" height="30" /> DC-Manager : Infrastructure & Resource Orchestrator

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![OpenAI](https://img.shields.io/badge/OpenAI-412991?style=for-the-badge&logo=openai&logoColor=white)

**La solution ultime de gestion de Data Center, enrichie par l'Intelligence Artificielle.**
*Performance native, Pas de jQuery, Pas de Bootstrap. Juste du code propre.*

[Fonctionnalités](#-fonctionnalités-clés) • [IA & Chatbot](#-assistant-ia-intelligent) • [Installation](#-guide-dinstallation) • [Équipe](#-équipe)

---
</div>

## 🎯 Vision & Innovation

**DC-Manager** redéfinit la gestion d'infrastructure IT en combinant une interface utilisateur ultra-moderne ("Glassmorphism", Dark Mode natif) avec des fonctionnalités avancées d'automatisation. Contrairement aux solutions classiques, nous avons banni les dépendances lourdes pour offrir une expérience **ultra-rapide** et **sécurisée**.

## 🚀 Fonctionnalités Clés

### 🧠 Assistant IA Intelligent (Nouveau)
- **Chatbot Autonome** : Intégré nativement dans l'interface, il répond 24/7 aux questions des utilisateurs.
- **Support Hybride** :
    - **Mode Autonome** : Répond aux questions fréquentes (réservations, pannes, règles) sans coût API.
    - **Mode GPT** : Connectable à OpenAI pour une intelligence illimitée.
- **Suggestions Rapides** : Interface conversationnelle moderne avec puces de suggestions contextuelles.

### � Système de Notifications Avancé
- **Emails Transactionnels** : Notifications SMTP temps réel pour l'activation de compte et les refus.
- **Magic Links** : Connexion sécurisée sans mot de passe via lien unique temporaire.
- **Alertes de Refus** : Envoi de justifications détaillées en cas de rejet d'une demande d'accès.

### 🛡️ Administration & Sécurité
- **Gestion des Utilisateurs 2.0** :
    - Workflow de validation des comptes (Approuver / Refuser avec motif).
    - Separation claire des comptes Actifs, En Attente et Refusés.
    - Suppression définitive sécurisée (Hard Delete).
- **Audit Logs** : Traçabilité immuable de toutes les actions critiques.

### 💎 Expérience Utilisateur (UI/UX)
- **Design Système Propriétaire** : Architecture CSS modulaire unique (Variables CSS, Grid Layout).
- **Tableaux de Bord Dynamiques** : Vues adaptées par rôle (Invité, Ingénieur, Responsable, Admin).

## 🛠 Spécifications Techniques

Cette application respecte des standards de qualité industrielle :

- **Backend** : Laravel 10 (MVC, Eloquent, Queues, Mailables).
- **Frontend** :
    - **JS** : Vanilla ES6+ Modulaire (Architecture basée sur les composants).
    - **CSS** : Design System Custom ("Aurora Design").
- **IA** : Controller dédié avec logique de matching NLP (Natural Language Processing) locale.

## 📦 Guide d'Installation

### Prérequis
- PHP 8.1+
- Composer
- Node.js & NPM
- Serveur MySQL

### Démarrage Rapide

1. **Cloner le projet**
   ```bash
   git clone https://github.com/Homam-Dany/Application_Web_DataCenter.git
   cd Application_Web_DataCenter
   ```

2. **Installation**
   ```bash
   composer install
   npm install
   ```

3. **Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Configurez votre base de données et paramètres SMTP (Gmail/Outlook) dans le fichier .env*

4. **Lancement**
   ```bash
   php artisan migrate --seed
   npm run build
   php artisan serve
   ```

---

## 👥 Équipe de Développement

**Projet Académique d'Excellence — Université Abdelmalek Essaâdi (IDAI)**

- **Homam Dany** : Lead Developer & Architecte
- **Houssam ElHAJIOUI** : Frontend Specialist
- **Fatima Zahra Farssi** : UI/UX Designer
- **Salim El Bourmaki** : Backend Engineer

---
<div align="center">
    <i>Un projet conçu avec passion pour l'excellence technique.</i>
</div>