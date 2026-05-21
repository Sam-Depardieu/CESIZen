import 'package:flutter/material.dart';
import '../models/user.dart';
import '../services/admin_service.dart';

class AdminProvider with ChangeNotifier {
  final AdminService _service = AdminService();
  List<User> _users = [];
  bool _isLoading = false;

  List<User> get users => _users;
  bool get isLoading => _isLoading;

  Future<void> loadUsers() async {
    _isLoading = true;
    notifyListeners();
    _users = await _service.fetchUsers();
    _isLoading = false;
    notifyListeners();
  }

  Future<bool> toggleUserStatus(User user) async {
    final success = await _service.updateUser(user.id, {'is_active': !user.isActive});
    if (success) {
      await loadUsers();
    }
    return success;
  }

  Future<bool> changeUserRole(User user, String newRole) async {
    // Dans une vraie app, on enverrait l'id du rôle
    final roleId = newRole == 'Admin' ? 1 : 2;
    final success = await _service.updateUser(user.id, {'id_role': roleId});
    if (success) {
      await loadUsers();
    }
    return success;
  }

  Future<bool> deleteUser(int userId) async {
    final success = await _service.deleteUser(userId);
    if (success) {
      _users.removeWhere((u) => u.id == userId);
      notifyListeners();
    }
    return success;
  }
}
