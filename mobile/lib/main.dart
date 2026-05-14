import 'package:flutter/material.dart';
import 'package:flutter/rendering.dart';
import 'package:provider/provider.dart';
import 'features/auth/providers/auth_provider.dart';
import 'features/diagnostics/providers/diagnostic_provider.dart';
import 'features/tracker/providers/emotion_provider.dart';
import 'features/relaxation/providers/relaxation_provider.dart';
import 'features/auth/screens/login_screen.dart';
import 'features/auth/screens/profile_screen.dart';
import 'features/diagnostics/screens/diagnostic_screen.dart';
import 'features/exercises/screens/breathing_screen.dart';
import 'features/informations/screens/info_screen.dart';
import 'features/informations/providers/information_provider.dart';
import 'features/tracker/screens/tracker_screen.dart';
import 'features/relaxation/screens/relaxation_screen.dart';
import 'features/wear/wear_sync_service.dart';

void main() {
  runApp(
    MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider()),
        ChangeNotifierProvider(create: (_) => DiagnosticProvider()),
        ChangeNotifierProvider(create: (_) => EmotionProvider()),
        ChangeNotifierProvider(create: (_) => RelaxationProvider()),
        ChangeNotifierProvider(create: (_) => InformationProvider()),
      ],
      child: const MyApp(),
    ),
  );
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'CESIZen',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: const Color(0xFF000080)),
        useMaterial3: true,
        scaffoldBackgroundColor: Colors.white,
      ),
      home: const HomeScreen(),
    );
  }
}

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final ScrollController _scrollController = ScrollController();
  bool _isFabExpanded = true;

  @override
  void initState() {
    super.initState();
    _scrollController.addListener(_onScroll);
    WidgetsBinding.instance.addPostFrameCallback((_) {
      WearSyncService.initialize(context);
    });
  }

  @override
  void dispose() {
    _scrollController.removeListener(_onScroll);
    _scrollController.dispose();
    super.dispose();
  }

  void _onScroll() {
    if (_scrollController.position.userScrollDirection == ScrollDirection.reverse) {
      if (_isFabExpanded) {
        setState(() => _isFabExpanded = false);
      }
    } else if (_scrollController.position.userScrollDirection == ScrollDirection.forward) {
      if (!_isFabExpanded) {
        setState(() => _isFabExpanded = true);
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);

    if (authProvider.isLoading && authProvider.user == null) {
      return const Scaffold(
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              CircularProgressIndicator(color: Color(0xFF000080)),
              SizedBox(height: 20),
              Text("Synchronisation en cours...", style: TextStyle(color: Color(0xFF000080), fontWeight: FontWeight.bold)),
            ],
          ),
        ),
      );
    }

    final bool isLoggedIn = authProvider.isAuthenticated;
    final String userName = isLoggedIn ? (authProvider.user?.name ?? 'Utilisateur') : 'Visiteur';

    if (isLoggedIn) {
      WearSyncService.checkAndPromptWearSync(context, authProvider.user?.name);
    }

    return Scaffold(
      body: SafeArea(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // HEADER FIXE (Logos + Bonjour + Message)
            Padding(
              padding: const EdgeInsets.fromLTRB(24, 20, 24, 20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Image.asset('assets/images/ministere.png', height: 60),
                      Image.asset('assets/images/CesiZen.png', height: 40),
                    ],
                  ),
                  const SizedBox(height: 30),
                  const Text('Bonjour,', style: TextStyle(fontSize: 18, color: Colors.grey)),
                  Text(
                    userName,
                    style: const TextStyle(fontSize: 32, fontWeight: FontWeight.bold),
                  ),
                  Container(
                    margin: const EdgeInsets.only(top: 8, bottom: 20),
                    width: 40,
                    height: 4,
                    decoration: BoxDecoration(
                      color: Colors.orange.shade300,
                      borderRadius: BorderRadius.circular(2),
                    ),
                  ),
                  Container(
                    width: double.infinity,
                    padding: const EdgeInsets.all(20),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(20),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withOpacity(0.05),
                          blurRadius: 10,
                          offset: const Offset(0, 4),
                        ),
                      ],
                      border: Border.all(color: Colors.grey.shade100),
                    ),
                    child: const Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Prenez soin de vous',
                          style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Color(0xFF000080)),
                        ),
                        SizedBox(height: 8),
                        Text(
                          'Retrouvez vos outils de suivi et de détente.',
                          style: TextStyle(color: Colors.grey),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),

            // ZONE SCROLLABLE (UNIQUEMENT LES BOUTONS)
            Expanded(
              child: GridView.count(
                controller: _scrollController,
                padding: const EdgeInsets.fromLTRB(24, 0, 24, 100),
                crossAxisCount: 2,
                crossAxisSpacing: 20,
                mainAxisSpacing: 20,
                children: [
                  _buildMenuCard(
                    context,
                    'Tracker',
                    Icons.access_time,
                    Colors.teal.shade300,
                    isLocked: !isLoggedIn,
                    onTap: () {
                      Navigator.push(context, MaterialPageRoute(builder: (_) => const TrackerScreen()));
                    },
                  ),
                  _buildMenuCard(
                    context,
                    'Diagnostics',
                    Icons.analytics_outlined,
                    Colors.orange.shade200,
                    isLocked: !isLoggedIn,
                    onTap: () {
                      Navigator.push(context, MaterialPageRoute(builder: (_) => const DiagnosticScreen()));
                    },
                  ),
                  _buildMenuCard(
                    context,
                    'Respiration',
                    Icons.explore_outlined,
                    Colors.indigo.shade300,
                    onTap: () {
                      Navigator.push(context, MaterialPageRoute(builder: (_) => const BreathingScreen()));
                    },
                  ),
                  _buildMenuCard(
                    context,
                    'Détente',
                    Icons.play_arrow_outlined,
                    Colors.red.shade300,
                    onTap: () {
                      Navigator.push(context, MaterialPageRoute(builder: (_) => const RelaxationScreen()));
                    },
                  ),
                  _buildMenuCard(
                    context,
                    'Informations',
                    Icons.info_outline,
                    Colors.lightBlue.shade300,
                    onTap: () {
                      Navigator.push(context, MaterialPageRoute(builder: (_) => const InfoScreen()));
                    },
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
      floatingActionButtonLocation: FloatingActionButtonLocation.endFloat,
      floatingActionButton: Padding(
        padding: const EdgeInsets.only(bottom: 20.0),
        child: InkWell(
          onTap: () {
            if (isLoggedIn) {
              Navigator.push(context, MaterialPageRoute(builder: (_) => const ProfileScreen()));
            } else {
              Navigator.push(context, MaterialPageRoute(builder: (_) => const LoginScreen()));
            }
          },
          child: AnimatedContainer(
            duration: const Duration(milliseconds: 300),
            curve: Curves.easeInOut,
            padding: const EdgeInsets.symmetric(horizontal: 15, vertical: 15),
            decoration: BoxDecoration(
              color: const Color(0xFF000080),
              borderRadius: BorderRadius.circular(_isFabExpanded ? 15 : 30),
              boxShadow: [
                BoxShadow(
                  color: const Color(0xFF000080).withOpacity(0.3),
                  blurRadius: 10,
                  offset: const Offset(0, 5),
                ),
              ],
            ),
            child: AnimatedSize(
              duration: const Duration(milliseconds: 300),
              curve: Curves.easeInOut,
              child: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  const Icon(Icons.lock, color: Colors.white, size: 20),
                  ClipRect(
                    child: AnimatedOpacity(
                      opacity: _isFabExpanded ? 1.0 : 0.0,
                      duration: const Duration(milliseconds: 200),
                      child: AnimatedSize(
                        duration: const Duration(milliseconds: 300),
                        curve: Curves.easeInOut,
                        child: _isFabExpanded
                            ? const Row(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  SizedBox(width: 10),
                                  Text(
                                    'Espace Santé',
                                    style: TextStyle(
                                      color: Colors.white,
                                      fontWeight: FontWeight.bold,
                                    ),
                                  ),
                                ],
                              )
                            : const SizedBox.shrink(),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildMenuCard(BuildContext context, String title, IconData icon, Color iconColor, {VoidCallback? onTap, bool isLocked = false}) {
    return InkWell(
      onTap: isLocked 
        ? () {
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(
                content: Text('Connectez-vous pour accéder à cette fonctionnalité'),
                backgroundColor: Color(0xFF000080),
              ),
            );
          }
        : onTap,
      borderRadius: BorderRadius.circular(20),
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: Colors.grey.shade200),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.02),
              blurRadius: 5,
              offset: const Offset(0, 2),
            ),
          ],
        ),
        child: Stack(
          children: [
            Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(
                    icon, 
                    size: 36, 
                    color: isLocked ? Colors.grey.shade300 : iconColor
                  ),
                  const SizedBox(height: 8),
                  Text(
                    title,
                    style: TextStyle(
                      fontWeight: FontWeight.w600, 
                      fontSize: 14,
                      color: isLocked ? Colors.grey : Colors.black87,
                    ),
                  ),
                ],
              ),
            ),
            if (isLocked)
              const Positioned(
                top: 10,
                right: 10,
                child: Icon(Icons.lock_outline, size: 18, color: Colors.grey),
              ),
          ],
        ),
      ),
    );
  }
}
