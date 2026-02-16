# <div align="center"><img src="https://raw.githubusercontent.com/FortAwesome/Font-Awesome/6.x/svgs/solid/server.svg" width="45" height="45" style="margin-right: 15px; vertical-align: middle;" /> DC-Manager : Enterprise-Grade DCIM</div>

<div align="center">

[![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Vite](https://img.shields.io/badge/Vite-Bundler-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)

**L'orchestrateur d'infrastructure nouvelle génération pour le Data Center FST Tanger.**
*Performance native • Architecture Sécurisée • Expérience Utilisateur Intuitive*

[📚 Rapport Technique](RAPPORT_TECHNIQUE.md) • [🚀 Guide d'Installation](#-installation-rapide) • [✨ Fonctionnalités](#-caractéristiques-majeures)

---
</div>

## 🌐 Présentation

**DC-Manager** est une plateforme **DCIM** (Data Center Infrastructure Management) sophistiquée conçue pour centraliser et automatiser la gestion des ressources critiques. Développée pour répondre aux exigences de la **FST Tanger**, elle transforme la complexité technique en une interface fluide et performante.

> [!TIP]
> **Pourquoi DC-Manager ?**
> Contrairement aux feuilles de calcul traditionnelles, DC-Manager offre une traçabilité totale, une visualisation en temps réel et une intelligence embarquée pour anticiper les besoins d'infrastructure.

---

## ✨ Caractéristiques Majeures

````carousel
### 📊 Dashboard Intelligent
Visualisez l'état de santé de votre parc en un clin d'œil. Graphiques dynamiques, KPIs en temps réel et alertes instantanées.
<!-- slide -->
### 🖥️ Rack Map Interactive
Une représentation visuelle précise de votre baie 42U. Repérez instantanément les serveurs actifs, les unités libres et les besoins de maintenance.
<!-- slide -->
### 📅 Orchestration des Réservations
Système de gestion des demandes avec flux d'approbation multiniveau. Prévention automatique des conflits et gestion des périodes.
<!-- slide -->
### 🤖 Assistant IA 24/7
Un support interactif intégré pour guider les utilisateurs, résoudre les problèmes courants et faciliter l'accès à l'information.
````

---

## 🛠 Stack Tecnologique

| Couche | Technologie | Excellence Technique |
| :--- | :--- | :--- |
| **Backend** | Laravel 10 (PHP 8.1+) | Architecture MVC, Eloquent ORM, Service Pattern. |
| **Frontend** | Vanilla JS / Vite | Performance maximale, sans dépendances lourdes, compilation optimisée. |
| **Design** | CSS3 Custom Properties | Design System "Aurora", animations 60 FPS, Dark Mode natif. |
| **Storage** | MySQL 8.0 | Intégrité transactionnelle et performances relationnelles. |
| **Reporting** | DOMPDF / Chart.js | Rapports PDF haute fidélité et data-visualisation interactive. |

---

## 🚀 Installation Rapide

### Prérequis
- **PHP** >= 8.1
- **Composer** & **NPM**
- **MySQL** >= 8.0

### Déploiement en 5 étapes

```bash
# 1. Acquisition
git clone https://github.com/Homam-Dany/Application_Web_DataCenter.git && cd Application_Web_DataCenter

# 2. Dépendances
composer install && npm install

# 3. Environnement
cp .env.example .env
php artisan key:generate

# 4. Persistence (Configurez .env avant)
php artisan migrate --seed

# 5. Lancement
npm run dev # ou npm run build pour la production
php artisan serve
```

---

## 🏛️ Architecture & Conception

Pour une analyse approfondie des choix techniques, de la modélisation de la base de données et des flux logiques, veuillez consulter le :

👉 **[RAPPORT_TECHNIQUE.md](file:///c:/xampp/htdocs/dashboard/Homam_Projet/RAPPORT_TECHNIQUE.md)**

---

## 👥 L'Équipe

Réalisé avec passion par l'équipe **IDAI - FST Tanger** :

- **Homam Dany** - *Lead Architecture & Fullstack Development*

---

<div align="center">
    <br>
    <img src="https://raw.githubusercontent.com/FortAwesome/Font-Awesome/6.x/svgs/solid/graduation-cap.svg" width="20" height="20" style="vertical-align: middle;" /> 
    <b>Licence IDAI • Faculté des Sciences et Techniques de Tanger</b>
    <br>
    <i>Université Abdelmalek Essaâdi • 2026</i>
</div>