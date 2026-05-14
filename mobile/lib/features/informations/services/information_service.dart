import 'dart:convert';
import 'package:http/http.dart' as http;
import '../../auth/services/auth_service.dart';

class InformationService {
  // Remplacer par votre IP locale ou l'URL de votre API
  static const String baseUrl = 'http://10.0.2.2:8000/api';

  Future<List<dynamic>> getInformations() async {
    final token = await AuthService().getToken();

    final response = await http.get(
      Uri.parse('$baseUrl/informations'),
      headers: {
        'Authorization': 'Bearer $token',
        'Accept': 'application/json',
      },
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return data['data'];
    } else {
      throw Exception('Erreur lors du chargement des informations');
    }
  }
}
