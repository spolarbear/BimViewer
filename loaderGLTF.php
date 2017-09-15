<!DOCTYPE html>
<html lang="en">
	<head>
		<title>three.js webgl - FBX loader</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<link href="../../../../../gdad/basic/web/assets/e8ff9cab/css/bootstrap.css" rel="stylesheet">
		<style>
			body {
				font-family: Monospace;
				background-color: white;
				color: #fff;
				margin: 0px;
				overflow: hidden;
			}
			#info {
				color: black;
				position: absolute;
				bottom:  0px;
				width: 100%;
				text-align: left;
				z-index: 100;
				display:block;
				background-color: #ddd;
				opacity: 0.5;
				padding: 1px 10px;
				font-size: 12px;
			}
			#situation {
				color: black;
				position: absolute;
				bottom:  100px;
				width: 100%;
				text-align: left;
				z-index: 100;
				display:block;
				background-color: #ddd;
				opacity: 0.5;
				padding: 3px 10px;
			}
			#info a, .button {
			 color: black; font-weight: bold; text-decoration: underline; cursor: pointer ;
			}

			#editPanel{
				color: black;
				position: absolute;
				top: 0px;
				left:0px;
				width: 180px;
				text-align: left;
				z-index: 100;
				display:block;
				opacity: 0.9;
			    background-color: #fff;
			    border: 1px solid transparent;
				border-color: #ddd;
			    border-radius: 4px;
			    /*<?=(($_GET["name"]=="pcbim"||$_GET["name"]=="opbim")&&$_GET["id"]==0?"":"display: none")?>*/
			    display: block;
			}
			#elementlist{
				height: 200px;
				font-size: 12px;
			}


			#box { 
				width: 200px; height: 100px; cursor: move; position: absolute; top: 200px; left: 300px; 
				border: 3px solid red;
				display: none;
			}

			#coor { 
				width: 10px; height: 10px; overflow: hidden; cursor: se-resize; position: absolute; right: 0; bottom: 0; background-color: #09C; 
			}


		</style>
	</head>

	<body>
		<div id="info">
			装配式建筑协同管理系统 © GDAD
		</div>

		<div id="box">
		    <div id="coor"></div>
		</div>

		<div class="panel panel-default" id="editPanel">
			<!-- Default panel contents -->
			<div class="panel-heading">编辑器</div>
			<div class="panel-body">
				<p>
					<button type="button" class="btn btn-xs btn-primary" onclick="move(-0.1,0,1,1)">底图：G305拆分平面</button>
				</p>
			</div>
			<!-- List group -->
			<ul class="list-group">
				<li class="list-group-item">
					<p><span class="glyphicon glyphicon-search" aria-hidden="true"></span> 构件列表</p>
					<select multiple class="form-control" id="elementlist">
					</select>
				</li>
				<li class="list-group-item">
					<p><span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span> 添加条目</p>
					<div class="btn-group btn-group-xs" role="group" aria-label="motifily">
						<button type="button" class="btn btn-primary" onclick="$('#box').show();" >云线</button>
						<button type="button" class="btn btn-default" id="printPic" >截图</button>
						<button type="button" class="btn btn-default" >提交</button>
					</div>		
				</li>
				<li class="list-group-item">
					<p><span class="glyphicon glyphicon-move" aria-hidden="true"></span> 底图定位</p>
					<div class="btn-group btn-group-xs " role="group" aria-label="editPanel">
						<button type="button" role="button" class="btn btn-default" onclick="move(0,0,1.01,1)">+</button>
						<button type="button" role="button" class="btn btn-default" onclick="move(0,0,0.99,1)">-</button>
						<button type="button" role="button" class="btn btn-default" onclick="move(-0.1,0,1,1)">←</button>
						<button type="button" role="button" class="btn btn-default" onclick="move(0.1,0,1,1)">→</button>
						<button type="button" role="button" class="btn btn-default" onclick="move(0,0.1,1,1)">↑</button>
						<button type="button" role="button" class="btn btn-default" onclick="move(0,-0.1,1,1)">↓</button>
					</div>	
				</li>
			</ul>
		</div>

		<div id="situation">
		模型加载中...<?=$_REQUEST["id"]?>
		</div>

		<!-- <div id="draw" style="width: 100%;height:100%;border: 0px;padding: 0px;margin: 0px;"></div> -->

		<script src="../build/three.js"></script>

		<script src="js/controls/OrbitControls.js"></script>
		<script src="js/controls/TrackballControls.js"></script>
		<script src="js/controls/TransformControls.js"></script>
		<script src="js/controls/DragControls.js"></script>


		<script src="js/curves/NURBSCurve.js"></script>
		<script src="js/curves/NURBSUtils.js"></script>
		<script src="js/loaders/FBXLoader.js"></script>

		<script src="js/Detector.js"></script>
		<script src="js/libs/stats.min.js"></script>
		<script src="js/libs/inflate.min.js"></script>

		<script src="gbk2utf.js"></script>
		
		<script src="js/loaders/GLTFLoader.js"></script>

		<script src="../../../../../gdad/basic/web/assets/5066aac/jquery.js"></script>
		<script type="text/javascript" src="http://html2canvas.hertzen.com/build/html2canvas.js"></script>  

		<script src="drag.js"></script>

		<script>


			if ( ! Detector.webgl ) Detector.addGetWebGLMessage();
