var boys = Array();
var girls = Array();
var sexo = "";
var max = 10;
var totalListaBoys = 0;
var totalListaGirls = 0;

$(document).ready(function (){
    
    $(".radio").selectradio();
    
    cargarNombres();
    
    $(".menu").click(function(evento){ 
        
        menu = $(this);
        
        if(menu.attr("href")=="search.html"){
              page = "search";
        }
        else { 
            page = "like";  
        }
    });

});

function cargarNombres(){

    $.post("client.php","var1=cargar",function (response){
                        var array = Array();
                        array = $.parseJSON(response);
                        boys = array[0];
                        girls = array[1];
                        totalListaBoys = boys.length;
                        totalListaGirls = girls.length;
                        
                        if(sexo=="girl"){
                            crearTabla(girls);
                        }
                        else crearTabla(boys);
                    });               
}


jQuery.fn.selectradio = function (){
        
        this.each(function (){
            elem=$(this);
            
            if(elem.prop("checked") == true){
                
                if(elem.attr("value")=="searchboy"){
                    sexo = "boy";
                }
                if(elem.attr("value")=="searchgirl") {
                    sexo = "girl";
                }
                if(elem.attr("value")=="likesboy"){
                    sexo = "boy";
                }
                if(elem.attr("value")=="likesgirl"){ 
                    sexo = "girl";
                };
            }
            
            elem.click(function(){
         //creo una variable elem con el elemento actual, suponemos un textarea
            var elem = $(this);
            
            if(elem.is(":checked")){

                if(elem.attr("value")=="searchboy"){
                    sexo = "boy";
                }
                if(elem.attr("value")=="searchgirl") {
                    sexo = "girl";
                }
                
                if(elem.attr("value")=="likesboy"){

                    sexo = "boy";
                    cargarNombres();

                }
                if(elem.attr("value")=="likesgirl"){
                    
                    sexo = "girl";
                    cargarNombres();
                    
                }
                max = 10;
            }  
      });
        
    });
    
        return this;
    };
    
    
function mostrarSugerencia(nombre){
    //jquery
    if(sexo=="boy"){
        $.post("client.php","var1=sugerenciasboys&var2="+nombre,function (response){
                        $('.contentsearch').text("Sugerencias : " + response);
                    });
    }
    else
    {
        $.post("client.php","var1=sugerenciasgirls&var2="+nombre,function (response){
                        $('.contentsearch').text("Sugerencias : " + response);
                    });
    }

}



function crearTabla(array){
    
    $(".contentlikes").text("");
    
    var mostrarDiez = max - 10;
    
    var tabla = '<table class="tabla">'+
                '<tr class="title"><td>Names</td>'+
                '<td>likes</td>'+
                '<td>dislikes</td>'+
                '<td>Click Like</td>'+
                '<td>Click Dislike</td></tr>';
                    
    for(mostrarDiez; mostrarDiez <= array.length; mostrarDiez++){
        tabla += '<tr><td>'+array[mostrarDiez][0]+'</td>'+
            '<td>'+array[mostrarDiez][2]+'</td>'+
            '<td>'+array[mostrarDiez][3]+'</td>'+
            '<td><input type="button" value="like" name="'+array[mostrarDiez][0]+'" onclick="sumarlike(this)" /></td>'+
            '<td><input type="button" value="dislike" name="'+array[mostrarDiez][0]+'" onclick="restarlike(this)" /></td></tr>';
        
        if(mostrarDiez==max-1) break;
    }
    tabla += '<tr><td></td><td><input type="button" value="<<<" onclick="pageBefore()" /></td><td><input type="button" value=">>>" onclick="pageNext()" /></td><td></td></tr>';
    tabla = tabla + "</table>";
    
    $(".contentlikes").append(tabla);
    
}

function sumarlike(obj){
    
    $.post("client.php","var1=like&var2="+obj.name,function (response){
                        cargarNombres();
                    });
    
}

function restarlike(obj){

    $.post("client.php","var1=dislike&var2="+obj.name,function (response){
                        cargarNombres();
                    });
}

function pageNext(){

    if(max+10<=40){
        max = max+10;
        cargarNombres();
        $(".before").css("display","block");
    }
    else{
        $(".next").css("display","none");
        $(".before").css("display","block");
        alert("No hay mas");
    }

}

function pageBefore(){

    if(max-10>=10){
        max = max-10;
        cargarNombres();
        $(".next").css("display","block");
    }
    else{
        max=10;
        $(".before").css("display","none");
        $(".next").css("display","block");
        alert("No hay mas");
    }

}