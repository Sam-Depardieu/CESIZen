# CESIZen - Application Mobile (Flutter)

CESIZen est une application de gestion de la santé mentale développée avec Flutter. Elle permet aux utilisateurs de suivre leur état émotionnel, de réaliser des diagnostics de stress et de pratiquer des exercices de relaxation.

## 🚀 Fonctionnalités

- **Authentification Sécurisée** : Connexion et inscription via une API Laravel (JWT).
- **Espace Santé Personnel** : Modification du profil (Nom, Email, Mot de passe).
- **Diagnostic de Stress** : Basé sur l'échelle de **Holmes et Rahe** (données dynamiques via API).
- **Tracker d'Émotions** : Journal de bord complet (CRUD) pour suivre son humeur.
- **Cohérence Cardiaque** : Exercice de respiration guidé (5s inspiration / 5s expiration).
- **Catalogue Détente** : Activités de relaxation (Méditation, Musique) filtrables par catégorie.

---

## 🛠️ Installation et Lancement

### 1. Prérequis
- [Flutter SDK](https://docs.flutter.dev/get-started/install) (dernière version stable recommandée).
- [Android Studio](https://developer.android.com/studio) ou [VS Code](https://code.visualstudio.com/).
- Un émulateur Android ou un appareil physique avec le débogage USB activé.

### 2. Récupération du projet
```bash
git clone <url-du-depot>
cd CESIZen/mobile
```

### 3. Installation des dépendances
```bash
flutter pub get
```

---

## 🌐 Configuration de l'API

L'application communique avec un backend Laravel.

### URL de l'API
La configuration se trouve dans `lib/core/network/dio_client.dart`.
- **URL actuelle** : `https://apicesizen.sam-coffre.duckdns.org/api`

### Compatibilité et Sécurité (SSL Bypass)
Pour faciliter le développement sur des serveurs avec des certificats auto-signés ou en cours de configuration, le client `Dio` est configuré pour **ignorer les erreurs de certificat SSL**.

Dans `lib/core/network/dio_client.dart` :
```dart
// Le code inclut un SecurityContext qui accepte tous les certificats
(dio.httpClientAdapter as IOHttpClientAdapter).createHttpClient = () {
  final client = HttpClient();
  client.badCertificateCallback = (X509Certificate cert, String host, int port) => true;
  return client;
};
```

**Note importante :** Sur Android, assurez-vous que `android:usesCleartextTraffic="true"` est bien présent dans le `AndroidManifest.xml` si vous repassez en HTTP simple.

---

## 🏃 Lancer l'application

Pour lancer l'application en mode debug :

```bash
flutter run
```

Pour générer un APK de test :
```bash
flutter build apk --debug
```

## 📂 Structure du Projet

- `lib/core/` : Configuration réseau (Dio) et thèmes.
- `lib/features/auth/` : Gestion de l'authentification et du profil.
- `lib/features/diagnostics/` : Logique du questionnaire Holmes & Rahe.
- `lib/features/tracker/` : Journal des émotions (CRUD).
- `lib/features/exercises/` : Exercice de cohérence cardiaque.
- `lib/features/relaxation/` : Catalogue d'activités de détente.

---
© 2024 CESIZen - Projet CDA