////////////definition////////////////////////////////////
			var container, stats, controls;
			var camera, scene, renderer, light;
			var lightPos,lightTarget;

			var clock = new THREE.Clock();

			var mixers = [];

			var mdCenter,mdBorder;

			var skyBox;

			var splineHelperObjects = [], splineOutline;


			var previousSelection,previousSelectionMaterial;


			var splineHelperObjects = [], splineOutline;
			var splinePointsLength = 1;
			var positions = [];
			var options;

			var controlBoxGeometry = new THREE.BoxGeometry( 2, 2, 2 );
			var transformControl;

			var skyBoxGeometry;

			var ARC_SEGMENTS = 200;
			var splineMesh;
			var boxX,boxY;
			var splines = {};
			var localPlane = new THREE.Plane( new THREE.Vector3( 0, 1, 0 ), 0 );
			<?php if($_GET["id"]==0):?>
			localPlane.visible = true;
			<?php endif;?>
			//var globalPlane = new THREE.Plane( new THREE.Vector3( - 1, 0, 0 ), 0.1 );


			var params = {
				uniform: true,
				tension: 0.5,
				centripetal: true,
				chordal: true,
				addPoint: addPoint,
				removePoint: removePoint,
				exportSpline: exportSpline
			};
			var hiding;

/////////////////////////////定义材料/////////////////////////////////////////////////////////
			var materialLine = new THREE.LineBasicMaterial({
		        color: 0x000000,
		        clippingPlanes: [ localPlane ],
				clipShadows: true
		    });

	        var materialWall = new THREE.MeshPhongMaterial({
		        color: 0x808080,
		        opacity: 0.8,
		        side:THREE.DoubleSide,
		        wireframe:false,
		        //fog:true,
				clippingPlanes: [ localPlane ],
				clipShadows: true
		        //transparent:true,
		        //depthTest:false,
		        //emissive: 0x000000
	        });
	        //var geometry = new THREE.TorusKnotBufferGeometry( 0.4, 0.08, 95, 20 );
	        var materialStair = new THREE.MeshLambertMaterial({
		        color: 0x5CACEE,
		        opacity: 0.8,
		        side:THREE.DoubleSide,
		        wireframe:false,
		        transparent:true,
				clippingPlanes: [ localPlane ],
				clipShadows: true
		        //depthTest:false,
		        //emissive: 0x000000
	        });
	        var materialSlab = new THREE.MeshLambertMaterial({
		        color: 0x008B8B,
		        opacity: 0.1,
		        side:THREE.DoubleSide,
		        wireframe:false,
		        transparent:true,
				clippingPlanes: [ localPlane ],
				clipShadows: true
		        //depthTest:false,
		        //emissive: 0x000000
	        });

	        var materialBeam = new THREE.MeshLambertMaterial({
		        color: 0xB8860B,
		        opacity: 0.8,
		        side:THREE.DoubleSide,
		        wireframe:false,
		        transparent:true,
				//clippingPlanes: [ localPlane ],
				//clipShadows: true
		        //depthTest:false,
		        //emissive: 0x000000
	        });

	        var materialHRB = new THREE.MeshLambertMaterial({
		        color: 0xFF3030,
		        opacity: 0.8,
		        side:THREE.DoubleSide,
		        wireframe:false,
		        transparent:true,
				clippingPlanes: [ localPlane ],
				clipShadows: true
		        //depthTest:false,
		        //emissive: 0x000000
	        });
	        var materialSELECTED = new THREE.MeshLambertMaterial({
		        color: 0xFF7F24,
		        opacity: 0.8,
		        side:THREE.DoubleSide,
		        wireframe:false,
				//clippingPlanes: [ localPlane ],
				//clipShadows: true
		        //transparent:true,
		        //depthTest:false,
		        //emissive: 0x000000
	        });
