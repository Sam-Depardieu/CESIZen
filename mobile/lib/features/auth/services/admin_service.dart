import 'package:dio/dio.dart';
import '../../../core/network/dio_client.dart';
import '../models/user.dart';
import './auth_service.dart';

class AdminService {
  final Dio _dio = DioClient().dio;
  final AuthService _authService = AuthService();

  Future<List<User>> fetchUsers() async {
    try {
      String? token = await _authService.getToken();
      final response = await _dio.get('/admin/users', options: Options(
        headers: {'Authorization': 'Bearer $token'}
      ));

      if (response.statusCode == 200) {
        final List<dynamic> usersData = response.data;
        return usersData.map((json) => User.fromJson(json)).toList();
      }
      return [];
    } catch (e) {
      return [];
    }
  }

  Future<bool> updateUser(int userId, Map<String, dynamic> data) async {
    try {
      String? token = await _authService.getToken();
      final response = await _dio.put('/admin/users/$userId', data: data, options: Options(
        headers: {'Authorization': 'Bearer $token'}
      ));

      return response.statusCode == 200;
    } catch (e) {
      return false;
    }
  }

  Future<bool> deleteUser(int userId) async {
    try {
      String? token = await _authService.getToken();
      final response = await _dio.delete('/admin/users/$userId', options: Options(
        headers: {'Authorization': 'Bearer $token'}
      ));

      return response.statusCode == 200;
    } catch (e) {
      return false;
    }
  }
}
