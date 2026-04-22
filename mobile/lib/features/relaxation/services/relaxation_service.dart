import 'package:dio/dio.dart';
import '../../../core/network/dio_client.dart';
import '../models/activity.dart';

class RelaxationService {
  final Dio _dio = DioClient().dio;

  Future<List<Activity>> fetchActivities() async {
    try {
      final response = await _dio.get('/activities');
      if (response.statusCode == 200) {
        List<dynamic> data = response.data;
        return data.map((item) => Activity.fromJson(item)).toList();
      }
      return [];
    } catch (e) {
      print("Erreur fetchActivities: $e");
      return [];
    }
  }
}
