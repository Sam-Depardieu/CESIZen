package com.cesizen.app

import android.util.Log
import io.flutter.embedding.android.FlutterActivity
import io.flutter.embedding.engine.FlutterEngine
import io.flutter.plugin.common.MethodChannel
import com.google.android.gms.wearable.Wearable
import com.google.android.gms.wearable.PutDataMapRequest
import com.google.android.gms.wearable.CapabilityClient
import com.google.android.gms.tasks.Tasks
import org.json.JSONObject
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch

class MainActivity : FlutterActivity() {
    private val CHANNEL = "com.cesizen/wearable"
    private val TAG = "CESIZen_Mobile_Native"

    override fun configureFlutterEngine(flutterEngine: FlutterEngine) {
        super.configureFlutterEngine(flutterEngine)

        MethodChannel(flutterEngine.dartExecutor.binaryMessenger, CHANNEL).setMethodCallHandler { call, result ->
            when (call.method) {
                "sendAuthStatus" -> {
                    val status = call.argument<String>("status")
                    val userName = call.argument<String>("userName")
                    updateWearDataLayer(status, userName)
                    result.success(true)
                }
                "syncMood" -> {
                    val mood = call.argument<String>("mood")
                    updateMoodDataLayer(mood)
                    result.success(true)
                }
                "isWatchNearby" -> {
                    CoroutineScope(Dispatchers.IO).launch {
                        try {
                            val capabilityInfo = Tasks.await(
                                Wearable.getCapabilityClient(this@MainActivity)
                                    .getCapability("cesizen_wear_app", CapabilityClient.FILTER_ALL)
                            )
                            val isNearby = capabilityInfo.nodes.isNotEmpty()
                            runOnUiThread { result.success(isNearby) }
                        } catch (e: Exception) {
                            runOnUiThread { result.success(false) }
                        }
                    }
                }
                else -> {
                    result.notImplemented()
                }
            }
        }

        // Écouter les messages de la montre
        Wearable.getMessageClient(this).addListener { messageEvent ->
            Log.d(TAG, "Message received from watch: ${messageEvent.path}")
            if (messageEvent.path == "/wearable_communication") {
                try {
                    val dataString = String(messageEvent.data)
                    Log.d(TAG, "Payload: $dataString")
                    val json = JSONObject(dataString)
                    val command = json.optString("command")
                    
                    runOnUiThread {
                        val messenger = flutterEngine.dartExecutor.binaryMessenger
                        when (command) {
                            "get_sync_data" -> {
                                Log.d(TAG, "Command: get_sync_data -> calling requestStatusUpdate")
                                MethodChannel(messenger, CHANNEL).invokeMethod("requestStatusUpdate", null)
                            }
                            "save_mood" -> {
                                val mood = json.optString("mood")
                                Log.d(TAG, "Command: save_mood ($mood) -> calling saveMoodFromWear")
                                MethodChannel(messenger, CHANNEL).invokeMethod("saveMoodFromWear", mapOf("mood" to mood))
                                updateMoodDataLayer(mood)
                            }
                        }
                    }
                } catch (e: Exception) {
                    Log.e(TAG, "Error processing message", e)
                }
            }
        }
    }

    private fun updateWearDataLayer(status: String?, userName: String?) {
        val request = PutDataMapRequest.create("/auth_status")
        request.dataMap.putString("status", status ?: "unauthenticated")
        request.dataMap.putString("userName", userName ?: "")
        request.dataMap.putLong("timestamp", System.currentTimeMillis())
        val putDataRequest = request.asPutDataRequest().setUrgent()
        Wearable.getDataClient(this).putDataItem(putDataRequest)
    }

    private fun updateMoodDataLayer(mood: String?) {
        val request = PutDataMapRequest.create("/daily_mood")
        val today = java.text.SimpleDateFormat("yyyy-MM-dd", java.util.Locale.getDefault()).format(java.util.Date())
        
        request.dataMap.putString("mood", mood ?: "")
        request.dataMap.putString("date", today)
        request.dataMap.putLong("timestamp", System.currentTimeMillis())

        val putDataRequest = request.asPutDataRequest().setUrgent()
        Wearable.getDataClient(this).putDataItem(putDataRequest)
    }
}
