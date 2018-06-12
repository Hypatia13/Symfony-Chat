var userInfo = {
    username: new Date().getTime().toString()
};

// Socket chat configuration
/**
 * @type WebSocket
 */

var socket = new WebSocket('ws://localhost:8080/');

socket.onopen = function(e) {
    console.info("Connection status OK!");
};

socket.onmessage = function(e) {
    var data = JSON.parse(e.data);
    ChatController.addMessage(data.username, data.message);
    
    console.log(data);
};

socket.onerror = function(e){
    alert("Something is wrong with your connection.");
    console.error(e);
};
// Socket chat config end


/// Adding messages to the list element
document.getElementById("submit-form").addEventListener("click",function(){
    var msg = document.getElementById("message-area").value;
    
    if(!msg){
        alert("You haven't written anything yet!");
    }
    
    Chat.sendMessage(msg);
    document.getElementById("form-message").value = "";
}, false);

// Send the message and add it to a list.
var ChatController = {
    addMessage: function(username,message){
        var from;
        
        if(username == userInfo.username){
            from = "Me";
        }else{
            from = userInfo.username;
        }
        
        var ul = document.getElementById("messages-list");
        var li = document.createElement("li");
        li.appendChild(document.createTextNode(from + " : "+ message));
        ul.appendChild(li);
    },
    sendMessage: function(text){
        userInfo.message = text;
        socket.send(JSON.stringify(userInfo));
        this.addMessage(userInfo.username, userInfo.message);
    }
};