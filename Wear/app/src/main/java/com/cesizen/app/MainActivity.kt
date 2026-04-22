package com.cesizen.app

import android.os.Bundle
import android.util.Log
import android.widget.Toast
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Brush
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.vector.ImageVector
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.text.style.TextOverflow
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.wear.compose.foundation.lazy.TransformingLazyColumn
import androidx.wear.compose.foundation.lazy.rememberTransformingLazyColumnState
import androidx.wear.compose.material3.*
import com.cesizen.app.presentation.theme.MyApplicationTheme
import com.google.android.gms.tasks.Tasks
import com.google.android.gms.wearable.*
import org.json.JSONObject
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch

class MainActivity : ComponentActivity(), DataClient.OnDataChangedListener {
    
    private var userName by mutableStateOf<String?>(null)
    private var currentMood by mutableStateOf<String?>(null)
    private var isRefreshing by mutableStateOf(false)
    private val TAG = "CESIZen_Watch"

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        Wearable.getDataClient(this).addListener(this)
        refreshData()

        setContent {
            WearApp(
                userName = userName,
                currentMood = currentMood,
                isRefreshing = isRefreshing,
                onRefresh = { refreshData() },
                onMoodSelected = { mood -> sendMoodToPhone(mood) }
            )
        }
    }

    private fun refreshData() {
        if (isRefreshing) return
        isRefreshing = true
        
        val today = java.text.SimpleDateFormat("yyyy-MM-dd", java.util.Locale.getDefault()).format(java.util.Date())

        Wearable.getDataClient(this).dataItems.addOnSuccessListener { items ->
            for (item in items) {
                if (item.uri.path == "/auth_status") {
                    val dataMap = DataMapItem.fromDataItem(item).dataMap
                    userName = if (dataMap.getString("status") == "authenticated") dataMap.getString("userName") else null
                } else if (item.uri.path == "/daily_mood") {
                    val dataMap = DataMapItem.fromDataItem(item).dataMap
                    val moodDate = dataMap.getString("date")
                    
                    if (moodDate == today) {
                        currentMood = dataMap.getString("mood")
                    } else {
                        currentMood = null
                    }
                }
            }
            isRefreshing = false
        }

        CoroutineScope(Dispatchers.IO).launch {
            try {
                val nodes = Tasks.await(Wearable.getNodeClient(this@MainActivity).connectedNodes)
                for (node in nodes) {
                    val payload = JSONObject().apply { put("command", "get_sync_data") }.toString().toByteArray()
                    Wearable.getMessageClient(this@MainActivity).sendMessage(node.id, "/wearable_communication", payload)
                }
            } catch (e: Exception) {
                Log.e(TAG, "Error sync nodes", e)
            }
        }
    }

    private fun sendMoodToPhone(mood: String) {
        currentMood = mood
        if (mood.isEmpty()) {
            // Reset mood locally to show the selection again
            currentMood = null
        }
        
        CoroutineScope(Dispatchers.IO).launch {
            try {
                val nodes = Tasks.await(Wearable.getNodeClient(this@MainActivity).connectedNodes)
                for (node in nodes) {
                    val payload = JSONObject().apply { 
                        put("command", "save_mood")
                        put("mood", mood)
                    }.toString().toByteArray()
                    Wearable.getMessageClient(this@MainActivity).sendMessage(node.id, "/wearable_communication", payload)
                }
            } catch (e: Exception) {
                Log.e(TAG, "Error sending mood", e)
            }
        }
    }

    override fun onDataChanged(dataEvents: DataEventBuffer) {
        val today = java.text.SimpleDateFormat("yyyy-MM-dd", java.util.Locale.getDefault()).format(java.util.Date())
        
        for (event in dataEvents) {
            val dataMap = DataMapItem.fromDataItem(event.dataItem).dataMap
            if (event.dataItem.uri.path == "/auth_status") {
                userName = if (dataMap.getString("status") == "authenticated") dataMap.getString("userName") else null
            } else if (event.dataItem.uri.path == "/daily_mood") {
                val moodDate = dataMap.getString("date")
                if (moodDate == today) {
                    currentMood = dataMap.getString("mood")
                } else {
                    currentMood = null
                }
            }
        }
    }
}

