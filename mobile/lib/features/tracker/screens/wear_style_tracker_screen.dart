import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/emotion_provider.dart';
import '../../auth/providers/auth_provider.dart';

class WearStyleTrackerScreen extends StatefulWidget {
  const WearStyleTrackerScreen({super.key});

  @override
  State<WearStyleTrackerScreen> createState() => _WearStyleTrackerScreenState();
}

class _WearStyleTrackerScreenState extends State<WearStyleTrackerScreen> {
  bool _forceShowSelection = false;

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);
    final emotionProvider = Provider.of<EmotionProvider>(context);
    final bool isLoggedIn = authProvider.isAuthenticated;
    final userName = isLoggedIn ? (authProvider.user?.name?.split(' ').first ?? 'Zen') : 'Visiteur';
    final cesiBlue = const Color(0xFF000080);

    // Vérifier si une humeur a déjà été enregistrée aujourd'hui
    final today = DateTime.now();
    final hasTodayMood = emotionProvider.entries.any((entry) =>
        entry.createdAt.year == today.year &&
        entry.createdAt.month == today.month &&
        entry.createdAt.day == today.day);

    final todayEntry = hasTodayMood
        ? emotionProvider.entries.firstWhere((entry) =>
            entry.createdAt.year == today.year &&
            entry.createdAt.month == today.month &&
            entry.createdAt.day == today.day)
        : null;

    return Scaffold(
      backgroundColor: Colors.black,
      body: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
            colors: [Colors.black, Color(0xFF000022)],
          ),
        ),
        child: SafeArea(
          child: Column(
            children: [
              const SizedBox(height: 8),
              Text(
                'CESIZen',
                style: TextStyle(
                  color: cesiBlue,
                  fontSize: 12,
                  fontWeight: FontWeight.bold,
                ),
              ),
              Expanded(
                child: !isLoggedIn
                    ? _buildLoginRequired(context)
                    : ListView(
                        padding: const EdgeInsets.symmetric(horizontal: 12),
                        children: [
                          const SizedBox(height: 4),
                          Text(
                            'Bonjour $userName',
                            textAlign: TextAlign.center,
                            style: const TextStyle(
                              color: Colors.white,
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          if (hasTodayMood && todayEntry != null && !_forceShowSelection)
                            _buildMoodSavedCard(context, todayEntry.emotion)
                          else ...[
                            const Padding(
                              padding: EdgeInsets.symmetric(vertical: 8.0),
                              child: Text(
                                'Comment allez-vous ?',
                                textAlign: TextAlign.center,
                                style: TextStyle(
                                  color: Colors.grey,
                                  fontSize: 12,
                                ),
                              ),
                            ),
                            _buildMoodButton(context, 'Très bien', '😊', const Color(0xFF4CAF50)),
                            _buildMoodButton(context, 'Bien', '🙂', const Color(0xFF8BC34A)),
                            _buildMoodButton(context, 'Neutre', '😐', const Color(0xFFFFC107)),
                            _buildMoodButton(context, 'Pas top', '🙁', const Color(0xFFFF9800)),
                            _buildMoodButton(context, 'Stressé', '😫', const Color(0xFFF44336)),
                          ],
                          const SizedBox(height: 20),
                        ],
                      ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildMoodSavedCard(BuildContext context, String mood) {
    return InkWell(
      onTap: () {
        setState(() {
          _forceShowSelection = true;
        });
      },
      child: Container(
        margin: const EdgeInsets.symmetric(vertical: 20),
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: const Color(0xFF000080).withOpacity(0.3),
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: const Color(0xFF000080).withOpacity(0.5)),
        ),
        child: Column(
          children: [
            const Text(
              'Humeur enregistrée',
              style: TextStyle(color: Colors.grey, fontSize: 10),
            ),
            const SizedBox(height: 8),
            Text(
              mood,
              style: const TextStyle(
                color: Colors.white,
                fontSize: 20,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 8),
            const Text(
              'Tapoter pour changer',
              style: TextStyle(color: Colors.grey, fontSize: 10),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildMoodButton(BuildContext context, String label, String emoji, Color color) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4.0),
      child: InkWell(
        onTap: () async {
          final provider = Provider.of<EmotionProvider>(context, listen: false);
          final success = await provider.addEmotion(label, "Depuis l'écran arrière");
          if (mounted) {
            setState(() {
              _forceShowSelection = false;
            });
            if (context.mounted) {
              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(
                  content: Text(success ? 'Humeur enregistrée !' : 'Erreur'),
                  duration: const Duration(seconds: 1),
                  backgroundColor: success ? Colors.green : Colors.red,
                ),
              );
            }
          }
        },
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 10, horizontal: 12),
          decoration: BoxDecoration(
            color: color.withOpacity(0.2),
            borderRadius: BorderRadius.circular(20),
          ),
          child: Row(
            children: [
              Text(emoji, style: const TextStyle(fontSize: 20)),
              const SizedBox(width: 12),
              Text(
                label,
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 14,
                  fontWeight: FontWeight.w500,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildLoginRequired(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          const Icon(Icons.lock_outline, color: Colors.white, size: 40),
          const SizedBox(height: 12),
          const Text(
            'Connexion requise',
            style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 8),
          const Text(
            'Ouvrez l\'app sur l\'écran principal',
            textAlign: TextAlign.center,
            style: TextStyle(color: Colors.grey, fontSize: 12),
          ),
        ],
      ),
    );
  }
}
