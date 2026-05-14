# 🌿 CESIZen - Écosystème de Bien-être Unifié

CESIZen est une solution complète de gestion du stress et du bien-être, composée d'une plateforme web, d'une application mobile et d'une application pour montre connectée (Wear OS).

## 🚀 Structure du Projet

- **/WEB** : API et interface d'administration/diagnostic (Laravel 10, DSFR, Alpine.js).
- **/mobile** : Application compagnon pour le suivi et les exercices (Flutter).
- **/Wear** : Application de montre pour le tracking rapide de l'humeur (Android Wear OS, Kotlin/Compose).

---

## 🛠 Installation & Configuration

### 1. Backend & Web (Laravel)
Le serveur central gère l'authentification (Sanctum) et la persistance des données.

```bash
cd WEB
composer install
npm install
cp .env.example .env # Configurez votre base de données et l'URL du serveur
php artisan key:generate
php artisan migrate --seed
php artisan serve
```
*Note : Assurez-vous que l'IP de votre serveur est accessible par votre téléphone/émulateur pour la synchronisation.*

### 2. Application Mobile (Flutter)
L'application mobile se connecte à l'API Laravel.

```bash
cd mobile
flutter pub get
# Modifiez l'URL de l'API dans lib/features/auth/services/auth_service.dart si nécessaire
flutter run
```

### 3. Application Montre (Wear OS)
La montre communique avec le téléphone via le **Google Wearable Data Layer**.

1. Ouvrez le dossier `/Wear` dans **Android Studio**.
2. Compilez et installez l'APK sur une montre (ou émulateur) Wear OS.
3. Assurez-vous que le téléphone appairé possède également l'application mobile CESIZen installée.

---

## 📱 Utilisation & Fonctionnalités

### 🖥️ Plateforme Web
- **Tableau de bord** : Visualisation des statistiques de stress.
- **Détente interactive** : Module de respiration guidée (méthode DSFR) avec 3 modes (Sommeil, Cohérence, Anxiété).
- **Diagnostics** : Historique détaillé des relevés émotionnels.

### 🤳 Application Mobile
- **Authentification** : Connexion sécurisée avec persistance de session.
- **Tracker** : Enregistrement quotidien de l'état émotionnel.
- **Exercices** : Respiration guidée avec animation synchronisée du logo CESIZen.
- **Sync Montre** : Envoi automatique de la date et réception des humeurs capturées sur la montre.

### ⌚ Application Wear OS
- **Quick Mood** : Sélectionnez votre humeur en un clic parmi 5 niveaux.
- **Smart Reset** : L'humeur est automatiquement réinitialisée à minuit chaque jour grâce à la synchronisation bidirectionnelle avec le mobile.
- **Indicateur visuel** : Confirmation visuelle immédiate de l'enregistrement.

---

## 🔗 Synchronisation Bidirectionnelle
- **Mobile -> Montre** : Envoie la `date du jour` pour synchroniser le cycle quotidien.
- **Montre -> Mobile** : Envoie l'émotion sélectionnée via le `Data Layer` (`/daily_mood`).
- **Mobile -> Web** : Synchronise les données finales vers la base de données Laravel pour analyse.

---

## 🎨 Identité Visuelle
L'ensemble de l'écosystème utilise la charte graphique **CesiZen** :
- **Couleur Primaire** : Bleu Marine (`#000080`)
- **Couleur Accent** : Orange (`#FFCC80`)
- **Typographie** : Marianne (standard DSFR)
- **Logo** : Point central de l'animation de respiration.

---

## 📄 Licence
Projet réalisé dans le cadre de la formation CDA CESI.