@Composable
fun WearApp(userName: String?, currentMood: String?, isRefreshing: Boolean, onRefresh: () -> Unit, onMoodSelected: (String) -> Unit) {
    val cesiBlue = Color(0xFF000091)
    
    MyApplicationTheme {
        AppScaffold {
            val listState = rememberTransformingLazyColumnState()
            ScreenScaffold(scrollState = listState) { contentPadding ->
                TransformingLazyColumn(
                    modifier = Modifier
                        .fillMaxSize()
                        .background(Brush.verticalGradient(listOf(Color.Black, Color(0xFF000022)))),
                    contentPadding = contentPadding,
                    state = listState,
                    horizontalAlignment = Alignment.CenterHorizontally
                ) {
                    // Header
                    item {
                        Column(
                            horizontalAlignment = Alignment.CenterHorizontally,
                            modifier = Modifier.padding(bottom = 12.dp)
                        ) {
                            Text(
                                "CESIZen",
                                color = cesiBlue,
                                style = MaterialTheme.typography.labelMedium,
                                fontWeight = FontWeight.Bold
                            )
                            Spacer(modifier = Modifier.height(4.dp))
                        }
                    }

                    if (userName == null) {
                        item {
                            TitleCard(
                                onClick = onRefresh,
                                title = { Text("Connexion requise", textAlign = TextAlign.Center) },
                                subtitle = { Text("Ouvrez l'app sur votre téléphone", textAlign = TextAlign.Center, fontSize = 12.sp) },
                                modifier = Modifier.padding(horizontal = 8.dp)
                            )
                        }
                    } else {
                        // User Status
                        item {
                            val displayName = userName.split(" ").firstOrNull() ?: "Zen"
                            Text(
                                "Bonjour $displayName",
                                style = MaterialTheme.typography.titleMedium,
                                textAlign = TextAlign.Center
                            )
                        }

                        if (currentMood.isNullOrEmpty()) {
                            item {
                                Text(
                                    "Comment allez-vous ?",
                                    style = MaterialTheme.typography.bodySmall,
                                    color = Color.LightGray,
                                    modifier = Modifier.padding(vertical = 8.dp)
                                )
                            }

                            val moods = listOf(
                                MoodItem("Très bien", "😊", Color(0xFF4CAF50)),
                                MoodItem("Bien", "🙂", Color(0xFF8BC34A)),
                                MoodItem("Neutre", "😐", Color(0xFFFFC107)),
                                MoodItem("Pas top", "🙁", Color(0xFFFF9800)),
                                MoodItem("Stressé", "😫", Color(0xFFF44336))
                            )

                            moods.forEach { mood ->
                                item {
                                    FilledTonalButton(
                                        onClick = { onMoodSelected(mood.label) },
                                        modifier = Modifier
                                            .fillMaxWidth()
                                            .padding(vertical = 2.dp, horizontal = 16.dp),
                                        colors = ButtonDefaults.filledTonalButtonColors(
                                            containerColor = mood.color.copy(alpha = 0.2f),
                                            contentColor = Color.White
                                        )
                                    ) {
                                        Row(
                                            modifier = Modifier.fillMaxWidth(),
                                            verticalAlignment = Alignment.CenterVertically
                                        ) {
                                            Text(mood.emoji, fontSize = 20.sp)
                                            Spacer(modifier = Modifier.width(12.dp))
                                            Text(mood.label, style = MaterialTheme.typography.labelLarge)
                                        }
                                    }
                                }
                            }
                        } else {
                            item {
                                Card(
                                    onClick = { onMoodSelected("") },
                                    modifier = Modifier.padding(16.dp),
                                    colors = CardDefaults.cardColors(containerColor = cesiBlue.copy(alpha = 0.3f))
                                ) {
                                    Column(
                                        modifier = Modifier.fillMaxWidth().padding(8.dp),
                                        horizontalAlignment = Alignment.CenterHorizontally
                                    ) {
                                        Text("Humeur enregistrée", style = MaterialTheme.typography.labelSmall)
                                        Spacer(modifier = Modifier.height(8.dp))
                                        Text(currentMood, style = MaterialTheme.typography.titleLarge, fontWeight = FontWeight.Bold)
                                        Spacer(modifier = Modifier.height(8.dp))
                                        Text("Tapoter pour changer", fontSize = 10.sp, color = Color.LightGray)
                                    }
                                }
                            }
                        }
                    }
                    
                    // Bottom Spacer
                    item {
                        Spacer(modifier = Modifier.height(20.dp))
                    }
                }
            }
        }
    }
}

data class MoodItem(val label: String, val emoji: String, val color: Color)
