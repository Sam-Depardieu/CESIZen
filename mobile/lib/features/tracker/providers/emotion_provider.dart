import 'package:flutter/material.dart';
import '../models/emotion_entry.dart';
import '../services/emotion_service.dart';

class EmotionProvider with ChangeNotifier {
  final EmotionService _service = EmotionService();
  List<EmotionEntry> _entries = [];
  bool _isLoading = false;

  List<EmotionEntry> get entries => _entries;
  bool get isLoading => _isLoading;

  Future<void> loadEmotions() async {
    _isLoading = true;
    notifyListeners();
    _entries = await _service.fetchEmotions();
    // Trier par date décroissante (plus récent en haut)
    _entries.sort((a, b) => b.createdAt.compareTo(a.createdAt));
    _isLoading = false;
    notifyListeners();
  }

  Future<bool> addEmotion(String emotion, String note) async {
    final success = await _service.addEmotion(emotion, note);
    if (success) {
      await loadEmotions();
    }
    return success;
  }

  Future<bool> deleteEmotion(int id) async {
    final success = await _service.deleteEmotion(id);
    if (success) {
      _entries.removeWhere((entry) => entry.id == id);
      notifyListeners();
    }
    return success;
  }
}