///////////////////////////////////////////////////////////////////////////////////////////
			init();
			animate();








/////////////functions////////////////////
			function init() {

				container = document.getElementById("draw");

				infocontainer = document.getElementById("info");
				container = document.createElement( 'div' );
				document.body.appendChild( container );

				camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 0.1, 2000000 );
				scene = new THREE.Scene();
				// stats
				stats = new Stats();
				//container.appendChild( stats.dom );




				// model
				var manager = new THREE.LoadingManager();
				manager.onProgress = function( item, loaded, total ) {

					console.log( item, loaded, total );

				};

				var onProgress = function( xhr ) {

					if ( xhr.lengthComputable ) {

						var percentComplete = xhr.loaded / xhr.total * 100;
						console.log( Math.round( percentComplete, 2 ) + '% downloaded' );
						if(percentComplete==100){
							document.getElementById("situation").style.display="none";
						}else{
							document.getElementById("situation").innerHTML="模型加载中... "+Math.round(percentComplete) + "%";
						}
					}

				};

				var onError = function( xhr ) {

					console.error( xhr );

				};




				var loader = new THREE.GLTFLoader();




/*				var url = "models/fbx/WQB2.gltf";
				loader.load( url, function(data) {

					gltf = data;
					console.log(gltf);
					var object = gltf.scene;

					var gridHelper = new THREE.GridHelper( 500, 100, 0xD1D1D1, 0xD1D1D1 );
					//scene.add( gridHelper );

					mdBorder = new THREE.BoxHelper( object,0x000000 );
               		mdCenter = (mdBorder.geometry.boundingSphere.center); 
					controls.target.set( mdCenter.x,mdCenter.y,mdCenter.z);
					camera.position.set( mdCenter.x + 50  , mdCenter.y +  50 , mdCenter.z + 50 ); 
					lightPos = camera.position; 
					lightTarget = controls.target;
					controls.update();

					object.traverse(function(child) {
				        if ( child instanceof THREE.Mesh) {
				        	child.material = materialBeam;
				        	scene.add( child );
					    }
				    });
				});*/

				var i;
				for(i = 0; i <=20  ; i++){
					var url = "models/fbx/WQB2/scene ("+i+").gltf";
					loader.load( url, function(data) {

						gltf = data;
						//console.log(gltf);
						var object = gltf.scene;
						if(1){
							mdBorder = new THREE.BoxHelper( object,0x000000 );
		               		mdCenter = (mdBorder.geometry.boundingSphere.center); 
							controls.target.set( mdCenter.x,mdCenter.y,mdCenter.z);
							camera.position.set( mdCenter.x + 50  , mdCenter.y +  50 , mdCenter.z + 50 ); 
							lightPos = camera.position; 
							lightTarget = controls.target;
							controls.update();
						}
						object.traverse(function(child) {
					        if ( child instanceof THREE.Mesh) {
					        	//child.material = materialBeam;
					        	scene.add( child );
						    }
					    });
					    document.getElementById("situation").innerHTML="模型加载中... "+Math.round(i) + " # ";
					});
				}




