import 'package:dio/dio.dart';
import '../../../core/network/dio_client.dart';
import '../../auth/services/auth_service.dart';
import '../models/emotion_entry.dart';

class EmotionService {
  final Dio _dio = DioClient().dio;
  final AuthService _authService = AuthService();

  Future<List<EmotionEntry>> fetchEmotions() async {
    try {
      String? token = await _authService.getToken();
      final response = await _dio.get(
        '/emotions',
        options: Options(headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        }),
      );

      if (response.statusCode == 200) {
        List<dynamic> data = response.data;
        return data.map((item) => EmotionEntry.fromJson(item)).toList();
      }
      return [];
    } catch (e) {
      print("Erreur fetchEmotions: $e");
      return [];
    }
  }

  Future<bool> addEmotion(String emotion, String note) async {
    try {
      String? token = await _authService.getToken();
      final response = await _dio.post(
        '/emotions',
        data: {
          'emotion_name': emotion,
          'note': note,
        },
        options: Options(headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        }),
      );
      return response.statusCode == 201 || response.statusCode == 200;
    } catch (e) {
      print("Erreur addEmotion: $e");
      return false;
    }
  }

  Future<bool> deleteEmotion(int id) async {
    try {
      String? token = await _authService.getToken();
      final response = await _dio.delete(
        '/emotions/$id',
        options: Options(headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        }),
      );
      return response.statusCode == 200 || response.statusCode == 204;
    } catch (e) {
      print("Erreur deleteEmotion: $e");
      return false;
    }
  }
}
