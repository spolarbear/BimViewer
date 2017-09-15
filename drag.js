/**
 * author levi
 * url http://levi.cg.am
 */
$(function() {
	$(document).mousemove(function(e) {
		if (!!this.move) {
			var posix = !document.move_target ? {'x': 0, 'y': 0} : document.move_target.posix,
				callback = document.call_down || function() {
					$(this.move_target).css({
						'top': e.pageY - posix.y,
						'left': e.pageX - posix.x
					});
				};

			callback.call(this, e, posix);
		}
	}).mouseup(function(e) {
		if (!!this.move) {
			var callback = document.call_up || function(){};
			callback.call(this, e);
			$.extend(this, {
				'move': false,
				'move_target': null,
				'call_down': false,
				'call_up': false
			});
		}
	});

	var $box = $('#box').mousedown(function(e) {
	    var offset = $(this).offset();
	    
	    this.posix = {'x': e.pageX - offset.left, 'y': e.pageY - offset.top};
	    $.extend(document, {'move': true, 'move_target': this});
	}).on('mousedown', '#coor', function(e) {
	    var posix = {
	            'w': $box.width(), 
	            'h': $box.height(), 
	            'x': e.pageX, 
	            'y': e.pageY
	        };
	    
	    $.extend(document, {'move': true, 'call_down': function(e) {
	        $box.css({
	            'width': Math.max(30, e.pageX - posix.x + posix.w),
	            'height': Math.max(30, e.pageY - posix.y + posix.h)
	        });
	    }});
	    return false;
	});
});



$(document).ready( function(){  
        $("#printPic").on("click", function(event) {  

        		showParentPanel();
                event.preventDefault();  
                html2canvas(document.body, {  
                allowTaint: true,  
                taintTest: false,  
                onrendered: function(canvas) {  
                    canvas.id = "mycanvas";  
                    //document.body.appendChild(canvas);  
                    var dataUrl = canvas.toDataURL();  
                    var newImg = document.createElement("img");  
                    $(newImg).attr({width:"80px"});
                    newImg.src =  dataUrl;  
                    $(parent.window.document.getElementById("picTableTd")).append(newImg);  
                    
                }  

            });  
        });   
       
});  


function showParentPanel(){
                if(
                	$(parent.window.document.getElementById("toShowPanel")).css("display")=="none"
                  )
                {
                	$(parent.window.document.getElementById("toggleThePanelClick")).click();
                }
        		console.log($(parent.window.document.getElementById("toShowPanel")).css("display"));
}