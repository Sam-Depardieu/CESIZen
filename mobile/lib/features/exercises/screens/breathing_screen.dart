import 'dart:async';
import 'package:flutter/material.dart';

class BreathingMode {
  final String name;
  final String description;
  final int inhale;
  final int hold;
  final int exhale;
  final Color color;

  BreathingMode({
    required this.name,
    required this.description,
    required this.inhale,
    required this.hold,
    required this.exhale,
    required this.color,
  });
}

class BreathingScreen extends StatefulWidget {
  const BreathingScreen({super.key});

  @override
  State<BreathingScreen> createState() => _BreathingScreenState();
}

class _BreathingScreenState extends State<BreathingScreen> with TickerProviderStateMixin {
  late AnimationController _controller;
  late Animation<double> _animation;
  
  final List<BreathingMode> _modes = [
    BreathingMode(
      name: "7-4-8",
      description: "Sommeil & Détente profonde",
      inhale: 7,
      hold: 4,
      exhale: 8,
      color: Colors.indigo,
    ),
    BreathingMode(
      name: "5-5",
      description: "Cohérence Cardiaque classique",
      inhale: 5,
      hold: 0,
      exhale: 5,
      color: Colors.teal,
    ),
    BreathingMode(
      name: "4-6",
      description: "Réduction rapide du stress",
      inhale: 4,
      hold: 0,
      exhale: 6,
      color: Colors.orange,
    ),
  ];

  late BreathingMode _selectedMode;
  bool _isStarted = false;
  String _actionText = "Prêt ?";
  int _secondsRemaining = 0;
  Timer? _timer;
  int _phaseSecondsRemaining = 0;

  @override
  void initState() {
    super.initState();
    _selectedMode = _modes[1]; // 5-5 par défaut
    _setupController();
  }

  void _setupController() {
    _controller = AnimationController(vsync: this);
    _animation = Tween<double>(begin: 0.6, end: 1.0).animate(
      CurvedAnimation(parent: _controller, curve: Curves.easeInOut),
    );
  }

  Future<void> _runExercise() async {
    while (_isStarted && _secondsRemaining > 0) {
      // Phase 1 : Inspiration
      if (!_isStarted) break;
      setState(() => _actionText = "Inspirez");
      _controller.duration = Duration(seconds: _selectedMode.inhale);
      _controller.forward();
      await _waitForPhase(_selectedMode.inhale);

      // Phase 2 : Apnée (Hold)
      if (!_isStarted) break;
      if (_selectedMode.hold > 0) {
        setState(() => _actionText = "Bloquez");
        await _waitForPhase(_selectedMode.hold);
      }

      // Phase 3 : Expiration
      if (!_isStarted) break;
      setState(() => _actionText = "Expirez");
      _controller.duration = Duration(seconds: _selectedMode.exhale);
      _controller.reverse();
      await _waitForPhase(_selectedMode.exhale);
    }
    if (_isStarted) _stopExercise();
  }

  Future<void> _waitForPhase(int seconds) async {
    _phaseSecondsRemaining = seconds;
    for (int i = 0; i < seconds; i++) {
      if (!_isStarted) return;
      await Future.delayed(const Duration(seconds: 1));
      if (mounted) setState(() => _phaseSecondsRemaining--);
    }
  }

  void _startExercise() {
    setState(() {
      _isStarted = true;
      _secondsRemaining = 300; // 5 minutes
    });
    
    _timer = Timer.periodic(const Duration(seconds: 1), (timer) {
      if (_secondsRemaining > 0) {
        setState(() => _secondsRemaining--);
      } else {
        _stopExercise();
      }
    });

    _runExercise();
  }

  void _stopExercise() {
    _timer?.cancel();
    _controller.stop();
    _controller.reset();
    setState(() {
      _isStarted = false;
      _actionText = "Prêt ?";
    });
  }

  @override
  void dispose() {
    _timer?.cancel();
    _controller.dispose();
    super.dispose();
  }

