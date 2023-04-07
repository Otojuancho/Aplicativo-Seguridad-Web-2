$(document).ready(function(){
    if(document.getElementById("chatbox")){
        setInterval(function(){
            var anticsrf = $("#anticsrf").val();
            var idchat = $("#persona_chat").val();
            updateUserChat(anticsrf,idchat);			
        }, 1000);
    }

    $("#submitmsg").click(function(e){
        e.preventDefault();
        var archivo = new FormData();
        var clientmsg = document.getElementById('usermsg').value;
        var anticsrf = document.getElementById('anticsrf').value;
        var idchat = document.getElementById('persona_chat').value;
        var formarch= document.getElementById("fulAdjunto").files[0];
        archivo.append("text", clientmsg);
        archivo.append("anticsrf", anticsrf);
        archivo.append("fulAdjunto", formarch);
        archivo.append("id", idchat);
        archivo.append("action", 'enviar_mensaje');
        if (clientmsg.length == 0 && formarch == null || anticsrf.length == 0 || idchat.length == 0) {
            console.log(clientmsg);
            return;
        }
        else{
            /*$.post("http://localhost/proyectoEPD1/libs/php/funciones_ajax.php", {text: clientmsg, anticsrf: anticsrf, id: idchat, action:'enviar_mensaje'});				
            document.getElementById("usermsg").value = "";
            document.getElementById("fulAdjunto").value = null
            return false;
            asi tambien se puede enviar por post*/
            $.ajax({
                url: "http://localhost/proyectoEPD1/libs/php/funciones_ajax.php",
                type: "POST",
                data: archivo,
                processData: false,  // tell jQuery not to process the data
                contentType: false   // tell jQuery not to set contentType
            })
                .done(function(res){

                })
                .fail(function(){
                    console.log("error");
                })
                .always(function(){
                    document.getElementById("usermsg").value = "";
                    document.getElementById("fulAdjunto").value = null
            });
        }
	});
    $("#persona_chat").click(function(){
        var anticsrf = $("#anticsrf").val();
        var idchat = $("#persona_chat").val();
        if (anticsrf.length == 0 || idchat.length == 0 || idchat == 0) {
            return;
        }
        else{
            updateUserChat(anticsrf,idchat);				
            return false;
        }
	});
    /*$("#submitmsg").click(function(event){
        var clientmsg = $("#usermsg").val();
        var anticsrf = $("#anticsrf").val();
        var idchat = $("#persona_chat").val();	
        sendMessage(clientmsg,anticsrf,idchat);
    });*/
    
});

function showHint(str,anticrf) {
        
    if (str.length == 0 || anticrf.length == 0) {
        document.getElementById("refresh").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("refresh").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "http://localhost/proyectoEPD1/libs/php/funciones_ajax.php?like=" + str +"&anticsrf="+anticrf, true);
        xmlhttp.send();
    }
}
function updateUserChat(anticsrf,idchat){
    if (anticsrf.length == 0 || idchat.length == 0 || idchat == 0) {
        return;
    } else {
        var oldscrol = document.getElementById("chatbox").scrollHeight; 
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("chatbox").innerHTML = this.responseText;
                var newscrol = document.getElementById("chatbox").scrollHeight;
                if(newscrol > oldscrol)
                {
                    document.getElementById("chatbox").scrollTop= newscrol ;
                }            
            }
        };
        xmlhttp.open("GET", "http://localhost/proyectoEPD1/libs/php/funciones_ajax.php?anticsrf=" + anticsrf +"&id="+idchat+"&action=actualizarchat", true);
        xmlhttp.send();
    }
}
