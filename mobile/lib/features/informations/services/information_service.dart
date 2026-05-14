import 'package:dio/dio.dart';
import '../../../core/network/dio_client.dart';

class InformationService {
  final Dio _dio = DioClient().dio;

  Future<List<dynamic>> getInformations() async {
    try {
      final response = await _dio.get('/informations');

      if (response.statusCode == 200) {
        // Selon la structure de votre API Laravel
        return response.data['data'] ?? response.data;
      } else {
        throw Exception('Erreur lors du chargement des informations');
      }
    } on DioException catch (e) {
      print('Erreur InformationService: ${e.message}');
      throw Exception('Erreur réseau lors du chargement des informations');
    }
  }
}
