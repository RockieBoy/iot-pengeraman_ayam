const express  = require("express")
const mqtt  = require("mqtt")
const mongoose  = require("mongoose")
const cors = require('cors');
const nodeSchedule = require("node-schedule")
const bodyParser = require('body-parser');
const app = express()
require('dotenv').config();
app.use(cors());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(express.json())
// const server = http.createServer((req, res) => {
//     res.writeHead(200,{"Content-Type" : "text/html"})
//     res.write('<h1>Hello, Node.js HTTP Server!</h1>')
//     res.end()
// })

mongoose.connect(process.env.URL_MONGODB)
    .then(() => console.log("MongoDB Connected")).catch((err) => console.error(err))

const mqttClient = mqtt.connect('wss://public.cloud.shiftr.io:443/mqtt', {
    username: "public",
    password: "public"
});

mqttClient.on("connect", () => {
  console.log("MQTT Connected");
});

const controlLamp = (action) => {
    mqttClient.publish('kandang_ayam/lamp', action ,() => {
        console.log(`Status lampu: ${action}`);
    });
};
const controlFan = (action) => {
    mqttClient.publish('kandang_ayam/fan', action ,() => {
        console.log(`Status kipas: ${action}`);
    });
};

const scheduleSchema = new mongoose.Schema({
    startTime : {type : String, required: true},
    endTime: {type : String, required: true},
  }, { timestamps: true });
  
const fanSchema = new mongoose.Schema({
    startTime : {type : String, required: true},
    endTime: {type : String, required: true},
  }, { timestamps: true });
  
const Schedule = mongoose.model("Schedule", scheduleSchema);
const ScheduleFan = mongoose.model("Fan", fanSchema);
let lampJobStart = null
let lampJobEnd = null
const loadSchedule = async () => {
    const latestSchedule = await Schedule.findOne().sort({createdAt : -1})
    if (latestSchedule) {
        const {startTime, endTime} = latestSchedule
        const ruleStart = new nodeSchedule.RecurrenceRule();
        const ruleEnd = new nodeSchedule.RecurrenceRule();
        const [startHour, startMinute] = startTime.split(":").map(Number)
        const [endHour, endMinute] = endTime.split(":").map(Number)
        ruleStart.hour = startHour;
        ruleStart.minute = startMinute; 
        ruleEnd.hour = endHour;
        ruleEnd.minute = endMinute; 

        if (lampJobStart) lampJobStart.cancel();
        if (lampJobEnd) lampJobEnd.cancel();
        
        lampJobStart = nodeSchedule.scheduleJob(ruleStart, () => {
            controlLamp("on")
        })

        lampJobEnd = nodeSchedule.scheduleJob(ruleEnd, () => {
            controlLamp("off")
        })

    }else{
        console.log("Jadwal lampu tidak ditemukan");
        
    }
}
let fanJobStart = null
let fanJobEnd = null
const loadScheduleFan = async () => {
    const latestSchedule = await ScheduleFan.findOne().sort({createdAt : -1})
    if (latestSchedule) {
        const {startTime, endTime} = latestSchedule
        const ruleStart = new nodeSchedule.RecurrenceRule();
        const ruleEnd = new nodeSchedule.RecurrenceRule();
        const [startHour, startMinute] = startTime.split(":").map(Number)
        const [endHour, endMinute] = endTime.split(":").map(Number)
        ruleStart.hour = startHour;
        ruleStart.minute = startMinute; 
        ruleEnd.hour = endHour;
        ruleEnd.minute = endMinute; 

        if (fanJobStart) fanJobStart.cancel();
        if (fanJobEnd) fanJobEnd.cancel();

        fanJobStart = nodeSchedule.scheduleJob(ruleStart, () => {
            controlFan("on")
        })

        fanJobEnd = nodeSchedule.scheduleJob(ruleEnd, () => {
            controlFan("off")
        })

    }else{
        console.log("Jadwal kipas tidak ditemukan");
        
    }
}
loadSchedule()
loadScheduleFan()

app.get("/api/schedule/lamp", async (req, res) => {
    const latestSchedule = await Schedule.findOne().sort({createdAt : -1})
    res.json(latestSchedule)
})
app.post("/api/schedule/lamp", async (req, res) => {
    console.log(req.body);
    const {startTime, endTime} = req.body
    const newSchedule = new Schedule({startTime, endTime})
    await newSchedule.save()
    loadSchedule()
    res.send("Berhasil mengubah jadwal lampu")
})
app.get("/api/schedule/fan", async (req, res) => {
    const latestSchedule = await ScheduleFan.findOne().sort({createdAt : -1})
    res.json(latestSchedule)
})
app.post("/api/schedule/fan", async (req, res) => {
    console.log(req.body);
    const {startTime, endTime} = req.body
    const newSchedule = new ScheduleFan({startTime, endTime})
    await newSchedule.save()
    // schedule.gracefulShutdown().then(() => {
    //     console.log("Semua jadwal lama dihapus.");
    //     loadAndScheduleTasks();
    // });
    loadScheduleFan()
    res.send("Berhasil mengubah jadwal kipas")
})


const port = 3000

app.listen(port,() => {
    console.log("Connected")
})