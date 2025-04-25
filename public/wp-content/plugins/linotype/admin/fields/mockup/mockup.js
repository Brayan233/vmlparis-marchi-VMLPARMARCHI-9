 (function($) {

$.fn.wp_field_mockup = function(){
	
 	$(this).each(function(){
		
		//var
 		var $MOCKUP = $(this);
		var $MOCKUP_VALUE = $MOCKUP.find('.wp-field-value');

		var $MOCKUP_POINTS = $MOCKUP.find('.perspective-point');

		var $MOCKUP_DRAG = $MOCKUP.find('.source-drag');

		var screenCanvasElement = $MOCKUP.find('.screenCanvas');
		var source = $MOCKUP.find('.source');
		var $message = $MOCKUP.find('.wp-field-mockup-message');

		var qualityOptions = [{anisotropicFiltering:true},{ mipMapping:true},{linearFiltering:true}];
		var srcPoints;
		var screenImgElement;
		var screenTextureSize;
		var anisoExt
		var glOpts;
		var gl;
		var glResources;
		var jsondata;
		var controlPoints;

		//hide default source
		source.css('display','none');

		//
		if ( source.attr('src') != undefined ) {

			init();
			
		} else {

			 add_message("Load an image source first.");

		}


		function init(){

			//get data
			jsondata = $MOCKUP_VALUE.val();

			//parse controle points
			controlPoints = $.parseJSON( jsondata );

			//if no data
			if ( controlPoints == null ) {

				var sourceWidth = source.attr('width');
				var sourceHeight = source.attr('height');

				controlPoints = $.parseJSON('[{"x": 0, "y": 0 },{ "x": ' + sourceWidth + ', "y": 0 },{ "x": 0, "y": ' + sourceHeight + ' },{ "x": ' + sourceWidth + ', "y": ' + sourceHeight + ' }]');
				
				update_output();

			}

			//init handle
			$.each( controlPoints, function( index, value ) {
				$MOCKUP.find('.point-' + index ).css('left',value['x']);
				$MOCKUP.find('.point-' + index ).css('top',value['y']);
			});
			
			//init drag
			function centerdrag(){
				$MOCKUP_DRAG.css('left', controlPoints[ 0 ]['x'] ).css('top', controlPoints[ 0 ]['y'] );
				$centerdragleft = ( ( controlPoints[ 3 ]['x'] - controlPoints[ 0 ]['x'] ) /2 ) - 10;
				$centerdragtop = ( ( controlPoints[ 2 ]['y'] - controlPoints[ 0 ]['y'] ) /2 ) - 10;
				$MOCKUP_DRAG.find('span').css('left', $centerdragleft ).css('top', $centerdragtop );
			}
			centerdrag();
			

			//make it draggable
			$MOCKUP_DRAG.draggable({

				drag: function( event, ui ) {
					
					var leftpos = ui.position.left;
					var toppos = ui.position.top;

					//console.log( 'leftpos: ' + leftpos + 'toppos: ' + toppos );
					$MOCKUP.find('.point-' + '0' ).css('left', leftpos );
					$MOCKUP.find('.point-' + '0' ).css('top', toppos );
					
					$MOCKUP.find('.point-' + '1' ).css('left', ( controlPoints[ 1 ]['x'] - controlPoints[ 0 ]['x'] ) + leftpos  );
					$MOCKUP.find('.point-' + '1' ).css('top', ( controlPoints[ 1 ]['y'] - controlPoints[ 0 ]['y'] ) + toppos  );
					
					$MOCKUP.find('.point-' + '2' ).css('left', ( controlPoints[ 2 ]['x'] - controlPoints[ 0 ]['x'] ) + leftpos  );
					$MOCKUP.find('.point-' + '2' ).css('top', ( controlPoints[ 2 ]['y'] - controlPoints[ 0 ]['y'] ) + toppos  );
					
					$MOCKUP.find('.point-' + '3' ).css('left', ( controlPoints[ 3 ]['x'] - controlPoints[ 0 ]['x'] ) + leftpos  );
					$MOCKUP.find('.point-' + '3' ).css('top', ( controlPoints[ 3 ]['y'] - controlPoints[ 0 ]['y'] ) + toppos  );
					
					//bim = controlPointsOrigin;
					controlPoints[ 0 ]['x'] = parseInt( $MOCKUP.find('.point-' + '0' ).css('left'), 10 );
					controlPoints[ 0 ]['y'] = parseInt( $MOCKUP.find('.point-' + '0' ).css('top'), 10 );

					controlPoints[ 1 ]['x'] = parseInt( $MOCKUP.find('.point-' + '1' ).css('left'), 10 );
					controlPoints[ 1 ]['y'] = parseInt( $MOCKUP.find('.point-' + '1' ).css('top'), 10 );

					controlPoints[ 2 ]['x'] = parseInt( $MOCKUP.find('.point-' + '2' ).css('left'), 10 );
					controlPoints[ 2 ]['y'] = parseInt( $MOCKUP.find('.point-' + '2' ).css('top'), 10 );

					controlPoints[ 3 ]['x'] = parseInt( $MOCKUP.find('.point-' + '3' ).css('left'), 10 );
					controlPoints[ 3 ]['y'] = parseInt( $MOCKUP.find('.point-' + '3' ).css('top'), 10 );

					//console.log(bim);
					redrawImg();
					
				},

				stop: function( event, ui ) {
					
					update_output();

				}

		    });

			//make it draggable
			$MOCKUP_POINTS.draggable({

				//containment: "parent",
				
				drag: function( event, ui ) {
					
					var index = $(ui.helper).attr('data-point-index');
					
					controlPoints[ index ]['x'] = ui.position.left;
					controlPoints[ index ]['y'] = ui.position.top;

					centerdrag();
					
					redrawImg();

				},

				stop: function( event, ui ) {
					
					update_output();

				}

		    });

			//init
			setup();

		    screenImgElement = new Image();
			screenImgElement.crossOrigin = '';
			screenImgElement.onload = loadScreenTexture;
			screenImgElement.src = source.attr('src');
				
		}

		function setup() {

			glOpts = { antialias: true, depth: false, preserveDrawingBuffer: true };
			gl =
			    screenCanvasElement[0].getContext('webgl', glOpts) ||
			    screenCanvasElement[0].getContext('experimental-webgl', glOpts);
			if(!gl) {
			    add_message("Your browser doesn't seem to support WebGL.");
			}
			anisoExt =
			    gl.getExtension('EXT_texture_filter_anisotropic') ||
			    gl.getExtension('MOZ_EXT_texture_filter_anisotropic') ||
			    gl.getExtension('WEBKIT_EXT_texture_filter_anisotropic');
			if(!anisoExt) {
			    qualityOptions.anisotropicFiltering = false;
			    add_message("Your browser doesn't support anisotropic filtering." + " Ordinary MIP mapping will be used.");
			}

			// Setup the GL context compiling the shader programs and returning the
			// attribute and uniform locations.
			glResources = setupGlContext();
	
		}

		function setupGlContext() {

		    // Store return values here
		    var rv = {};
		    
		    // Vertex shader:
		    var vertShaderSource = [
		        'attribute vec2 aVertCoord;',
		        'uniform mat4 uTransformMatrix;',
		        'varying vec2 vTextureCoord;',
		        'void main(void) {',
		        '    vTextureCoord = aVertCoord;',
		        '    gl_Position = uTransformMatrix * vec4(aVertCoord, 0.0, 1.0);',
		        '}'
		    ].join('\n');

		    var vertexShader = gl.createShader(gl.VERTEX_SHADER);
		    gl.shaderSource(vertexShader, vertShaderSource);
		    gl.compileShader(vertexShader);

		    if (!gl.getShaderParameter(vertexShader, gl.COMPILE_STATUS)) {
		        add_message('Failed to compile vertex shader:' +
		              gl.getShaderInfoLog(vertexShader));
		    }
		       
		    // Fragment shader:
		    var fragShaderSource = [
		        'precision mediump float;',
		        'varying vec2 vTextureCoord;',
		        'uniform sampler2D uSampler;',
		        'void main(void)  {',
		        '    gl_FragColor = texture2D(uSampler, vTextureCoord);',
		        '}'
		    ].join('\n');

		    var fragmentShader = gl.createShader(gl.FRAGMENT_SHADER);
		    gl.shaderSource(fragmentShader, fragShaderSource);
		    gl.compileShader(fragmentShader);

		    if (!gl.getShaderParameter(fragmentShader, gl.COMPILE_STATUS)) {
		        add_message('Failed to compile fragment shader:' +
		              gl.getShaderInfoLog(fragmentShader));
		    }
		    
		    // Compile the program
		    rv.shaderProgram = gl.createProgram();
		    gl.attachShader(rv.shaderProgram, vertexShader);
		    gl.attachShader(rv.shaderProgram, fragmentShader);
		    gl.linkProgram(rv.shaderProgram);

		    if (!gl.getProgramParameter(rv.shaderProgram, gl.LINK_STATUS)) {
		        add_message('Shader linking failed.');
		    }
		        
		    // Create a buffer to hold the vertices
		    rv.vertexBuffer = gl.createBuffer();

		    // Find and set up the uniforms and attributes        
		    gl.useProgram(rv.shaderProgram);
		    rv.vertAttrib = gl.getAttribLocation(rv.shaderProgram, 'aVertCoord');
		        
		    rv.transMatUniform = gl.getUniformLocation(rv.shaderProgram, 'uTransformMatrix');
		    rv.samplerUniform = gl.getUniformLocation(rv.shaderProgram, 'uSampler');
		        
		    // Create a texture to use for the screen image
		    rv.screenTexture = gl.createTexture();
		    
		    return rv;

		}

		function loadScreenTexture() {

		    if(!gl || !glResources) { return; }
		    
		    var image = screenImgElement;
		    var extent = { w: image.naturalWidth, h: image.naturalHeight };
		    
		    gl.bindTexture(gl.TEXTURE_2D, glResources.screenTexture);
		    
		    // Scale up the texture to the next highest power of two dimensions.
		    var canvas = document.createElement("canvas");
		    canvas.width = nextHighestPowerOfTwo(extent.w);
		    canvas.height = nextHighestPowerOfTwo(extent.h);
		    
		    var ctx = canvas.getContext("2d");
		    ctx.drawImage(image, 0, 0, image.width, image.height);
		    
		    gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, canvas);
		    
		    if(qualityOptions.linearFiltering) {
		        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER,
		                         qualityOptions.mipMapping
		                             ? gl.LINEAR_MIPMAP_LINEAR
		                             : gl.LINEAR);
		        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
		    } else {
		        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, 
		                         qualityOptions.mipMapping
		                             ? gl.NEAREST_MIPMAP_NEAREST
		                             : gl.LINEAR);
		        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.NEAREST);
		    }
		    
		    if(anisoExt) {
		        // turn the anisotropy knob all the way to 11 (or down to 1 if it is
		        // switched off).
		        var maxAniso = qualityOptions.anisotropicFiltering ?
		            gl.getParameter(anisoExt.MAX_TEXTURE_MAX_ANISOTROPY_EXT) : 1;
		        gl.texParameterf(gl.TEXTURE_2D, anisoExt.TEXTURE_MAX_ANISOTROPY_EXT, maxAniso);
		    }
		    
		    if(qualityOptions.mipMapping) {
		        gl.generateMipmap(gl.TEXTURE_2D);
		    }
		    
		    gl.bindTexture(gl.TEXTURE_2D, null);
		    
		    // Record normalised height and width.
		    var w = extent.w / canvas.width, h = extent.h / canvas.height;
		    
		    srcPoints = [
		        { x: 0, y: 0 }, // top-left
		        { x: w, y: 0 }, // top-right
		        { x: 0, y: h }, // bottom-left
		        { x: w, y: h }  // bottom-right
		    ];
		        
		    // setup the vertex buffer with the source points
		    var vertices = [];
		    for(var i=0; i<srcPoints.length; i++) {
		        vertices.push(srcPoints[i].x);
		        vertices.push(srcPoints[i].y);
		    }
		    
		    gl.bindBuffer(gl.ARRAY_BUFFER, glResources.vertexBuffer);
		    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertices), gl.STATIC_DRAW);
		    
		    // Redraw the image
		    redrawImg();

		}

		function isPowerOfTwo(x) { 

			return (x & (x - 1)) == 0; 

		}
		 
		function nextHighestPowerOfTwo(x) {
		    
		    --x;
		    for (var i = 1; i < 32; i <<= 1) {
		        x = x | x >> i;
		    }
		    return x + 1;

		}

		function redrawImg() {

		    if(!gl || !glResources || !srcPoints) { return; }
		    
		    var vpW = screenCanvasElement.width();
		    var vpH = screenCanvasElement.height();
		    
		    // Find where the control points are in 'window coordinates'. I.e.
		    // where thecanvas covers [-1,1] x [-1,1]. Note that we have to flip
		    // the y-coord.
		    var dstPoints = [];
		    for(var i=0; i<controlPoints.length; i++) {
		        dstPoints.push({
		            x: (2 * controlPoints[i].x / vpW) - 1,
		            y: -(2 * controlPoints[i].y / vpH) + 1
		        });
		    }
		    
		    // Get the transform
		    var v = transformationFromQuadCorners(srcPoints, dstPoints);
		    
		    // set background to full transparency
		    gl.clearColor(0,0,0,0);
		    gl.viewport(0, 0, vpW, vpH);
		    gl.clear(gl.COLOR_BUFFER_BIT | gl.DEPTH_BUFFER_BIT);

		    gl.useProgram(glResources.shaderProgram);

		    // draw the triangles
		    gl.bindBuffer(gl.ARRAY_BUFFER, glResources.vertexBuffer);
		    gl.enableVertexAttribArray(glResources.vertAttrib);
		    gl.vertexAttribPointer(glResources.vertAttrib, 2, gl.FLOAT, false, 0, 0);
		    
		    gl.uniformMatrix4fv(
		        glResources.transMatUniform,
		        false, [
		            v[0], v[1],    0, v[2],
		            v[3], v[4],    0, v[5],
		               0,    0,    0,    0,
		            v[6], v[7],    0,    1
		        ]);
		        
		    gl.activeTexture(gl.TEXTURE0);
		    gl.bindTexture(gl.TEXTURE_2D, glResources.screenTexture);
		    gl.uniform1i(glResources.samplerUniform, 0);

		    gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);   

		}

		function transformationFromQuadCorners(before, after) {
		 
		    var b = numeric.transpose([[
		        after[0].x, after[0].y,
		        after[1].x, after[1].y,
		        after[2].x, after[2].y,
		        after[3].x, after[3].y ]]);
		    
		    var A = [];
		    for(var i=0; i<before.length; i++) {
		        A.push([
		            before[i].x, 0, -after[i].x*before[i].x,
		            before[i].y, 0, -after[i].x*before[i].y, 1, 0]);
		        A.push([
		            0, before[i].x, -after[i].y*before[i].x,
		            0, before[i].y, -after[i].y*before[i].y, 0, 1]);
		    }

		    return numeric.transpose(numeric.dot(numeric.inv(A), b))[0];

		}
		
		function add_message( message ) {

			$message.append( '<p>' + message + '</p>' );
		
		}

		function update_output(){

			var output = JSON.stringify( controlPoints );

		    $MOCKUP_VALUE.val( output );

		}

	});

}

$(document).ready(function(){

	$('body').find('.wp-field-mockup').wp_field_mockup();
	
});

}(jQuery));