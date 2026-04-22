import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/relaxation_provider.dart';

class RelaxationScreen extends StatefulWidget {
  const RelaxationScreen({super.key});

  @override
  State<RelaxationScreen> createState() => _RelaxationScreenState();
}

class _RelaxationScreenState extends State<RelaxationScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() =>
        Provider.of<RelaxationProvider>(context, listen: false).loadActivities());
  }

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<RelaxationProvider>(context);
    final categories = ['Toutes', 'Méditation', 'Musique', 'Lecture', 'Sport'];

    return Scaffold(
      appBar: AppBar(
        title: const Text('Espace Détente'),
        elevation: 0,
      ),
      body: Column(
        children: [
          // Barre de catégories
          Container(
            height: 60,
            padding: const EdgeInsets.symmetric(vertical: 10),
            child: ListView.builder(
              scrollDirection: Axis.horizontal,
              itemCount: categories.length,
              padding: const EdgeInsets.symmetric(horizontal: 16),
              itemBuilder: (context, index) {
                final cat = categories[index];
                final isSelected = provider.selectedCategory == cat;
                return Padding(
                  padding: const EdgeInsets.only(right: 10),
                  child: FilterChip(
                    label: Text(cat),
                    selected: isSelected,
                    onSelected: (_) => provider.setCategory(cat),
                    selectedColor: const Color(0xFF000080).withOpacity(0.2),
                    checkmarkColor: const Color(0xFF000080),
                  ),
                );
              },
            ),
          ),
          // Liste des activités
          Expanded(
            child: provider.isLoading
                ? const Center(child: CircularProgressIndicator())
                : provider.activities.isEmpty
                    ? const Center(child: Text('Aucune activité trouvée.'))
                    : ListView.builder(
                        itemCount: provider.activities.length,
                        padding: const EdgeInsets.all(16),
                        itemBuilder: (context, index) {
                          final activity = provider.activities[index];
                          return Card(
                            margin: const EdgeInsets.only(bottom: 16),
                            clipBehavior: Clip.antiAlias,
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                if (activity.imageUrl != null)
                                  Image.network(
                                    activity.imageUrl!,
                                    height: 150,
                                    width: double.infinity,
                                    fit: BoxFit.cover,
                                    errorBuilder: (_, __, ___) => Container(
                                      height: 150,
                                      color: Colors.grey.shade200,
                                      child: const Icon(Icons.image_not_supported),
                                    ),
                                  ),
                                Padding(
                                  padding: const EdgeInsets.all(16),
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Row(
                                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                        children: [
                                          Text(
                                            activity.category,
                                            style: TextStyle(
                                              color: Colors.orange.shade700,
                                              fontWeight: FontWeight.bold,
                                              fontSize: 12,
                                            ),
                                          ),
                                          if (activity.videoUrl != null)
                                            const Icon(Icons.play_circle_fill, color: Color(0xFF000080)),
                                        ],
                                      ),
                                      const SizedBox(height: 8),
                                      Text(
                                        activity.title,
                                        style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                                      ),
                                      const SizedBox(height: 8),
                                      Text(
                                        activity.description,
                                        style: const TextStyle(color: Colors.grey),
                                        maxLines: 2,
                                        overflow: TextOverflow.ellipsis,
                                      ),
                                    ],
                                  ),
                                ),
                              ],
                            ),
                          );
                        },
                      ),
          ),
        ],
      ),
    );
  }
}
