# <img src="https://raw.githubusercontent.com/FortAwesome/Font-Awesome/6.x/svgs/solid/server.svg" width="30" height="30" /> DC-Manager : Infrastructure & Resource Orchestrator

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

**La solution de référence pour la gestion, la réservation et le monitoring des ressources de Data Center.**
*Développée avec rigueur, sans frameworks CSS/JS, pour une performance pure.*

[Concept](#-vision--concept) • [Spécifications](#-spécifications-techniques) • [Installation](#-guide-dinstallation) • [Équipe](#-équipe)

---
</div>

## 🎯 Vision & Concept

**DC-Manager** répond au défi critique de l'allocation des ressources IT (Serveurs, Baies, VMs) en environnement partagé. 
Notre approche "Zero-Dependency" sur le frontend garantit une maîtrise totale du code, une légèreté inégalée et une interface sur-mesure (Dark Mode natif) pensée pour les ingénieurs.

## 🚀 Fonctionnalités Clés

### 💎 Expérience Utilisateur (UI/UX)
- **Interface Premium** : Design moderne, "Card-based", avec un mode sombre profond (Midnight Blue).
- **Responsive** : Adaptation fluide sur tous les écrans grâce à CSS Grid & Flexbox.
- **Tableaux de Bord Personnalisés** : Vues adaptées par rôle (Utilisateur, Responsable, Admin).

### 🛡️ Cœur Fonctionnel
- **Système de Réservation Intelligent** :
    - Algorithme anti-collision (interdiction des chevauchements).
    - Vérification de disponibilité en temps réel.
- **Gestion d'Incidents** : Workflow de signalement et de résolution intégré.
- **Mon Profil** : Espace personnel complet (Sécurité, RGPD, Historique).
- **Administration** : Audit logs, graphiques statistiques (Chart.js), gestion des utilisateurs.

## 🛠 Spécifications Techniques

Cette application respecte des contraintes strictes pour démontrer une expertise technique :

- **Backend** : Laravel 9/10 (Architecture MVC, Eloquent ORM, Policies, Middlewares).
- **Frontend** :
    - **CSS** : 100% Custom (Pas de Bootstrap ni Tailwind). Architecture modulaire.
    - **JS** : Vanilla ES6+ (Pas de jQuery). Modules séparés par fonctionnalité.
    - **Build** : Vite.js pour la compilation des assets.
- **Base de Données** : MySQL relationnelle.

## 📦 Guide d'Installation

### Prérequis
- PHP 8.1+
- Composer
- Node.js & NPM
- Serveur MySQL

### Démarrage Rapide

1. **Cloner et Installer les dépendances**
   ```bash
   git clone https://github.com/Homam-Dany/Application_Web_DataCenter.git
   cd Application_Web_DataCenter
   composer install
   npm install
   ```

2. **Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   # Configurez votre base de données dans le fichier .env
   ```

3. **Base de Données**
   ```bash
   php artisan migrate --seed
   ```

4. **Lancement**
   ```bash
   npm run build
   php artisan serve
   ```

---

## 👥 Équipe

- **Dany Homam** — *Lead Fullstack Developer & Architecte*

---

<div align="center">

**Projet Académique d'Excellence — Université Abdelmalek Essaâdi**  
*Département Ingénierie De Développement D'Applications Informatiques*

</div>