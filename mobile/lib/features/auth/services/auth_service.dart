import 'package:dio/dio.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../../../core/network/dio_client.dart';
import '../models/user.dart';

class AuthService {
  final Dio _dio = DioClient().dio;
  final FlutterSecureStorage _storage = const FlutterSecureStorage();

  Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      final response = await _dio.post('/login', data: {
        'email': email,
        'password': password,
      });

      if (response.statusCode == 200) {
        String token = response.data['token'];
        await _storage.write(key: 'token', value: token);
        return {
          'status': 'success',
          'user': User.fromJson(response.data['user']),
        };
      }
      return {'status': 'error', 'message': 'Erreur inconnue'};
    } on DioException catch (e) {
      return {
        'status': 'error',
        'message': e.response?.data['message'] ?? 'Erreur de connexion'
      };
    }
  }

  Future<Map<String, dynamic>> register(String name, String email, String password, String passwordConfirmation) async {
    try {
      final response = await _dio.post('/register', data: {
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': passwordConfirmation,
      });

      if (response.statusCode == 201) {
        String token = response.data['token'];
        await _storage.write(key: 'token', value: token);
        return {
          'status': 'success',
          'user': User.fromJson(response.data['user']),
        };
      }
      return {'status': 'error', 'message': 'Erreur inconnue'};
    } on DioException catch (e) {
      return {
        'status': 'error',
        'errors': e.response?.data['errors'] ?? 'Erreur lors de l\'inscription'
      };
    }
  }

  Future<Map<String, dynamic>> updateProfile(String name, String email, {String? password}) async {
    try {
      String? token = await getToken();

      final Map<String, dynamic> data = {
        'name': name,
        'email': email,
      };

      if (password != null && password.isNotEmpty) {
        data['current_password'] = password;
        data['password'] = password;
        data['password_confirmation'] = password;
      }

      final response = await _dio.post(
        '/update-profile',
        data: data,
        options: Options(
          headers: {
            'Authorization': 'Bearer $token',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
          },
        ),
      );

      if (response.statusCode == 200) {
        return {
          'status': 'success',
          'user': User.fromJson(response.data['user']),
        };
      }
      return {'status': 'error', 'message': 'Erreur lors de la mise à jour'};
    } on DioException catch (e) {
      return {
        'status': 'error',
        'message': e.response?.data['message'] ?? 'Erreur lors de la modification du profil'
      };
    }
  }

  Future<Map<String, dynamic>> getProfile() async {
    try {
      String? token = await getToken();
      if (token == null) return {'status': 'error', 'message': 'No token'};

      final response = await _dio.get('/profile', options: Options(
        headers: {'Authorization': 'Bearer $token'}
      ));

      if (response.statusCode == 200) {
        return {
          'status': 'success',
          'user': User.fromJson(response.data),
        };
      }
      return {'status': 'error', 'message': 'Session expirée'};
    } catch (e) {
      return {'status': 'error', 'message': e.toString()};
    }
  }

  Future<void> logout() async {
    await _storage.delete(key: 'token');
  }

  Future<String?> getToken() async {
    return await _storage.read(key: 'token');
  }
}
