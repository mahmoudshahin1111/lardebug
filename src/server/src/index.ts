import * as express from "express";
import * as cors from "cors";
import * as http from "http";
import * as socketIo from "socket.io";
import * as path from "path";
import * as childProcess from 'child_process';
import * as fs from 'fs';
const app = express.default();
const server = http.createServer(app);
const io = new socketIo.Server(server);

app.use(express.json());
app.use(cors.default({ origin: true }));
app.use(express.static("public"));
app.get("*", function (req: express.Request, res: express.Response) {
  return res.sendFile(path.join(__dirname, "/public/index.html"));
});
app.post(
  "/lardebug/start",
  function (req: express.Request, res: express.Response) {
    io.emit("start", null);
    res.status(200).send();
  }
);
app.post(
  "/lardebug/collect",
  function (req: express.Request, res: express.Response) {
    io.emit("collect", req.body);
    res.status(200).send();
  }
);
app.post(
  "/lardebug/console",
  function (req: express.Request, res: express.Response) {
    io.emit("console", req.body);
    res.status(200).send();
  }
);
app.post(
  "/lardebug/end",
  function (req: express.Request, res: express.Response) {
    io.emit("end", null);
    res.status(200).send();
  }
);
io.on("connection", function (socket) {
  console.log(`client listening io ${socket.id}`);
});


interface IConfig {
  server: {
    host: string,
    port: number;
  }
}
class ServerConfigManager {
  private config: IConfig | null = null;
  constructor() {

  }

  boot() {
    this.loadConfig();
  }
  public getPort() {
    return this.config?.server.port;
  }
  public getHost() {
    return this.config?.server.host;
  }
  private loadConfig() {
    this.config = require(this.getConfigFilePath());
  }
  private getConfigFilePath() {
    return path.join(__dirname, '/../../../../config/lardebug.json');
  }
}



const serverConfigManager = new ServerConfigManager();
serverConfigManager.boot();


server.listen(serverConfigManager.getPort(), function () {
  console.log(`debug server on ${serverConfigManager.getHost()}:${serverConfigManager.getPort()}`);
});
