"use strict";
var __createBinding = (this && this.__createBinding) || (Object.create ? (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    Object.defineProperty(o, k2, { enumerable: true, get: function() { return m[k]; } });
}) : (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    o[k2] = m[k];
}));
var __setModuleDefault = (this && this.__setModuleDefault) || (Object.create ? (function(o, v) {
    Object.defineProperty(o, "default", { enumerable: true, value: v });
}) : function(o, v) {
    o["default"] = v;
});
var __importStar = (this && this.__importStar) || function (mod) {
    if (mod && mod.__esModule) return mod;
    var result = {};
    if (mod != null) for (var k in mod) if (k !== "default" && Object.prototype.hasOwnProperty.call(mod, k)) __createBinding(result, mod, k);
    __setModuleDefault(result, mod);
    return result;
};
Object.defineProperty(exports, "__esModule", { value: true });
var express = __importStar(require("express"));
var cors = __importStar(require("cors"));
var http = __importStar(require("http"));
var socketIo = __importStar(require("socket.io"));
var path = __importStar(require("path"));
var app = express.default();
var server = http.createServer(app);
var io = new socketIo.Server(server);
app.use(express.json());
app.use(cors.default({ origin: true }));
app.use(express.static("public"));
app.get("*", function (req, res) {
    return res.sendFile(path.join(__dirname, "/public/index.html"));
});
app.post("/laravelAnalysis/start", function (req, res) {
    io.emit("start", null);
    res.status(200).send();
});
app.post("/laravelAnalysis/collect", function (req, res) {
    io.emit("collect", req.body);
    res.status(200).send();
});
app.post("/laravelAnalysis/console", function (req, res) {
    io.emit("console", req.body);
    res.status(200).send();
});
app.post("/laravelAnalysis/end", function (req, res) {
    io.emit("end", null);
    res.status(200).send();
});
io.on("connection", function (socket) {
    console.log("client listening io " + socket.id);
});
server.listen(3000, function () {
    console.log("server listen on port 3000");
});
