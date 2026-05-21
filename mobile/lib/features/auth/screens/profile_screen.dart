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
  // Formulaire profil
  final _profileFormKey = GlobalKey<FormState>();
  late TextEditingController _nameController;
  late TextEditingController _emailController;
  bool _isEditing = false;

  // Formulaire mot de passe
  final _passwordFormKey = GlobalKey<FormState>();
  final _currentPasswordController = TextEditingController();
  final _newPasswordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();
  bool _showCurrentPassword = false;
  bool _showNewPassword = false;
  bool _showConfirmPassword = false;
  bool _isChangingPassword = false;

  @override
  void initState() {
    super.initState();
    final user = Provider.of<AuthProvider>(context, listen: false).user;
    _nameController = TextEditingController(text: user?.name);
    _emailController = TextEditingController(text: user?.email ?? '');

    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<EmotionProvider>(context, listen: false).loadEmotions();
    });
  }

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _currentPasswordController.dispose();
    _newPasswordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }

  /// Soumet la mise à jour du nom / email uniquement.
  void _submitProfileUpdate() async {
    if (!_profileFormKey.currentState!.validate()) return;

    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    final success = await authProvider.updateProfile(
      _nameController.text.trim(),
      _emailController.text.trim(),
    );

    if (!mounted) return;
    if (success) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Profil mis à jour avec succès'),
          backgroundColor: Color(0xFF00BF63),
        ),
      );
      setState(() => _isEditing = false);
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(authProvider.errorMessage ?? 'Erreur lors de la mise à jour'),
          backgroundColor: Colors.red.shade700,
        ),
      );
    }
  }

  /// Soumet uniquement le changement de mot de passe.
  void _submitPasswordChange() async {
    if (!_passwordFormKey.currentState!.validate()) return;

    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    final success = await authProvider.updateProfile(
      authProvider.user?.name ?? '',
      authProvider.user?.email ?? '',
      currentPassword: _currentPasswordController.text,
      newPassword: _newPasswordController.text,
    );

    if (!mounted) return;
    if (success) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Mot de passe modifié avec succès'),
          backgroundColor: Color(0xFF00BF63),
        ),
      );
      setState(() {
        _isChangingPassword = false;
        _currentPasswordController.clear();
        _newPasswordController.clear();
        _confirmPasswordController.clear();
      });
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(authProvider.errorMessage ?? 'Mot de passe actuel incorrect'),
          backgroundColor: Colors.red.shade700,
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);
    final emotionProvider = Provider.of<EmotionProvider>(context);
    final user = authProvider.user;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Mon Espace Santé'),
        backgroundColor: Colors.white,
        elevation: 0,
        actions: [
          IconButton(
            icon: Icon(_isEditing ? Icons.close : Icons.edit),
            tooltip: _isEditing ? 'Annuler' : 'Modifier le profil',
            onPressed: () {
              setState(() {
                _isEditing = !_isEditing;
                if (!_isEditing) {
                  _nameController.text = user?.name ?? '';
                  _emailController.text = user?.email ?? '';
                }
              });
            },
          )
        ],
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            // ── Section Profil ──────────────────────────────
            Padding(
              padding: const EdgeInsets.all(24.0),
              child: Form(
                key: _profileFormKey,
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
                      validator: (value) =>
                          (value == null || value.trim().isEmpty) ? 'Veuillez entrer un nom' : null,
                    ),
                    const SizedBox(height: 15),
                    TextFormField(
                      controller: _emailController,
                      enabled: _isEditing,
                      keyboardType: TextInputType.emailAddress,
                      decoration: const InputDecoration(
                        labelText: 'Email',
                        prefixIcon: Icon(Icons.email_outlined),
                        border: OutlineInputBorder(),
                      ),
                      validator: (value) {
                        final email = value?.trim() ?? '';
                        if (email.isEmpty) return 'Veuillez entrer un email';
                        if (!RegExp(r'^[^@]+@[^@]+\.[^@]+$').hasMatch(email)) {
                          return 'Adresse email invalide';
                        }
                        return null;
                      },
                    ),
                    if (_isEditing) ...[
                      const SizedBox(height: 20),
                      ElevatedButton(
                        onPressed: authProvider.isLoading ? null : _submitProfileUpdate,
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFF000080),
                          foregroundColor: Colors.white,
                          minimumSize: const Size.fromHeight(50),
                        ),
                        child: authProvider.isLoading
                            ? const SizedBox(
                                height: 20,
                                width: 20,
                                child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2),
                              )
                            : const Text('ENREGISTRER LE PROFIL'),
                      ),
                    ],
                  ],
                ),
              ),
            ),

            const Divider(height: 1, thickness: 1),

            // ── Section Changement de mot de passe ──────────
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 24.0, vertical: 8.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  ListTile(
                    contentPadding: EdgeInsets.zero,
                    leading: const Icon(Icons.lock_outline, color: Color(0xFF000080)),
                    title: const Text(
                      'Modifier le mot de passe',
                      style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15),
                    ),
                    trailing: Icon(
                      _isChangingPassword ? Icons.keyboard_arrow_up : Icons.keyboard_arrow_down,
                      color: const Color(0xFF000080),
                    ),
                    onTap: () {
                      setState(() {
                        _isChangingPassword = !_isChangingPassword;
                        if (!_isChangingPassword) {
                          _currentPasswordController.clear();
                          _newPasswordController.clear();
                          _confirmPasswordController.clear();
                        }
                      });
                    },
                  ),
                  if (_isChangingPassword)
                    Form(
                      key: _passwordFormKey,
                      child: Column(
                        children: [
                          const SizedBox(height: 4),
                          // Mot de passe actuel
                          TextFormField(
                            controller: _currentPasswordController,
                            obscureText: !_showCurrentPassword,
                            decoration: InputDecoration(
                              labelText: 'Mot de passe actuel',
                              prefixIcon: const Icon(Icons.lock_outline),
                              border: const OutlineInputBorder(),
                              suffixIcon: IconButton(
                                icon: Icon(_showCurrentPassword
                                    ? Icons.visibility_off
                                    : Icons.visibility),
                                onPressed: () => setState(
                                    () => _showCurrentPassword = !_showCurrentPassword),
                              ),
                            ),
                            validator: (value) {
                              if (value == null || value.isEmpty) {
                                return 'Veuillez saisir votre mot de passe actuel';
                              }
                              return null;
                            },
                          ),
                          const SizedBox(height: 15),
                          // Nouveau mot de passe
                          TextFormField(
                            controller: _newPasswordController,
                            obscureText: !_showNewPassword,
                            decoration: InputDecoration(
                              labelText: 'Nouveau mot de passe',
                              prefixIcon: const Icon(Icons.lock_reset),
                              border: const OutlineInputBorder(),
                              suffixIcon: IconButton(
                                icon: Icon(_showNewPassword
                                    ? Icons.visibility_off
                                    : Icons.visibility),
                                onPressed: () =>
                                    setState(() => _showNewPassword = !_showNewPassword),
                              ),
                            ),
                            validator: (value) {
                              if (value == null || value.isEmpty) {
                                return 'Veuillez saisir un nouveau mot de passe';
                              }
                              if (value.length < 8) {
                                return 'Le mot de passe doit contenir au moins 8 caractères';
                              }
                              if (value == _currentPasswordController.text) {
                                return 'Le nouveau mot de passe doit être différent de l\'actuel';
                              }
                              return null;
                            },
                          ),
                          const SizedBox(height: 15),
                          // Confirmation
                          TextFormField(
                            controller: _confirmPasswordController,
                            obscureText: !_showConfirmPassword,
                            decoration: InputDecoration(
                              labelText: 'Confirmer le nouveau mot de passe',
                              prefixIcon: const Icon(Icons.lock_clock),
                              border: const OutlineInputBorder(),
                              suffixIcon: IconButton(
                                icon: Icon(_showConfirmPassword
                                    ? Icons.visibility_off
                                    : Icons.visibility),
                                onPressed: () => setState(
                                    () => _showConfirmPassword = !_showConfirmPassword),
                              ),
                            ),
                            validator: (value) {
                              if (value == null || value.isEmpty) {
                                return 'Veuillez confirmer le mot de passe';
                              }
                              if (value != _newPasswordController.text) {
                                return 'Les mots de passe ne correspondent pas';
                              }
                              return null;
                            },
                          ),
                          const SizedBox(height: 20),
                          ElevatedButton(
                            onPressed: authProvider.isLoading ? null : _submitPasswordChange,
                            style: ElevatedButton.styleFrom(
                              backgroundColor: const Color(0xFF000080),
                              foregroundColor: Colors.white,
                              minimumSize: const Size.fromHeight(50),
                            ),
                            child: authProvider.isLoading
                                ? const SizedBox(
                                    height: 20,
                                    width: 20,
                                    child: CircularProgressIndicator(
                                        color: Colors.white, strokeWidth: 2),
                                  )
                                : const Text('CHANGER LE MOT DE PASSE'),
                          ),
                          const SizedBox(height: 12),
                        ],
                      ),
                    ),
                ],
              ),
            ),

            const Divider(height: 1, thickness: 1),

            // ── Section Journal Émotionnel ──────────────────
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
