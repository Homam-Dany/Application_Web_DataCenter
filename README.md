<div align="center">

# 🏢 DC-Manager : Data Center Management System
### Solution logicielle de haute précision pour la réservation et le monitoring de ressources IT.

[![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-Storage-4479A1?style=for-the-badge&logo=mysql)](https://mysql.com)
[![Vite](https://img.shields.io/badge/Vite-Asset_Pipeline-646CFF?style=for-the-badge&logo=vite)](https://vitejs.dev)

**Une approche minimaliste sur le front-end, une robustesse maximale sur le back-end.**

[Fonctionnalités](#-fonctionnalités-clés) • [Installation](#-installation-et-setup) • [Architecture](#-architecture-technique) • [Équipe](#-auteurs)

</div>

---

## 📖 Présentation du Projet

**DC-Manager** est une plateforme Web avancée conçue pour centraliser, sécuriser et optimiser l'allocation des ressources informatiques au sein d'un Data Center (IDAI). Elle offre une interface intuitive permettant de gérer tout le cycle de vie d'une ressource : de son intégration au catalogue jusqu'à sa réservation et son monitoring technique.

### Points Forts Technique :
- 🎨 **Esthétique "Total Black & Indigo"** : Design premium auto-adaptatif (Dark/Light Mode).
- 🚀 **Zero-Framework UI** : Entièrement développé en CSS et JS natif (Vanilla), sans Bootstrap, Tailwind ou jQuery, garantissant des performances optimales et une maîtrise totale de l'UI.
- ⚡ **Vite.js Pipeline** : Compilation moderne des actifs JS/CSS externes pour un chargement ultra-rapide.

---

## ✨ Fonctionnalités Clés

### 🔒 Gestion des Accès (Rôles & Permissions)
- **Multi-profils** : Utilisateurs, Responsables Techniques et Administrateurs.
- **Validation manuelle** : Système d'approbation des comptes par l'administrateur.
- **Magic Login** : Connexion via token sécurisé.

### 📅 Réservation Intelligente
- **Cycle complet** : Demande (avec justification) → Validation/Refus → Notification immédiate.
- **Historisation** : Traçabilité complète des réservations passées et actives.
- **Notifications** : Système d'alertes en temps réel pour le suivi des dossiers.

### 🛠️ Maintenance & Monitoring
- **Gestionnaire d'Incidents** : Signalement et résolution rapide des pannes par les utilisateurs et techniciens.
- **Mode Maintenance** : Possibilité pour les administrateurs de geler l'accès à une ressource en un clic.
- **Tableau de Bord Statistiques** : Taux d'occupation en temps réel et inventaire global.

---

## 🛠 Architecture Technique

Le projet repose sur une architecture **MVC (Model-View-Controller)** moderne via Laravel 10 :

- **Backend** : PHP 8.2+ avec Eloquent ORM pour une manipulation fluide des données.
- **Frontend** : Blade Engine + CSS3 Variables (Design System custom) + JS Moderne (Modules Vite).
- **Sécurité** : Protection CSRF, Middleware de rôles, Hashage de mots de passe, Validation stricte des entrées.
- **Base de Données** : Schéma relationnel optimisé incluant `users`, `resources`, `reservations`, `incidents`, `logs` et `notifications`.

---

## � Installation et Setup

### Configuration Pré-requise
- PHP 8.2 ou supérieur
- Composer
- Node.js & NPM
- MySQL / MariaDB (XAMPP recommandé pour le développement local)

### Étapes de Déploiement

1.  **Clonage du projet**
    ```bash
    git clone https://github.com/votre-repo/Homam_Projet.git
    cd Homam_Projet
    ```

2.  **Configuration des dépendances PHP**
    ```bash
    composer install
    ```

3.  **Configuration des dépendances Front-end**
    ```bash
    npm install
    ```

4.  **Environnement**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Éditez le fichier `.env` pour configurer vos accès MySQL (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).*

5.  **Migration de la base de données**
    ```bash
    php artisan migrate
    ```

6.  **Compilation des actifs & Lancement**
    ```bash
    npm run dev
    # Dans un autre terminal :
    php artisan serve
    ```

---

## 👥 Auteurs

Ce projet a été réalisé avec passion par l'équipe d'ingénierie de l'IDAI :

- **Dany Homam** — *Administrateur Système & Lead Developer*
- **EL Hajioui Houssam** — *Développeur Backend & DevOps*
- **El Bourmaki Salim** — *Architecte Base de Données & UI Designer*
- **Farssi Fatima Zahra** — *Développeur Fullstack & QA*

---

<div align="center">
     Projet Académique • Université Abdelmalek Essaâdi • IDAI
</div>