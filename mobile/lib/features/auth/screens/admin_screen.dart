import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/admin_provider.dart';
import '../models/user.dart';

class AdminScreen extends StatefulWidget {
  const AdminScreen({super.key});

  @override
  State<AdminScreen> createState() => _AdminScreenState();
}

class _AdminScreenState extends State<AdminScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() =>
        Provider.of<AdminProvider>(context, listen: false).loadUsers());
  }

  @override
  Widget build(BuildContext context) {
    final adminProvider = Provider.of<AdminProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Administration'),
        backgroundColor: const Color(0xFF000080),
        foregroundColor: Colors.white,
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () => adminProvider.loadUsers(),
          ),
        ],
      ),
      body: adminProvider.isLoading
          ? const Center(child: CircularProgressIndicator(color: Color(0xFF000080)))
          : ListView.builder(
              itemCount: adminProvider.users.length,
              padding: const EdgeInsets.all(16),
              itemBuilder: (context, index) {
                final user = adminProvider.users[index];
                return _buildUserCard(context, user, adminProvider);
              },
            ),
    );
  }

  Widget _buildUserCard(BuildContext context, User user, AdminProvider provider) {
    return Card(
      elevation: 2,
      margin: const EdgeInsets.only(bottom: 12),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
      child: ExpansionTile(
        leading: CircleAvatar(
          backgroundColor: user.isAdmin ? Colors.orange.shade100 : Colors.blue.shade100,
          child: Icon(
            user.isAdmin ? Icons.admin_panel_settings : Icons.person,
            color: user.isAdmin ? Colors.orange : Colors.blue,
          ),
        ),
        title: Text(
          user.name,
          style: const TextStyle(fontWeight: FontWeight.bold),
        ),
        subtitle: Text(user.email),
        trailing: Container(
          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
          decoration: BoxDecoration(
            color: user.isActive ? Colors.green.shade100 : Colors.red.shade100,
            borderRadius: BorderRadius.circular(10),
          ),
          child: Text(
            user.isActive ? 'Actif' : 'Inactif',
            style: TextStyle(
              color: user.isActive ? Colors.green : Colors.red,
              fontSize: 12,
              fontWeight: FontWeight.bold,
            ),
          ),
        ),
        children: [
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceAround,
              children: [
                _buildActionButton(
                  icon: user.isActive ? Icons.block : Icons.check_circle_outline,
                  label: user.isActive ? 'Désactiver' : 'Activer',
                  color: user.isActive ? Colors.orange : Colors.green,
                  onTap: () => provider.toggleUserStatus(user),
                ),
                _buildActionButton(
                  icon: Icons.swap_horiz,
                  label: user.isAdmin ? 'Passer User' : 'Passer Admin',
                  color: Colors.blue,
                  onTap: () => _showRoleDialog(context, user, provider),
                ),
                _buildActionButton(
                  icon: Icons.delete_outline,
                  label: 'Supprimer',
                  color: Colors.red,
                  onTap: () => _showDeleteConfirm(context, user, provider),
                ),
              ],
            ),
          )
        ],
      ),
    );
  }

  Widget _buildActionButton({
    required IconData icon,
    required String label,
    required Color color,
    required VoidCallback onTap,
  }) {
    return InkWell(
      onTap: onTap,
      child: Column(
        children: [
          Icon(icon, color: color),
          const SizedBox(height: 4),
          Text(
            label,
            style: TextStyle(color: color, fontSize: 11, fontWeight: FontWeight.bold),
          ),
        ],
      ),
    );
  }

  void _showRoleDialog(BuildContext context, User user, AdminProvider provider) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Changer le rôle'),
        content: Text('Voulez-vous changer le rôle de ${user.name} ?'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('ANNULER')),
          ElevatedButton(
            onPressed: () {
              provider.changeUserRole(user, user.isAdmin ? 'User' : 'Admin');
              Navigator.pop(context);
            },
            child: const Text('CONFIRMER'),
          ),
        ],
      ),
    );
  }

  void _showDeleteConfirm(BuildContext context, User user, AdminProvider provider) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Supprimer l\'utilisateur'),
        content: Text('Êtes-vous sûr de vouloir supprimer ${user.name} ? Cette action est irréversible.'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('ANNULER')),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
            onPressed: () {
              provider.deleteUser(user.id);
              Navigator.pop(context);
            },
            child: const Text('SUPPRIMER', style: TextStyle(color: Colors.white)),
          ),
        ],
      ),
    );
  }
}