/////////////////////LOADER  完成///////////////////

			    //声明渲染器对象：WebGLRenderer  
			    renderer=new THREE.WebGLRenderer({  
			        antialias:true,       //是否开启反锯齿  
			        precision:"highp",    //着色精度选择  
			        alpha:true,           //是否可以设置背景色透明  
			        premultipliedAlpha:false,  
			        stencil:false,  
			        preserveDrawingBuffer:true, //是否保存绘图缓冲  
			        maxLights:100           //maxLights:最大灯光数  
			    }); 


				renderer.setPixelRatio( window.devicePixelRatio );
				renderer.setSize( window.innerWidth, window.innerHeight );
				container.appendChild( renderer.domElement );
				renderer.localClippingEnabled = true;

				// controls, camera
				<?php if($_GET["name"]!="pcbim"): ?>
				controls = new THREE.OrbitControls( camera, renderer.domElement );
				controls.target.set( 0,0,0 );//初始化  后期加载模型后根据模型的box重新设置
				<?php else: ?>
				//改为trackball controls

				controls = new THREE.TrackballControls( camera, renderer.domElement );

				controls.rotateSpeed = 2;
				controls.zoomSpeed = 1.5;
				controls.panSpeed = 1.5;

				controls.noZoom = false;
				controls.noPan = false;

				controls.staticMoving = true;
				controls.dynamicDampingFactor = 0.3;
				//controls.up0 =  new THREE.Vector3( 1, -1, 0 );
				// /controls.rotation =  THREE.Math.degToRad( 90 );

				controls.keys = [ 65, 83, 68 ];
				<?php endif; ?>

				controls.addEventListener( 'change', render );




				//controls.addEventListener( 'change', render );

				controls.addEventListener( 'start', function() {
					cancelHideTransorm();
				} );
				controls.addEventListener( 'end', function() {
					delayHideTransform();
				} );
				transformControl = new THREE.TransformControls( camera, renderer.domElement );
				transformControl.size = 0.5;
				transformControl.axis = "XY";

				transformControl.addEventListener( 'change', render );
				scene.add( transformControl );
				// Hiding transform situation is a little in a mess :()
				transformControl.addEventListener( 'change', function( e ) {
					cancelHideTransorm();
				} );
				transformControl.addEventListener( 'mouseDown', function( e ) {
					cancelHideTransorm();
				} );
				transformControl.addEventListener( 'mouseUp', function( e ) {
					delayHideTransform();
				} );
				transformControl.addEventListener( 'objectChange', function( e ) {
					updateSplineOutline();
				} );

				var dragcontrols = new THREE.DragControls( splineHelperObjects, camera, renderer.domElement ); //
				dragcontrols.enabled = false;
				dragcontrols.addEventListener( 'hoveron', function ( event ) {
					transformControl.attach( event.object );
					cancelHideTransorm();

				} );
				dragcontrols.addEventListener( 'hoveroff', function ( event ) {
					delayHideTransform();
				} );



				



				/*******
				 * Curves
				 *********/


/*				while ( new_positions.length > positions.length ) {

					addPoint();

				}

				while ( new_positions.length < positions.length ) {

					removePoint();

				}*/




/*				var curve = new THREE.CatmullRomCurve3( positions );
				curve.type = 'catmullrom';
				curve.mesh = new THREE.Line( geometry.clone(), new THREE.LineBasicMaterial( {
					color: 0xff0000,
					opacity: 0.35,
					linewidth: 2
					} ) );
				curve.mesh.castShadow = true;
				splines.uniform = curve;

				curve = new THREE.CatmullRomCurve3( positions );
				curve.type = 'centripetal';
				curve.mesh = new THREE.Line( geometry.clone(), new THREE.LineBasicMaterial( {
					color: 0x00ff00,
					opacity: 0.35,
					linewidth: 2
					} ) );
				curve.mesh.castShadow = true;
				splines.centripetal = curve;

				curve = new THREE.CatmullRomCurve3( positions );
				curve.type = 'chordal';
				curve.mesh = new THREE.Line( geometry.clone(), new THREE.LineBasicMaterial( {
					color: 0x0000ff,
					opacity: 0.35,
					linewidth: 2
					} ) );
				curve.mesh.castShadow = true;
				splines.chordal = curve;

				for ( var k in splines ) {

					var spline = splines[ k ];
					scene.add( spline.mesh );

				}*/

				//addPoint();
				controls.update();
				window.addEventListener( 'resize', onWindowResize, false );
///////////////////////////////////////////////////
				light = new THREE.HemisphereLight(0xffffff, 0x444444, 1);
				light.position.set(46,49,50);
				light.castShadow = true;
				light.shadow = new THREE.LightShadow( new THREE.PerspectiveCamera( 70, 1, 200, 2000 ) );
				light.shadow.bias = -0.000222;
				light.shadow.mapSize.width = 1024;
				light.shadow.mapSize.height = 1024;
				scene.add(light);
