import 'package:flutter/material.dart';
import '../models/activity.dart';
import '../services/relaxation_service.dart';

class RelaxationProvider with ChangeNotifier {
  final RelaxationService _service = RelaxationService();
  List<Activity> _activities = [];
  bool _isLoading = false;
  String _selectedCategory = 'Toutes';

  List<Activity> get activities {
    if (_selectedCategory == 'Toutes') return _activities;
    return _activities.where((a) => a.category == _selectedCategory).toList();
  }

  bool get isLoading => _isLoading;
  String get selectedCategory => _selectedCategory;

  Future<void> loadActivities() async {
    _isLoading = true;
    notifyListeners();
    _activities = await _service.fetchActivities();
    _isLoading = false;
    notifyListeners();
  }

  void setCategory(String category) {
    _selectedCategory = category;
    notifyListeners();
  }
}
