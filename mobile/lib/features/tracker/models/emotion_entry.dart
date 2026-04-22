class EmotionEntry {
  final int? id;
  final String emotion; // ex: 'Heureux', 'Triste', 'Stressé'
  final String? note;
  final DateTime createdAt;

  EmotionEntry({
    this.id,
    required this.emotion,
    this.note,
    required this.createdAt,
  });

  factory EmotionEntry.fromJson(Map<String, dynamic> json) {
    return EmotionEntry(
      id: json['id'],
      emotion: json['emotion_name'] ?? json['emotion'] ?? '',
      note: json['note'],
      createdAt: DateTime.parse(json['created_at']),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'emotion_name': emotion,
      'note': note,
    };
  }
}