  String _formatTime(int seconds) {
    int mins = seconds ~/ 60;
    int secs = seconds % 60;
    return "$mins:${secs.toString().padLeft(2, '0')}";
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: const Text('Respiration Guidée', style: TextStyle(fontWeight: FontWeight.bold)),
        elevation: 0,
        backgroundColor: Colors.white,
      ),
      body: Container(
        width: double.infinity,
        padding: const EdgeInsets.symmetric(horizontal: 24),
        child: Column(
          children: [
            if (!_isStarted) ...[
              const SizedBox(height: 20),
              const Text(
                "Choisissez votre exercice",
                style: TextStyle(fontSize: 18, color: Colors.grey),
              ),
              const SizedBox(height: 20),
              ..._modes.map((mode) => _buildModeCard(mode)).toList(),
            ] else ...[
              const SizedBox(height: 40),
              Text(
                _formatTime(_secondsRemaining),
                style: const TextStyle(fontSize: 32, fontWeight: FontWeight.bold, letterSpacing: 2),
              ),
              const Spacer(),
              _buildAnimatedCircle(),
              const SizedBox(height: 20),
              if (_phaseSecondsRemaining > 0)
                Text(
                  "$_phaseSecondsRemaining",
                  style: TextStyle(fontSize: 40, fontWeight: FontWeight.w300, color: _selectedMode.color),
                ),
              const Spacer(),
              ElevatedButton(
                onPressed: _stopExercise,
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.red.shade50,
                  foregroundColor: Colors.red,
                  elevation: 0,
                  padding: const EdgeInsets.symmetric(horizontal: 50, vertical: 15),
                ),
                child: const Text("ARRÊTER LA SESSION", style: TextStyle(fontWeight: FontWeight.bold)),
              ),
              const SizedBox(height: 40),
            ]
          ],
        ),
      ),
    );
  }

  Widget _buildModeCard(BreathingMode mode) {
    final isSelected = _selectedMode == mode;
    return GestureDetector(
      onTap: () => setState(() => _selectedMode = mode),
      child: Container(
        margin: const EdgeInsets.only(bottom: 16),
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          color: isSelected ? mode.color.withOpacity(0.05) : Colors.white,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(
            color: isSelected ? mode.color : Colors.grey.shade200,
            width: isSelected ? 2 : 1,
          ),
        ),
        child: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: mode.color.withOpacity(0.1),
                shape: BoxShape.circle,
              ),
              child: Icon(Icons.air, color: mode.color),
            ),
            const SizedBox(width: 20),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(mode.name, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  Text(mode.description, style: TextStyle(color: Colors.grey.shade600, fontSize: 13)),
                ],
              ),
            ),
            if (isSelected)
              ElevatedButton(
                onPressed: _startExercise,
                style: ElevatedButton.styleFrom(
                  backgroundColor: mode.color,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                ),
                child: const Text("GO"),
              ),
          ],
        ),
      ),
    );
  }

  Widget _buildAnimatedCircle() {
    return ScaleTransition(
      scale: _animation,
      child: Container(
        width: 250,
        height: 250,
        decoration: BoxDecoration(
          shape: BoxShape.circle,
          gradient: RadialGradient(
            colors: [
              _selectedMode.color.withOpacity(0.2),
              _selectedMode.color.withOpacity(0.05),
            ],
          ),
          border: Border.all(color: _selectedMode.color.withOpacity(0.3), width: 8),
        ),
        child: Stack(
          alignment: Alignment.center,
          children: [
            // Logo de méditation en fond
            Opacity(
              opacity: 0.1,
              child: Image.asset(
                'assets/images/CesiZen logo.png',
                width: 150,
              ),
            ),
            Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Text(
                  _actionText,
                  style: TextStyle(
                    fontSize: 28,
                    fontWeight: FontWeight.bold,
                    color: _selectedMode.color,
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
