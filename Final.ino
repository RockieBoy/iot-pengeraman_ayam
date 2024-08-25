#include <PubSubClient.h>
#include <WiFi.h>
#include <DHT.h>

// Definisikan pin untuk DHT sensor pada ESP32
#define DHTPIN 16
#define DHTTYPE DHT22 

// Definisikan pin untuk LAMP (lampu) dan FAN (kipas)
#define LAMP 12
#define FAN 14

// Buat instance DHT sensor
DHT dht(DHTPIN, DHTTYPE);

const char* mqtt_server = "public.cloud.shiftr.io";
const int mqtt_port = 1883;
const char* mqtt_user = "public";
const char* mqtt_password = "public";
const char* temp_topic = "kandang_ayam/temperature";
const char* humidity_topic = "kandang_ayam/humidity";
const char* fan_topic = "kandang_ayam/fan";
const char* lamp_topic = "kandang_ayam/lamp";

char ssid[] = "ayamjago";
char pass[] = "ayamjago";

WiFiClient espClient;
PubSubClient client(espClient);

bool fan_control_manual = false;  
bool lamp_control_manual = false; 

void setup() {
  Serial.begin(9600);
  WiFi.begin(ssid, pass);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nConnected to WiFi");

  client.setServer(mqtt_server, mqtt_port);
  client.setCallback(callback);
  
  // Inisialisasi pin LAMP dan FAN sebagai output
  pinMode(LAMP, OUTPUT);
  pinMode(FAN, OUTPUT);
  
  // Inisialisasi DHT sensor
  dht.begin();
}

void loop() {
  if (!client.connected()) {
    reconnect();
  }
  client.loop();

  // Baca nilai suhu dan kelembapan
  float humidity = dht.readHumidity();
  float temperature = dht.readTemperature();

  // Cek apakah pembacaan suhu atau kelembapan gagal
  if (isnan(humidity) || isnan(temperature)) {
    Serial.println("Failed to read from DHT sensor!");
    return;
  }

  // Publish suhu dan kelembapan
  char temp_str[8];
  dtostrf(temperature, 1, 2, temp_str);
  client.publish(temp_topic, temp_str);
  char hum_str[8];
  dtostrf(humidity, 1, 2, hum_str);
  client.publish(humidity_topic, hum_str);

  // Tampilkan suhu dan kelembapan di serial monitor
  Serial.print("Temperature: ");
  Serial.print(temperature);
  Serial.print("°C  ");
  Serial.print("Humidity: ");
  Serial.print(humidity);
  Serial.println("%");

  // Kontrol KIPAS berdasarkan suhu atau manual override
  if (!fan_control_manual) {
    if (temperature > 36) {
      digitalWrite(FAN, HIGH);  // Nyalakan KIPAS
      Serial.println("FAN ON");
      client.publish(fan_topic, "on");
    } else {
      digitalWrite(FAN, LOW);   // Matikan KIPAS
      Serial.println("FAN OFF");
      client.publish(fan_topic, "off");
    }
  } else {
    // Jika manual control diaktifkan, cek apakah kondisi suhu memerlukan reset manual control
    if (temperature > 36) {
      fan_control_manual = false;
    }
  }
  
  // Kontrol LAMPU berdasarkan kelembapan atau manual override
  if (!lamp_control_manual) {
    if (humidity > 60) {
      digitalWrite(LAMP, HIGH);  // Nyalakan LAMPU
      Serial.println("LAMP ON");
      client.publish(lamp_topic, "on");
    } else {
      digitalWrite(LAMP, LOW);   // Matikan LAMPU
      Serial.println("LAMP OFF");
      client.publish(lamp_topic, "off");
    }
  } else {
    // Jika manual control diaktifkan, cek apakah kondisi suhu memerlukan reset manual control
    if (humidity > 60) {
      lamp_control_manual = false;
    }
  }

  // Delay selama 2 detik sebelum pembacaan berikutnya
  delay(2000);
}

void callback(char* topic, byte* payload, unsigned int length) {
  String message;
  for (int i = 0; i < length; i++) {
    message += (char)payload[i];
  }

  Serial.print("Received message: ");
  Serial.print(message);
  Serial.print(" on topic: ");
  Serial.println(topic);

  if (String(topic) == fan_topic) {
    if (message == "on") {
      fan_control_manual = true;
      digitalWrite(FAN, HIGH);
      Serial.println("Fan turned on manually");
    } else if (message == "off") {
      fan_control_manual = true;
      digitalWrite(FAN, LOW);
      Serial.println("Fan turned off manually");
    }
  } else if (String(topic) == lamp_topic) {
    if (message == "on") {
      lamp_control_manual = true;
      digitalWrite(LAMP, HIGH);
      Serial.println("Lamp turned on manually");
    } else if (message == "off") {
      lamp_control_manual = true;
      digitalWrite(LAMP, LOW);
      Serial.println("Lamp turned off manually");
    }
  }
}

void reconnect() {
  while (!client.connected()) {
    Serial.print("Attempting MQTT connection...");
    if (client.connect("ESP32Client", mqtt_user, mqtt_password)) {
      Serial.println("connected");
      client.subscribe(fan_topic);
      client.subscribe(lamp_topic);
    } else {
      Serial.print("failed, rc=");
      Serial.print(client.state());
      Serial.println(" try again in 5 seconds");
      delay(5000);
    }
  }
}
