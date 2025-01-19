const express  = require("express")
const mqtt  = require("mqtt")
const mongoose  = require("mongoose")
const nodeSchedule = require("node-schedule")
const app = express()
require('dotenv').config();
app.use(express.json())
// const server = http.createServer((req, res) => {
//     res.writeHead(200,{"Content-Type" : "text/html"})
//     res.write('<h1>Hello, Node.js HTTP Server!</h1>')
//     res.end()
// })

mongoose.connect(process.env.URL_MONGODB, { useNewUrlParser: true, useUnifiedTopology: true })
    .then(() => console.log("MongoDB Connected")).catch((err) => console.error(err))

const mqttClient = mqtt.connect('wss://public.cloud.shiftr.io:443/mqtt', {
    username: "public",
    password: "public"
});
mqttClient.on("connect", () => {
  console.log("MQTT Connected");
});

const controlLamp = (action) => {
    mqttClient.publish("lamp/control", action, () => {
      console.log(`Perintah dikirim: ${action}`);
    });
  };

const scheduleSchema = new mongoose.Schema({
    startTime : {type : String, required: true},
    endTime: {type : String, required: true},
  }, { timestamps: true }); // Menambahkan createdAt dan updatedAt
  
const Schedule = mongoose.model("Schedule", scheduleSchema);

const loadSchedule = async () => {
    const latestSchedule = Schedule.findOne().sort({createdAt : -1})

    if (latestSchedule) {
        const {startTime, endTime} = latestSchedule
        nodeSchedule.scheduleJob()

        const [startHour, startMinute] = startTime.split(":").map(Number)
        const [endHour, endMinute] = endTime.split(":").map(Number)

        nodeSchedule.scheduleJob({hour: startHour,minute : startMinute}, () => {
            console.log("Lampu nyala");
            controlLamp("ON")
        })

    }else{
        console.log("Jadwal tidak ditemukan");
        
    }
}

app.get("/api/schedule", async (req, res) => {
    const latestSchedule = Schedule.findOne().sort({createdAt : -1})
    res.json(latestSchedule)
})
app.post("/api/schedule", async (req, res) => {
    const {startHour, startMinute, stopHour, stopMinute} = req.body
    const newSchedule = new Schedule({startHour, startMinute, stopHour, stopMinute})
    await newSchedule.save()
    res.send("Jadwal berhasil disimpan!")
})

const port = 3000

app.listen(port,() => {
    console.log("Connected")
})