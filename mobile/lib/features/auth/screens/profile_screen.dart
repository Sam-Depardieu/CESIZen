import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import '../../tracker/providers/emotion_provider.dart';
import 'package:intl/intl.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  final _formKey = GlobalKey<FormState>();
  late TextEditingController _nameController;
  late TextEditingController _emailController;
  late TextEditingController _passwordController;
  late String _currentEmail;
  bool _isEditing = false;

  @override
  void initState() {
    super.initState();
    final user = Provider.of<AuthProvider>(context, listen: false).user;
    _currentEmail = user?.email ?? '';
    _nameController = TextEditingController(text: user?.name);
    _emailController = TextEditingController(text: _currentEmail);
    _passwordController = TextEditingController();
    
    _emailController.addListener(() {
      if (mounted) setState(() {}); 
    });

    // Charger les émotions pour le journal
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<EmotionProvider>(context, listen: false).loadEmotions();
    });
  }

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  void _submitUpdate() async {
    if (_formKey.currentState!.validate()) {
      final authProvider = Provider.of<AuthProvider>(context, listen: false);
      
      final String newName = _nameController.text.trim();
      final String newEmail = _emailController.text.trim();
      final String? newPassword = _passwordController.text.isNotEmpty ? _passwordController.text : null;

      final success = await authProvider.updateProfile(
        newName,
        newEmail,
        password: newPassword,
      );

      if (mounted) {
        if (success) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Profil mis à jour avec succès')),
          );
          setState(() {
            _isEditing = false;
            _currentEmail = newEmail;
            _passwordController.clear();
          });
        } else {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text(authProvider.errorMessage ?? 'Erreur lors de la mise à jour')),
          );
        }
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);
    final emotionProvider = Provider.of<EmotionProvider>(context);
    final user = authProvider.user;
    final bool emailChanged = _emailController.text.trim() != _currentEmail.trim();

    return Scaffold(
      appBar: AppBar(
        title: const Text('Mon Espace Santé'),
        backgroundColor: Colors.white,
        elevation: 0,
        actions: [
          IconButton(
            icon: Icon(_isEditing ? Icons.close : Icons.edit),
            onPressed: () {
              setState(() {
                _isEditing = !_isEditing;
                if (!_isEditing) {
                  _nameController.text = user?.name ?? '';
                  _emailController.text = user?.email ?? '';
                  _passwordController.clear();
                }
              });
            },
          )
        ],
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            // Section Profil
            Padding(
              padding: const EdgeInsets.all(24.0),
              child: Form(
                key: _formKey,
                child: Column(
                  children: [
                    const CircleAvatar(
                      radius: 50,
                      backgroundColor: Color(0xFF000080),
                      child: Icon(Icons.person, size: 50, color: Colors.white),
                    ),
                    const SizedBox(height: 20),
                    TextFormField(
                      controller: _nameController,
                      enabled: _isEditing,
                      decoration: const InputDecoration(
                        labelText: 'Nom',
                        prefixIcon: Icon(Icons.person_outline),
                        border: OutlineInputBorder(),
                      ),
                      validator: (value) => (value == null || value.trim().isEmpty) ? 'Veuillez entrer un nom' : null,
                    ),
                    const SizedBox(height: 15),
                    TextFormField(
                      controller: _emailController,
                      enabled: _isEditing,
                      decoration: const InputDecoration(
                        labelText: 'Email',
                        prefixIcon: Icon(Icons.email_outlined),
                        border: OutlineInputBorder(),
                      ),
                      validator: (value) {
                        final email = value?.trim() ?? '';
                        if (email.isEmpty) return 'Veuillez entrer un email';
                        if (!email.contains('@')) return 'Email invalide';
                        return null;
                      },
                    ),
                    if (_isEditing) ...[
                      const SizedBox(height: 15),
                      TextFormField(
                        controller: _passwordController,
                        obscureText: true,
                        decoration: InputDecoration(
                          labelText: emailChanged ? 'Mot de passe requis' : 'Nouveau mot de passe',
                          prefixIcon: const Icon(Icons.lock_outline),
                          border: const OutlineInputBorder(),
                        ),
                      ),
                      const SizedBox(height: 20),
                      ElevatedButton(
                        onPressed: authProvider.isLoading ? null : _submitUpdate,
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFF000080),
                          foregroundColor: Colors.white,
                          minimumSize: const Size.fromHeight(50),
                        ),
                        child: const Text('ENREGISTRER'),
                      ),
                    ],
                  ],
                ),
              ),
            ),

            // Section Journal Émotionnel (Le "Journal Santé")
            Container(
              width: double.infinity,
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
              color: Colors.grey.shade50,
              child: const Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Journal Émotionnel',
                    style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Color(0xFF000080)),
                  ),
                  Text('Historique de vos saisies (Mobile & Montre)', style: TextStyle(color: Colors.grey, fontSize: 12)),
                ],
              ),
            ),

            if (emotionProvider.isLoading)
              const Padding(
                padding: EdgeInsets.all(20.0),
                child: CircularProgressIndicator(),
              )
            else if (emotionProvider.entries.isEmpty)
              const Padding(
                padding: EdgeInsets.all(40.0),
                child: Column(
                  children: [
                    Icon(Icons.history, size: 48, color: Colors.grey),
                    SizedBox(height: 10),
                    Text('Aucun historique pour le moment.', style: TextStyle(color: Colors.grey)),
                  ],
                ),
              )
            else
              ListView.separated(
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                itemCount: emotionProvider.entries.length,
                separatorBuilder: (context, index) => const Divider(height: 1),
                itemBuilder: (context, index) {
                  final entry = emotionProvider.entries[index];
                  final dateStr = DateFormat('dd/MM/yyyy HH:mm').format(entry.createdAt);
                  return ListTile(
                    leading: _getMoodEmoji(entry.emotion),
                    title: Text(entry.emotion, style: const TextStyle(fontWeight: FontWeight.bold)),
                    subtitle: Text(entry.note ?? 'Saisie rapide'),
                    trailing: Text(dateStr, style: const TextStyle(fontSize: 11, color: Colors.grey)),
                  );
                },
              ),
              
            const SizedBox(height: 100), // Espace pour le scroll
          ],
        ),
      ),
    );
  }

  Widget _getMoodEmoji(String mood) {
    String emoji = "😐";
    Color color = Colors.grey;
    switch (mood) {
      case 'Très bien':
        emoji = "😊";
        color = const Color(0xFF4CAF50);
        break;
      case 'Bien':
        emoji = "🙂";
        color = const Color(0xFF8BC34A);
        break;
      case 'Neutre':
        emoji = "😐";
        color = const Color(0xFFFFC107);
        break;
      case 'Pas top':
        emoji = "🙁";
        color = const Color(0xFFFF9800);
        break;
      case 'Stressé':
        emoji = "😫";
        color = const Color(0xFFF44336);
        break;
    }
    return CircleAvatar(
      backgroundColor: color.withOpacity(0.1),
      child: Text(emoji, style: const TextStyle(fontSize: 20)),
    );
  }
}
