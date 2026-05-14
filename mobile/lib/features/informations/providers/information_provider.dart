import 'package:flutter/material.dart';
import '../services/information_service.dart';

class InformationProvider with ChangeNotifier {
  final InformationService _service = InformationService();
  List<dynamic> _informations = [];
  bool _isLoading = false;
  String? _error;

  List<dynamic> get informations => _informations;
  bool get isLoading => _isLoading;
  String? get error => _error;

  Future<void> fetchInformations() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      _informations = await _service.getInformations();
    } catch (e) {
      _error = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
