import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:permission_handler/permission_handler.dart';
import 'package:provider/provider.dart';
import '../tracker/providers/emotion_provider.dart';

class WearSyncService {
  static const _platform = MethodChannel('com.cesizen/wearable');

  static void initialize(BuildContext context) {
    _platform.setMethodCallHandler((call) async {
      switch (call.method) {
        case "requestStatusUpdate":
          // On peut forcer un rafraîchissement des données ici si besoin
          break;
        case "saveMoodFromWear":
          final String mood = call.arguments['mood'];
          print("DEBUG FLUTTER: Message saveMoodFromWear reçu pour '$mood'");
          if (mood.isNotEmpty) {
            // UTILISATION DU PROVIDER : C'est la clé pour que le token soit inclus
            try {
              final emotionProvider = Provider.of<EmotionProvider>(context, listen: false);
              await emotionProvider.addEmotion(mood, "Enregistré depuis la montre");
              print("DEBUG: Humeur '$mood' envoyée à l'API via Provider");
            } catch (e) {
              print("DEBUG ERROR: Impossible d'accéder au Provider: $e");
            }
          }
          break;
      }
    });
  }

  static Future<void> syncMoodToWatch(String mood) async {
    await _platform.invokeMethod('syncMood', {'mood': mood});
  }

  static Future<void> checkAndPromptWearSync(BuildContext context, String? userName) async {
    if (await Permission.bluetoothConnect.request().isGranted) {
      final bool? isWatchNearby = await _platform.invokeMethod('isWatchNearby');
      
      if (isWatchNearby == true && context.mounted) {
        showDialog(
          context: context,
          builder: (BuildContext context) {
            return AlertDialog(
              title: const Text("Montre connectée détectée"),
              content: const Text(
                "Souhaitez-vous lier CESIZen à votre montre pour suivre votre état émotionnel au poignet ?"
              ),
              actions: [
                TextButton(
                  onPressed: () => Navigator.pop(context),
                  child: const Text("PLUS TARD", style: TextStyle(color: Colors.grey)),
                ),
                ElevatedButton(
                  onPressed: () async {
                    Navigator.pop(context);
                    await _platform.invokeMethod('sendAuthStatus', {
                      'status': userName != null ? 'authenticated' : 'unauthenticated',
                      'userName': userName ?? '',
                    });
                  },
                  style: ElevatedButton.styleFrom(backgroundColor: const Color(0xFF000091)),
                  child: const Text("ACTIVER LA LIAISON", style: TextStyle(color: Colors.white)),
                ),
              ],
            );
          },
        );
      }
    }
  }
}
