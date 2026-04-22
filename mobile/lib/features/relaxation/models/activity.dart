class Activity {
  final int id;
  final String title;
  final String description;
  final String category; // 'Méditation', 'Musique', 'Respiration', etc.
  final String? imageUrl;
  final String? videoUrl;

  Activity({
    required this.id,
    required this.title,
    required this.description,
    required this.category,
    this.imageUrl,
    this.videoUrl,
  });

  factory Activity.fromJson(Map<String, dynamic> json) {
    return Activity(
      id: json['id'],
      title: json['title'] ?? '',
      description: json['description'] ?? '',
      category: json['category'] ?? 'Détente',
      imageUrl: json['image_url'],
      videoUrl: json['video_url'],
    );
  }
}