///////////////////////////////////////////////////
				light = new THREE.DirectionalLight(0xffffff, 1.0);
				light.position.set(lightPos);
				//scene.add(light);
///////////////////////////////////////////////////
				light = new THREE.PointLight(0xffffff, 1, 100);
				light.position.set(lightPos);
				scene.add(light);

				light = new THREE.AmbientLight(0xffffff); //模拟漫反射光源
				light.position.set(lightPos); //使用Ambient Light时可以忽略方向和角度，只考虑光源的位置
				//scene.add(light);
///////////////////////////////////////////////////
				animate();
				//render();




				function delayHideTransform() {

					cancelHideTransorm();
					hideTransform();

				}

				function hideTransform() {

					hiding = setTimeout( function() {

						transformControl.detach( transformControl.object );

					}, 2500 )

				}

				function cancelHideTransorm() {

					if ( hiding ) clearTimeout( hiding );

				}


			}
			function move(x,y,sx,sy){
				var cccc = skyBox.position;
				skyBox.position.set(cccc.x+x,cccc.y+y,cccc.z);

				boxX = sx*boxX;
				boxY = sx*boxY;
				skyBoxGeometry.parameters.width = boxX;
			}
			function onWindowResize() {

				camera.aspect = window.innerWidth / window.innerHeight; //500/400 ;// 
				camera.updateProjectionMatrix();

				renderer.setSize( window.innerWidth, window.innerHeight ); // 500,400); //

			}
			function animate() {

				requestAnimationFrame( animate );

				if ( mixers.length > 0 ) {

					for ( var i = 0; i < mixers.length; i ++ ) {

						mixers[ i ].update( clock.getDelta() );

					}

				}

				stats.update();
				controls.update();//采用trackball时需要加
				render();

			}

			function render() {
				//requestAnimationFrame(render);  

				//var pointToPlane = localPlane.distanceToPoint(positions[0])  ;
				//console.log(pointToPlane);
				if(positions.length>0)
					localPlane.set (new THREE.Vector3( 0, 1, 0 ), -positions[0].y);
				//controls.update();

				renderer.render( scene, camera );
				//boxHelper.update();

				
               	
               	
			}


			///////////////UI FUNCTION
			function addPoint() {

				splinePointsLength ++;

				positions.push( addSplineObject().position );

				//updateSplineOutline();

			}
			function removePoint() {

				if ( splinePointsLength <= 4 ) {

					return;

				}
				splinePointsLength --;
				positions.pop();
				scene.remove( splineHelperObjects.pop() );

				updateSplineOutline();

			}

			function exportSpline() {

				var strplace = [];

				for ( var i = 0; i < splinePointsLength; i ++ ) {

					var p = splineHelperObjects[ i ].position;
					strplace.push( 'new THREE.Vector3({0}, {1}, {2})'.format( p.x, p.y, p.z ) )

				}

				console.log( strplace.join( ',\n' ) );
				var code = '[' + ( strplace.join( ',\n\t' ) ) + ']';
				prompt( 'copy and paste code', code );

			}

			function addSplineObject( position ) {

				var material = new THREE.MeshLambertMaterial( { color: Math.random() * 0xffffff } );
				var object = new THREE.Mesh( controlBoxGeometry, material );

				if ( position ) {

					object.position.copy( position );

				} else {

					object.position.x = Math.random() * 100 - 50;
					object.position.y = Math.random() * 60;
					object.position.z = Math.random() * 80 - 40;
				}

				object.castShadow = true;
				object.receiveShadow = true;
				scene.add( object );
				splineHelperObjects.push( object );
				return object;

			}

			function updateSplineOutline() {

				for ( var k in splines ) {

					var spline = splines[ k ];

					splineMesh = spline.mesh;

					for ( var i = 0; i < ARC_SEGMENTS; i ++ ) {

						var p = splineMesh.geometry.vertices[ i ];
						p.copy( spline.getPoint( i /  ( ARC_SEGMENTS - 1 ) ) );

					}

					splineMesh.geometry.verticesNeedUpdate = true;

				}


			}


		</script>

	</body>
</html>
