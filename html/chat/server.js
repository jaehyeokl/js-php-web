// LIVE 페이지의 실시간 채팅 서버
const path = require('path')
// const http = require('http')
const https = require('https')
const fs = require('fs');
// const cors = require('cors');

const express = require('express')
const socketIO = require('socket.io')

const publicPath = path.join(__dirname, '')
const port = process.env.PORT || 3000
let app = express()

// http.createServer(app)

const options = {
  key: fs.readFileSync('/etc/letsencrypt/live/jaehyeok.ml/privkey.pem'),
  cert: fs.readFileSync('/etc/letsencrypt/live/jaehyeok.ml/cert.pem'),
  ca: fs.readFileSync('/etc/letsencrypt/live/jaehyeok.ml/fullchain.pem')
}

let server = https.createServer(options, app)
let io = socketIO(server)


// 서버의 루트경로를 세팅한다
app.use(express.static(publicPath))

io.on('connection', (socket) => {
  console.log("A new user just connected")

  // 클라이언트가 접속을 끊었을때 이벤트
  socket.on('disconnect', () => {
    // console.log(name + " was disconnected");
    // socket.broadcast.emit("leave_chat", name);
  })


  // 특정 클라이언트로 부터 채팅에 참여할 이름을 전달받아
  // 전체 클라이언트에게 전달한다
  socket.on("join_chat", (name) => {
    console.log("join user is " + name)
    io.emit("join_chat", name)
  })

  // 특정 클라이언트로 부터 채팅 메세지를 전달받아
  // 해당 클라이언트를 제외한 나머지 클라언트 전체에 메세지를 전달한다
  // 메세지를 보낼때 클라이언트 자체적으로 메세지를 화면에 표시하기 때문에
  socket.on("send_message", (data) => {
    console.log("message : " + data.name + " : " + data.message)
    socket.broadcast.emit("send_message", data)
  })

})


server.listen(port, () => {
  console.log('server is up on port')
})