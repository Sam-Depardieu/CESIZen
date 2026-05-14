import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/diagnostic_provider.dart';

class DiagnosticScreen extends StatelessWidget {
  const DiagnosticScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (_) => DiagnosticProvider(),
      child: const _DiagnosticView(),
    );
  }
}

class _DiagnosticView extends StatelessWidget {
  const _DiagnosticView();

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<DiagnosticProvider>(context);

    if (provider.isLoading) {
      return const Scaffold(
        body: Center(child: CircularProgressIndicator()),
      );
    }

    if (provider.questions.isEmpty) {
      return Scaffold(
        appBar: AppBar(title: const Text('Diagnostic')),
        body: const Center(child: Text('Aucun événement trouvé.')),
      );
    }

    final question = provider.questions[provider.currentQuestionIndex];

    if (provider.isFinished) {
      return Scaffold(
        appBar: AppBar(title: const Text('Résultat du Diagnostic')),
        body: Center(
          child: Padding(
            padding: const EdgeInsets.all(24.0),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                const Text('Votre niveau de stress est :', style: TextStyle(fontSize: 18)),
                const SizedBox(height: 10),
                Text(
                  provider.stressLevel,
                  style: TextStyle(
                    fontSize: 32,
                    fontWeight: FontWeight.bold,
                    color: _getColorForLevel(provider.stressLevel),
                  ),
                ),
                const SizedBox(height: 20),
                Text(
                  provider.advice,
                  textAlign: TextAlign.center,
                  style: const TextStyle(fontSize: 16, fontStyle: FontStyle.italic),
                ),
                const SizedBox(height: 40),
                ElevatedButton(
                  onPressed: () => Navigator.pop(context),
                  child: const Text('Retour à l\'accueil'),
                ),
              ],
            ),
          ),
        ),
      );
    }

    return Scaffold(
      appBar: AppBar(
        title: Text('Question ${provider.currentQuestionIndex + 1}/${provider.questions.length}'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(20.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            LinearProgressIndicator(
              value: (provider.currentQuestionIndex + 1) / provider.questions.length,
              backgroundColor: Colors.grey.shade200,
            ),
            const SizedBox(height: 40),
            Text(
              question.text,
              style: const TextStyle(fontSize: 20, fontWeight: FontWeight.w500),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 40),
            ...question.options.map((option) => Padding(
                  padding: const EdgeInsets.only(bottom: 12.0),
                  child: ElevatedButton(
                    onPressed: () => provider.answerQuestion(option.points),
                    style: ElevatedButton.styleFrom(
                      padding: const EdgeInsets.symmetric(vertical: 15),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                    ),
                    child: Text(option.text),
                  ),
                )),
          ],
        ),
      ),
    );
  }

  Color _getColorForLevel(String level) {
    switch (level) {
      case "Faible": return Colors.green;
      case "Modéré": return Colors.orange;
      case "Élevé": return Colors.red;
      case "Très élevé": return Colors.red.shade900;
      default: return Colors.black;
    }
  }
}
