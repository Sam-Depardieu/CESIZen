import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/emotion_provider.dart';
import 'package:intl/intl.dart';

class TrackerScreen extends StatefulWidget {
  const TrackerScreen({super.key});

  @override
  State<TrackerScreen> createState() => _TrackerScreenState();
}

class _TrackerScreenState extends State<TrackerScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() =>
        Provider.of<EmotionProvider>(context, listen: false).loadEmotions());
  }

  void _showAddEmotionDialog() {
    String selectedEmotion = 'Bien';
    final noteController = TextEditingController();
    final emotions = ['Très bien', 'Bien', 'Neutre', 'Pas top', 'Stressé'];

    showDialog(
      context: context,
      builder: (context) => StatefulBuilder(
        builder: (context, setDialogState) => AlertDialog(
          title: const Text('Comment vous sentez-vous ?'),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              DropdownButton<String>(
                value: selectedEmotion,
                isExpanded: true,
                items: emotions.map((String value) {
                  return DropdownMenuItem<String>(
                    value: value,
                    child: Text(value),
                  );
                }).toList(),
                onChanged: (newValue) {
                  setDialogState(() => selectedEmotion = newValue!);
                },
              ),
              const SizedBox(height: 20),
              TextField(
                controller: noteController,
                decoration: const InputDecoration(
                  labelText: 'Note (optionnel)',
                  border: OutlineInputBorder(),
                ),
                maxLines: 3,
              ),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('ANNULER'),
            ),
            ElevatedButton(
              onPressed: () async {
                final success = await Provider.of<EmotionProvider>(context, listen: false)
                    .addEmotion(selectedEmotion, noteController.text);
                if (mounted) {
                  Navigator.pop(context);
                  if (!success) {
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(content: Text('Erreur lors de l\'ajout')),
                    );
                  }
                }
              },
              child: const Text('ENREGISTRER'),
            ),
          ],
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<EmotionProvider>(context);

    return Scaffold(
      appBar: AppBar(title: const Text('Journal des Émotions')),
      body: provider.isLoading
          ? const Center(child: CircularProgressIndicator())
          : provider.entries.isEmpty
              ? const Center(child: Text('Aucune entrée pour le moment.'))
              : ListView.builder(
                  itemCount: provider.entries.length,
                  padding: const EdgeInsets.all(16),
                  itemBuilder: (context, index) {
                    final entry = provider.entries[index];
                    return Card(
                      margin: const EdgeInsets.only(bottom: 12),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
                      child: ListTile(
                        leading: _getEmotionIcon(entry.emotion),
                        title: Text(
                          entry.emotion,
                          style: const TextStyle(fontWeight: FontWeight.bold),
                        ),
                        subtitle: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            if (entry.note != null && entry.note!.isNotEmpty)
                              Text(entry.note!),
                            Text(
                              DateFormat('dd/MM/yyyy HH:mm').format(entry.createdAt),
                              style: const TextStyle(fontSize: 12, color: Colors.grey),
                            ),
                          ],
                        ),
                        trailing: IconButton(
                          icon: const Icon(Icons.delete_outline, color: Colors.red),
                          onPressed: () => provider.deleteEmotion(entry.id!),
                        ),
                      ),
                    );
                  },
                ),
      floatingActionButton: FloatingActionButton(
        onPressed: _showAddEmotionDialog,
        backgroundColor: const Color(0xFF000080),
        child: const Icon(Icons.add, color: Colors.white),
      ),
    );
  }

  Widget _getEmotionIcon(String emotion) {
    IconData icon;
    Color color;
    switch (emotion) {
      case 'Très bien':
        icon = Icons.sentiment_very_satisfied;
        color = const Color(0xFF4CAF50);
        break;
      case 'Bien':
        icon = Icons.sentiment_satisfied;
        color = const Color(0xFF8BC34A);
        break;
      case 'Neutre':
        icon = Icons.sentiment_neutral;
        color = const Color(0xFFFFC107);
        break;
      case 'Pas top':
        icon = Icons.sentiment_dissatisfied;
        color = const Color(0xFFFF9800);
        break;
      case 'Stressé':
        icon = Icons.sentiment_very_dissatisfied;
        color = const Color(0xFFF44336);
        break;
      default:
        icon = Icons.sentiment_neutral;
        color = Colors.grey;
    }
    return CircleAvatar(
      backgroundColor: color.withOpacity(0.1),
      child: Icon(icon, color: color),
    );
  }
}
