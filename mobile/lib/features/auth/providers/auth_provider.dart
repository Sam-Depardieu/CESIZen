import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import '../models/user.dart';
import '../services/auth_service.dart';

class AuthProvider with ChangeNotifier {
  final AuthService _authService = AuthService();
  static const _platform = MethodChannel('com.cesizen/wearable');
  
  User? _user;
  bool _isLoading = false;
  String? _errorMessage;

  AuthProvider() {
    // Écouter les demandes de mise à jour venant de la couche native (montre -> téléphone)
    _platform.setMethodCallHandler((call) async {
      if (call.method == "requestStatusUpdate") {
        _syncWithWear();
      }
    });
    
    // Tenter de restaurer la session au lancement
    _checkInitialAuth();
  }

  Future<void> _checkInitialAuth() async {
    _isLoading = true;
    notifyListeners();

    final result = await _authService.getProfile();
    if (result['status'] == 'success') {
      _user = result['user'];
      _syncWithWear();
    }
    
    _isLoading = false;
    notifyListeners();
  }

  User? get user => _user;
  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;
  bool get isAuthenticated => _user != null;

  void _syncWithWear() {
    try {
      _platform.invokeMethod('sendAuthStatus', {
        'status': isAuthenticated ? 'authenticated' : 'unauthenticated',
        'userName': _user?.name ?? '',
      });
    } catch (e) {
      // Échec silencieux si pas d'appareil Wear OS à proximité
      debugPrint("Wear OS sync failed: $e");
    }
  }

  Future<bool> login(String email, String password) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    final result = await _authService.login(email, password);

    _isLoading = false;
    if (result['status'] == 'success') {
      _user = result['user'];
      _syncWithWear();
      notifyListeners();
      return true;
    } else {
      _errorMessage = result['message'];
      notifyListeners();
      return false;
    }
  }

  Future<bool> register(String name, String email, String password, String passwordConfirmation) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    final result = await _authService.register(name, email, password, passwordConfirmation);

    _isLoading = false;
    if (result['status'] == 'success') {
      _user = result['user'];
      _syncWithWear();
      notifyListeners();
      return true;
    } else {
      if (result['errors'] is Map) {
        _errorMessage = (result['errors'] as Map).values.first[0];
      } else {
        _errorMessage = result['errors'].toString();
      }
      notifyListeners();
      return false;
    }
  }

  Future<void> logout() async {
    await _authService.logout();
    _user = null;
    _syncWithWear();
    notifyListeners();
  }

  Future<bool> updateProfile(
    String name,
    String email, {
    String? currentPassword,
    String? newPassword,
  }) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    final result = await _authService.updateProfile(
      name,
      email,
      currentPassword: currentPassword,
      newPassword: newPassword,
    );

    _isLoading = false;
    if (result['status'] == 'success') {
      _user = result['user'];
      _syncWithWear();
      notifyListeners();
      return true;
    } else {
      _errorMessage = result['message'];
      notifyListeners();
      return false;
    }
  }
}
