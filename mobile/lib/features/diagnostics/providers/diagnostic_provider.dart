import 'package:flutter/material.dart';
import '../models/question.dart';
import '../services/diagnostic_service.dart';

class DiagnosticProvider with ChangeNotifier {
  final DiagnosticService _service = DiagnosticService();
  List<Question> _questions = [];
  int _currentQuestionIndex = 0;
  int _totalScore = 0;
  bool _isFinished = false;
  bool _isLoading = true;

  List<Question> get questions => _questions;
  int get currentQuestionIndex => _currentQuestionIndex;
  int get totalScore => _totalScore;
  bool get isFinished => _isFinished;
  bool get isLoading => _isLoading;

  DiagnosticProvider() {
    loadQuestions();
  }

  Future<void> loadQuestions() async {
    _isLoading = true;
    notifyListeners();
    
    _questions = await _service.fetchStressEvents();
    
    _isLoading = false;
    notifyListeners();
  }

  void answerQuestion(int points) {
    _totalScore += points;
    if (_currentQuestionIndex < _questions.length - 1) {
      _currentQuestionIndex++;
    } else {
      _isFinished = true;
    }
    notifyListeners();
  }

  void reset() {
    _currentQuestionIndex = 0;
    _totalScore = 0;
    _isFinished = false;
    notifyListeners();
  }

  String get stressLevel {
    if (_totalScore < 150) return "Risque Faible";
    if (_totalScore < 300) return "Risque Modéré (50%)";
    return "Risque Élevé (80%)";
  }

  String get advice {
    if (_totalScore < 150) {
      return "Votre niveau de changement de vie est gérable. Continuez à maintenir votre équilibre actuel.";
    }
    if (_totalScore < 300) {
      return "Vous avez vécu beaucoup de changements. Il y a un risque modéré que cela affecte votre santé. Prenez du temps pour vous reposer.";
    }
    return "Attention : Votre score est très élevé. L'accumulation de stress lié à ces changements majeurs pourrait sérieusement impacter votre santé. Il est recommandé de consulter un professionnel.";
  }
}
