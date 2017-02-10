$(document).ready(function() {


		$("#conteudo").hide();
		$("#cadastroUser").hide();
		$("#sucesso").hide();
		$("#sucesso2").hide();
		$("#cadastroApp").hide();
		
	    

	    $(document).on("keyup", "input" , function() {

		    var nome = $("#nome").val();
		   	var senha = $("#senha").val();		    	

		   	if (nome != '' && senha != '')
		   	{
		   		$("#logar").removeAttr("disabled");
		   	}    else {
		   			$("#logar").attr("disabled", true);  
		   		}  
	    });

	    $(document).on("keyup", "input" , function() {

		    var nomeUser = $("#nomeCadastroUser").val();
		   	var senhaCadastro = $("#senhaCadastroUser").val();		    	

		   	if (nomeUser != '' && senhaCadastro != '')
		   	{
		   		$("#btnCadastrarUser").removeAttr("disabled");
		   	}    else {
		   			$("#btnCadastrarUser").attr("disabled", true);  
		   		}  
	    });

	    $(document).on("keyup", "input" , function() {

		    var nomeApp = $("#nomeCadastroApp").val();
		   	var validadeApp = $("#validadeCadastroApp").val();	  	

		   	if (nomeApp != '' && validadeApp != '')
		   	{
		   		$("#btnCadastrarApp").removeAttr("disabled");
		   	}    else {
		   			$("#btnCadastrarApp").attr("disabled", true);  
		   		}  
	    });


	    $('#logar').click(function(e){
	    	e.preventDefault();
	    	$.ajax({
		        url : '/api/controllers/Logar.php',
		        type : 'POST',
		        data : 'login=' + $('#nome').val() + '&senha=' + $('#senha').val(),
		        success: function(data){
		            if (data == 0) {
		            	$("#nome").css("border", "solid 2px red"); 
		            	$("#senha").css("border", "solid 2px red");  
		                $('#aviso').html('Usuário ou senha inválidos!');
		            } else if(data == 1) {
		            	window.location.replace("admin.php");
		            }	
		        }
		        });   
         	  
    	});

    	$('#sair').click(function(e){ //ver aplicações registradas
	    	e.preventDefault();
		    $.ajax({
		        url : '/api/controllers/RequestsAdmin.php',
	            type : 'POST',
	            data : 'codigo=4',
		        success: function(data){	
		            window.location.replace("index.php");
		        }
		    });      
    	});

    	$('#add-app').click(function(e){ //adicionar aplicação
	    	e.preventDefault();
	    	$("#sucesso").hide();
	    	$("#cadastroUser").hide();
	    	$("#conteudo").hide();
	    	$("#sucesso2").hide();
	    	$("#cadastroApp").show();
	    	$("#add-user").css("background-color", "#E5E5E5"); 
		    $("#rec-app").css("background-color", "#E5E5E5"); 
		    $("#rec-user").css("background-color", "#E5E5E5"); 
		    $("#add-app").css("background-color", "white");
		    $("#add-app > .link-bar-app").css("color", "#A3498B");
		    $("#rec-user > .link-bar-app").css("color", "#7A7A7A");
		    $("#rec-app > .link-bar-app").css("color", "#7A7A7A");
		    $("#add-user > .link-bar-app").css("color", "#7A7A7A");
    	});
    	  	
    	
    	$('#rec-app').click(function(e){ //ver aplicações registradas
	    	e.preventDefault();
	    	$("#sucesso").hide();
	    	$("#sucesso2").hide();
	    	$("#cadastroApp").hide();
	    	$("#cadastroUser").hide();
	    	$('#conteudo').html('<img src="estilo/ajax-loader.gif"/>');
	    	$("#conteudo").show();
	    	$("#add-app").css("background-color", "#E5E5E5"); 
		    $("#rec-app").css("background-color", "white"); 
		    $("#rec-user").css("background-color", "#E5E5E5"); 
		    $("#add-user").css("background-color", "#E5E5E5");
		    $("#add-user > .link-bar-app").css("color", "#7A7A7A");
		    $("#rec-user > .link-bar-app").css("color", "#7A7A7A");
		    $("#rec-app > .link-bar-app").css("color", "#A3498B");
		    $("#add-app > .link-bar-app").css("color", "#7A7A7A");
		    $.ajax({
		        url : '/api/controllers/RequestsAdmin.php',
	            type : 'POST',
	            data : 'codigo=0',
		        success: function(data){	
		            $('#conteudo').html(data);
		        }
		    });      
    	});

    	$('#add-user').click(function(e){ //adicionar usuário para a api
	    	e.preventDefault();
	    	$("#sucesso").hide();
	    	$("#sucesso2").hide();
	    	$('#aviso').html('');
	    	$("#cadastroApp").hide();
	    	$("#cadastroUser").show();
	    	$("#conteudo").hide();
	    	$("#add-app").css("background-color", "#E5E5E5"); 
		    $("#rec-app").css("background-color", "#E5E5E5"); 
		    $("#rec-user").css("background-color", "#E5E5E5"); 
		    $("#add-user").css("background-color", "white");
		    $("#add-user > .link-bar-app").css("color", "#A3498B");
		    $("#rec-user > .link-bar-app").css("color", "#7A7A7A");
		    $("#rec-app > .link-bar-app").css("color", "#7A7A7A");
		    $("#add-app > .link-bar-app").css("color", "#7A7A7A");  
    	});

    	$('#rec-user').click(function(e){ //ver usuários da api
	    	e.preventDefault();
	    	$("#sucesso").hide();
	    	$("#sucesso2").hide();
	    	$("#cadastroUser").hide();
	    	$("#cadastroApp").hide();
	    	$('#conteudo').html('<img src="estilo/ajax-loader.gif"/>');
	    	$("#conteudo").show();
	    	$("#add-app").css("background-color", "#E5E5E5"); 
		    $("#rec-app").css("background-color", "#E5E5E5"); 
		    $("#add-user").css("background-color", "#E5E5E5"); 
		    $("#rec-user").css("background-color", "white");
		    $("#add-user > .link-bar-app").css("color", "#7A7A7A");
		    $("#rec-user > .link-bar-app").css("color", "#A3498B");
		    $("#rec-app > .link-bar-app").css("color", "#7A7A7A");
		    $("#add-app > .link-bar-app").css("color", "#7A7A7A");
		    $.ajax({
		        url : '/api/controllers/RequestsAdmin.php',
		        type : 'POST',
		        data : 'codigo=1',
		        success: function(data){	
		            $('#conteudo').html(data);
		        }
		    });     
    	});



    	$('#btnCadastrarUser').click(function(e){
    		e.preventDefault();
	    	var name = $("#nomeCadastroUser").val();
	    	var pass = $("#senhaCadastroUser").val();
	    	$('#aviso').html('<img src="estilo/ajax-loader.gif"/>');	
		    $.ajax({
		        url : '/api/controllers/RequestsAdmin.php',
		        type : 'POST',
		        data : 'codigo=3&nomeuser='+name+'&senhauser='+pass,
		        success: function(data){	
		            if (data == false) {
		            	$('#aviso').html('Este usuário já existe');
		            	$("#sucesso").hide();
		            } else {
		            	$('#aviso').html('');
		            	$("#cadastroUser").hide();
		            	$("#add-user > .link-bar-app").css("color", "#7A7A7A");
		            	$("#add-user").css("background-color", "#E5E5E5"); 
		            	$("#sucesso").show();
		            }
		        }
		    });     
    	});

    	$('#btnCadastrarApp').click(function(e){
    		e.preventDefault();
    		$("#sucesso2").hide();
	    	var nameApp = $("#nomeCadastroApp").val();
	    	var validade = $("#validadeCadastroApp").val();
	    	$('#aviso2').html('<img src="estilo/ajax-loader.gif"/>');	
		    $.ajax({
		        url : '/api/controllers/RequestsAdmin.php',
		        type : 'POST',
		        data : 'codigo=2&nomeApp='+nameApp+'&validade='+validade,
		        success: function(data){	
		            if (data == true) {
		            	$('#aviso2').html('');
		            	$("#cadastroApp").hide();
		            	$("#add-app > .link-bar-app").css("color", "#7A7A7A");
		            	$("#add-app").css("background-color", "#E5E5E5"); 
		            	$('#sucesso2').html('<h1>Aplicação registrada com sucesso</h1>');
		            	$("#sucesso2").show();
		            } else if (data == false) {
		            	$('#aviso2').html('Essa aplicação já foi registrada');
		            	$("#sucesso2").hide();
		            } else {
		            	$('#aviso2').html('Data de validade inválida');
		            	$("#sucesso2").hide();
		            }
		        }
		    });     
    	});

});
