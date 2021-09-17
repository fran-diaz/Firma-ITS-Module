var firma__canvas;
var firma__signaturePad;

function resizeCanvas() {
    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
    firma__canvas.width = firma__canvas.offsetWidth * ratio;
    firma__canvas.height = firma__canvas.offsetHeight * ratio;
    firma__canvas.getContext("2d").scale(ratio, ratio);
    firma__signaturePad.clear(); // otherwise isEmpty() might return incorrect value
}

window.addEventListener("resize", resizeCanvas);

function download(dataURL, filename) {
  if (navigator.userAgent.indexOf("Safari") > -1 && navigator.userAgent.indexOf("Chrome") === -1) {
    window.open(dataURL);
  } else {
    var blob = dataURLToBlob(dataURL);
    var url = window.URL.createObjectURL(blob);

    var a = document.createElement("a");
    a.style = "display: none";
    a.href = url;
    a.download = filename;

    document.body.appendChild(a);
    a.click();

    window.URL.revokeObjectURL(url);
  }
}

// One could simply use Canvas#toBlob method instead, but it's just to show
// that it can be done using result of SignaturePad#toDataURL.
function dataURLToBlob(dataURL) {
	// Code taken from https://github.com/ebidel/filer.js
	var parts = dataURL.split(';base64,');
	var contentType = parts[0].split(":")[1];
	var raw = window.atob(parts[1]);
	var rawLength = raw.length;
	var uInt8Array = new Uint8Array(rawLength);

	for (var i = 0; i < rawLength; ++i) {
	uInt8Array[i] = raw.charCodeAt(i);
	}

	return new Blob([uInt8Array], { type: contentType });
}



$(document).on('click','.firma__btn-open', function () {
	var that = $(this);
	$.loadScript( "https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js", false, function(){
		var d = that.attr('data-d');
		var type = that.attr('data-type');

		$.ajax({
	        type: 'POST',
	        url: '/ajax',
	        data: 'DEBUG=0&action=firma/modal&d='+d+'&type='+type, 
	        success: function(data){
	            var id = $(data).filter('.modal').attr('id');
	            $('body').append(data);
	            $('#'+id).modal();

	            firma__canvas = document.querySelector("canvas");
				firma__signaturePad = new SignaturePad(firma__canvas,{
					// It's Necessary to use an opaque color when saving image as JPEG;
				  	// this option can be omitted if only saving as PNG or SVG
				  	//backgroundColor: 'rgb(255, 255, 255)'
				});
	        }
	    });

		
	} );
})

$(document).on('click','.firma__btn-clear',function(){
	firma__signaturePad.clear();
});

$(document).on('click','.firma__btn-download',function(){
	if (firma__signaturePad.isEmpty()) {
    	alert("Por favor, dibuja una firma primero.");
  	} else {
    	var dataURL = firma__signaturePad.toDataURL();
    	download(dataURL, "firma.png");
  	}
});

$(document).on('click','.firma__btn-save',function(){
	if (firma__signaturePad.isEmpty()) {
    	alert("Por favor, dibuja una firma primero.");
  	} else {
    	var dataURL = firma__signaturePad.toDataURL();
    	var modal = $(this).closest('.modal').attr('id');
    	var d = $('[data-target="#'+modal+'"]').attr('data-d'); //.closest('.component')
    	var type = $(this).attr('data-type');
    	
    	//download(dataURL, "firma.png");
    	$.ajax({
	        type: 'POST',
	        url: '/ajax',
	        data: 'DEBUG=0&action=firma/save-file&dataURL='+dataURL+'&d='+d+'&type='+type,
	        success: function(data){
	        	$('.component').each(function(i,el){
	        			if( $(el).attr('data-component-type') === 'files' ){
	        					reload_component( $(el).attr('data-component') );
	        			}
	        			reload_component( $('[data-target="#'+modal+'"]').closest('.component').attr('data-component') )
	        	});
	           
	          $('#'+modal).find('button.close').trigger('click');
	        }
	    });
  	}
});