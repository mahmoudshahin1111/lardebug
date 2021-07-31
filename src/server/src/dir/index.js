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
app.use(express.static(path.join(__dirname, '/public')));
app.use(express.json({ limit: '500mb' }));
app.use(cors.default({ origin: true }));
// app.get("/", function (req: express.Request, res: express.Response) {
//   return res.sendFile(path.join(__dirname, "/public/index.html"));
// });
app.post("/lardebug/start", function (req, res) {
    io.emit("start", null);
    res.status(200).send();
});
app.post("/lardebug/collect", function (req, res) {
    io.emit("collect", req.body);
    res.status(200).send();
});
app.post("/lardebug/console", function (req, res) {
    io.emit("console", req.body);
    res.status(200).send();
});
app.post("/lardebug/end", function (req, res) {
    io.emit("end", null);
    res.status(200).send();
});
io.on("connection", function (socket) {
    console.log("client listening io " + socket.id);
});
var ServerConfigManager = /** @class */ (function () {
    function ServerConfigManager() {
        this.config = null;
    }
    ServerConfigManager.prototype.boot = function () {
        this.loadConfig();
    };
    ServerConfigManager.prototype.getPort = function () {
        var _a;
        return (_a = this.config) === null || _a === void 0 ? void 0 : _a.server.port;
    };
    ServerConfigManager.prototype.getHost = function () {
        var _a;
        return (_a = this.config) === null || _a === void 0 ? void 0 : _a.server.host;
    };
    ServerConfigManager.prototype.loadConfig = function () {
        this.config = require(this.getConfigFilePath());
    };
    ServerConfigManager.prototype.getConfigFilePath = function () {
        return path.join(__dirname, '/../../../../config/lardebug.json');
    };
    return ServerConfigManager;
}());
var serverConfigManager = new ServerConfigManager();
serverConfigManager.boot();
server.listen(serverConfigManager.getPort(), function () {
    console.log("debug server on " + serverConfigManager.getHost() + ":" + serverConfigManager.getPort());
    console.log(__dirname);
});
