import 'package:dio/dio.dart';
import '../../../core/network/dio_client.dart';
import '../models/question.dart';

class DiagnosticService {
  final Dio _dio = DioClient().dio;

  Future<List<Question>> fetchStressEvents() async {
    try {
      final response = await _dio.get('/stress-events');
      
      if (response.statusCode == 200) {
        List<dynamic> data = response.data;
        return data.map((item) {
          return Question(
            text: "Avez-vous vécu : ${item['event_name']} ?",
            options: [
              Option(text: "Oui", points: int.parse(item['points'].toString())),
              Option(text: "Non", points: 0),
            ],
          );
        }).toList();
      }
      return [];
    } catch (e) {
      print("Erreur lors de la récupération des événements : $e");
      return [];
    }
  }
}
