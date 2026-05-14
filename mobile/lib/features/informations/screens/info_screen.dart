import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/information_provider.dart';

class InfoScreen extends StatefulWidget {
  const InfoScreen({super.key});

  @override
  State<InfoScreen> createState() => _InfoScreenState();
}

class _InfoScreenState extends State<InfoScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(
      () => context.read<InformationProvider>().fetchInformations(),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: const Text('Informations Santé', style: TextStyle(fontWeight: FontWeight.bold)),
        backgroundColor: Colors.white,
        elevation: 0,
      ),
      body: Consumer<InformationProvider>(
        builder: (context, provider, child) {
          if (provider.isLoading) {
            return const Center(child: CircularProgressIndicator(color: Color(0xFF000080)));
          }

          if (provider.error != null) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Icon(Icons.error_outline, size: 60, color: Colors.red),
                  const SizedBox(height: 16),
                  Text("Erreur : ${provider.error}"),
                  ElevatedButton(
                    onPressed: () => provider.fetchInformations(),
                    child: const Text("Réessayer"),
                  )
                ],
              ),
            );
          }

          if (provider.informations.isEmpty) {
            return const Center(child: Text("Aucune information disponible pour le moment."));
          }

          return ListView.builder(
            padding: const EdgeInsets.all(20),
            itemCount: provider.informations.length,
            itemBuilder: (context, index) {
              final info = provider.informations[index];
              return _buildArticleCard(context, info);
            },
          );
        },
      ),
    );
  }

  Widget _buildArticleCard(BuildContext context, dynamic info) {
    // On définit une couleur selon la catégorie ou une couleur par défaut
    final Color categoryColor = _getCategoryColor(info['category'] ?? 'Général');

    return Container(
      margin: const EdgeInsets.only(bottom: 20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: Colors.grey.shade100),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.03),
            blurRadius: 10,
            offset: const Offset(0, 5),
          ),
        ],
      ),
      child: InkWell(
        onTap: () => _showArticleDetail(context, info),
        borderRadius: BorderRadius.circular(20),
        child: Padding(
          padding: const EdgeInsets.all(20),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                    decoration: BoxDecoration(
                      color: categoryColor.withOpacity(0.1),
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: Text(
                      info['category'] ?? 'Général',
                      style: TextStyle(color: categoryColor, fontWeight: FontWeight.bold, fontSize: 12),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 15),
              Text(
                info['title'] ?? 'Sans titre',
                style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 8),
              Text(
                _stripHtml(info['content'] ?? '').split('\n').first,
                maxLines: 2,
                overflow: TextOverflow.ellipsis,
                style: TextStyle(color: Colors.grey.shade600, fontSize: 14),
              ),
              const SizedBox(height: 15),
              Row(
                mainAxisAlignment: MainAxisAlignment.end,
                children: [
                  Text(
                    "Lire la suite",
                    style: TextStyle(color: categoryColor, fontWeight: FontWeight.bold),
                  ),
                  Icon(Icons.arrow_forward_ios, size: 14, color: categoryColor),
                ],
              )
            ],
          ),
        ),
      ),
    );
  }

  Color _getCategoryColor(String category) {
    switch (category.toLowerCase()) {
      case 'prévention': return Colors.blue;
      case 'sommeil': return Colors.indigo;
      case 'stress': return Colors.orange;
      case 'exercices': return Colors.redAccent;
      default: return Colors.teal;
    }
  }

  String _stripHtml(String htmlString) {
    return htmlString.replaceAll(RegExp(r'<[^>]*>|&nbsp;'), ' ');
  }

  void _showArticleDetail(BuildContext context, dynamic info) {
    final Color categoryColor = _getCategoryColor(info['category'] ?? 'Général');

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => Container(
        height: MediaQuery.of(context).size.height * 0.85,
        decoration: const BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.vertical(top: Radius.circular(30)),
        ),
        padding: const EdgeInsets.fromLTRB(24, 20, 24, 40),
        child: SingleChildScrollView(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Center(
                child: Container(
                  width: 40,
                  height: 4,
                  decoration: BoxDecoration(color: Colors.grey.shade300, borderRadius: BorderRadius.circular(2)),
                ),
              ),
              const SizedBox(height: 30),
              Text(
                (info['category'] ?? 'Général').toUpperCase(),
                style: TextStyle(color: categoryColor, fontWeight: FontWeight.bold, letterSpacing: 1.2),
              ),
              const SizedBox(height: 10),
              Text(
                info['title'] ?? 'Sans titre',
                style: const TextStyle(fontSize: 28, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 20),
              const Divider(),
              const SizedBox(height: 20),
              Text(
                _stripHtml(info['content'] ?? ''),
                style: const TextStyle(fontSize: 16, height: 1.6, color: Colors.black87),
              ),
              const SizedBox(height: 30),
              Container(
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  color: Colors.grey.shade50,
                  borderRadius: BorderRadius.circular(15),
                ),
                child: const Row(
                  children: [
                    Icon(Icons.info_outline, color: Colors.grey),
                    SizedBox(width: 15),
                    Expanded(
                      child: Text(
                        "Ces informations sont fournies à titre indicatif et ne remplacent pas un avis médical.",
                        style: TextStyle(color: Colors.grey, fontSize: 13, fontStyle: FontStyle.italic),
                      ),
                    ),
                  ],
                ),
              )
            ],
          ),
        ),
      ),
    );
  }
}
