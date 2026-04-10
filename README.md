# 📚 BiblioGest - Gestion de Bibliothèque Universitaire 4.0

[![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://github.com/latifa112/bibliotheque-universitaire)
[![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-4479A1.svg)](https://mysql.com)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

## 🎯 À propos du projet

BiblioGest est une application web moderne de gestion de bibliothèque universitaire conçue pour répondre aux besoins des étudiants, enseignants et bibliothécaires. Elle permet de gérer efficacement les catalogues de livres, les emprunts, les retours, les réservations et les statistiques.

### 👥 Équipe de développement

| Rôle | Membres |
|------|---------|
| **Chef de projet** | Nawel |
| **Développeur Backend** | nawel, latifa |
| **Développeur Frontend** | ryham, nesrine |
| **Intégrateur** | maria |
| **Testeur** | feriel|

## ✨ Fonctionnalités

### 🔐 Authentification
- Inscription avec choix du rôle (Étudiant/Enseignant)
- Connexion sécurisée
- Mot de passe oublié avec réinitialisation
- Session utilisateur persistante

### 📖 Catalogue
- Recherche de livres par titre, auteur ou ISBN
- Affichage en temps réel de la disponibilité
- Filtres par catégorie
- Interface moderne avec aperçu des couvertures

### 🤝 Emprunts
- Emprunter un livre disponible
- Retourner un livre
- Historique des emprunts
- Notifications de retard

### 📅 Réservations
- Réserver un livre indisponible
- Annuler une réservation
- Expiration automatique après 7 jours

### 👨‍💼 Administration
- Gestion complète du catalogue (CRUD)
- Gestion des utilisateurs (activer/désactiver/supprimer)
- Consultation des statistiques
- Sauvegarde de la base de données

### 📊 Statistiques
- Graphiques des emprunts mensuels (Chart.js)
- Top 5 des livres les plus empruntés
- Top 5 des lecteurs les plus actifs
- Répartition par catégorie

### 🌐 Internationalisation
- 3 langues supportées : 🇫🇷 Français, 🇬🇧 English, 🇸🇦 العربية
- Persistance de la langue après déconnexion

### 🎨 Interface
- Thème clair/sombre
- Design responsive (mobile/tablette/desktop)
- Curseur personnalisé
- Animations fluides

### 🤖 Chatbot
- Assistant virtuel Athena
- Recommandations de livres
- Réponses aux questions fréquentes

## 🛠️ Technologies utilisées

| Technologie | Version | Utilisation |
|-------------|---------|-------------|
| PHP | 8.1+ | Backend / API |
| MySQL | 5.7+ | Base de données |
| HTML5/CSS3 | - | Structure et style |
| JavaScript | ES6 | Interactions dynamiques |
| Chart.js | 3.9+ | Graphiques statistiques |
| Font Awesome | 6.5+ | Icônes |

## 📋 Prérequis

- PHP 8.1 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache/Nginx)
- Composer (optionnel)

## 🚀 Installation

### 1. Cloner le projet
```bash
git clone https://github.com/latifa112/bibliotheque-universitaire.git
cd bibliotheque-universitaire
git checkout code
