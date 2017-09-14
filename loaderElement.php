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
			    <?=($_GET["name"]=="pcbim"&&$_GET["id"]==0?"":"display: none")?>

			}
			#elementlist{
				height: 200px;
				font-size: 12px;
			}


			#box { 
				width: 200px; height: 100px; cursor: move; position: absolute; top: 200px; left: 300px; 
				/*border: 3px solid red;*/
				display: none;
				border:20px solid;
				-moz-border-image:url(../../image/border.png) 30 30 round;	/* Old Firefox */
				-webkit-border-image:url(../../image/border.png) 30 30 round;	/* Safari and Chrome */
				-o-border-image:url(../../image/border.png) 30 30 round;		/* Opera */
				border-image:url(../../image/border.png) 30 30 round;
				 

			}



			#coor { 
				width: 15px; height: 15px; overflow: hidden; cursor: se-resize; position: absolute; right: 0; bottom: 0; 
				background-color: #d0d0d0; 
				border:3px solid #337ab7;
				


				border-radius: 50%;      -moz-border-radius: 50%;      -webkit-border-radius: 50%;
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
					<button type="button" class="btn btn-primary" onclick="showParentPanel();" >添加</button>
						<button type="button" class="btn btn-default" onclick="$('#box').show();" >云线</button>
						<button type="button" class="btn btn-default" id="printPic" >截图</button>
						<button type="button" class="btn btn-default"  >提交</button>
						
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
				<li class="list-group-item">
					<button type="button" class="btn btn-xs btn-default" onclick="exportGLTF(gltfExp);">保存GLTF</button>
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

		<script src="js/exporters/GLTFExporter.js"></script>

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

			var gltfExp ;

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
				clippingPlanes: [ localPlane ],
				clipShadows: true
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

				var loader = new THREE.FBXLoader( manager );
				//var loader = new THREE.GLTFLoader( manager );
				//loader.load( 'models/fbx/opbim.fbx', function( object ) {
			    //loader.load( 'models/fbx/<?=$_GET["name"]?>.gltf', function( object ) {   //WQB2   pcbim
				loader.load( 'models/fbx/<?=$_GET["name"]?>.fbx', function( object ) {   //WQB2   pcbim

					//object.rotation.x = THREE.Math.degToRad( 90 );//旋转90
					gltfExp = object;
				    // if you want to add your custom material
				    var materialObj = new THREE.MeshBasicMaterial({
				        vertexColors: THREE.FaceColors,
				        overdraw: 1.0
				    });

				    var groupSlab60 = new THREE.Group();
				    var groupSlab70 = new THREE.Group();
				    var groupOTHER = new THREE.Group();


				    object.position.set( 0, 0, 0 );

               		mdBorder = new THREE.BoxHelper( object,0x000000 );
               		mdCenter = (mdBorder.geometry.boundingSphere.center); 
               		//console.log(mdBorder.geometry.attributes.position);

               		var maxX,maxY,maxZ,minX,minY,minZ;
               		maxX=maxY=maxZ=0;
               		minX=minY=minZ=9999999;
               		for(var i=0;i<8;i++){
               			if(maxX<mdBorder.geometry.attributes.position.getX(i))maxX=mdBorder.geometry.attributes.position.getX(i);
               			if(minX>mdBorder.geometry.attributes.position.getX(i))minX=mdBorder.geometry.attributes.position.getX(i);

               			if(maxY<mdBorder.geometry.attributes.position.getY(i))maxY=mdBorder.geometry.attributes.position.getY(i);
               			if(minY>mdBorder.geometry.attributes.position.getY(i))minY=mdBorder.geometry.attributes.position.getY(i);

               			if(maxZ<mdBorder.geometry.attributes.position.getZ(i))maxZ=mdBorder.geometry.attributes.position.getZ(i);
               			if(minZ>mdBorder.geometry.attributes.position.getZ(i))minZ=mdBorder.geometry.attributes.position.getZ(i);
               		}
               		var lenX=(maxX - minX);
               		var lenY=(maxY - minY);THREE.Math.degToRad( 90 );  
               		var lenZ=(maxZ - minZ);

               		console.log(lenX + " , " + lenY + " , " + lenZ);
               		var lenMin = Math.min(lenX,lenY,lenZ);
               		var lenMax = Math.max(lenX,lenY,lenZ);

               		//transformControl.size = lenMin/20;

               		var boxSize =  (lenMax+lenMin)/30;
               		//boxSize=10;
               		//console.log(controlBoxGeometry);
               		controlBoxGeometry = new THREE.BoxGeometry( boxSize,boxSize,boxSize );
               		//controlBoxGeometry.parameters.width = boxSize;
               		//controlBoxGeometry.parameters.innerHeight = boxSize;
               		//controlBoxGeometry.parameters.depth = boxSize;

               		//localPlane.set (new THREE.Vector3( 0, 1, 0 ), maxY);//mdCenter.y);

               		var localPlanePosition = new THREE.Vector3(mdCenter.x,minY-1,mdCenter.z);
               		//localPlanePosition = mdCenter.clone();
               		//localPlanePosition.y = maxY;
               		//console.log(localPlanePosition);
					positions = [];
					for ( var i = 0; i < splinePointsLength; i ++ ) {
						addSplineObject( localPlanePosition ); //positions[ i ]
					}	
					for ( var i = 0; i < splinePointsLength; i ++ ) {
						positions.push( splineHelperObjects[ i ].position );
					}
					var geometry = new THREE.Geometry();
					for ( var i = 0; i < ARC_SEGMENTS; i ++ ) {
						geometry.vertices.push( new THREE.Vector3() );
					}

               		

               		//var pointToPlane = localPlane.distanceToPoint(positions[0])  ;
               		//localPlane.set (new THREE.Vector3( -1, 0, 0 ),mdCenter.x+pointToPlane);
               		//console.log(pointToPlane);
               		//scene.add(mdBorder);
			        //var helper = new THREE.BoundingBoxHelper(object, 0xff0000);  
			        //helper.update();  
			        //scene.add(helper);  


					var gridHelper = new THREE.GridHelper( Math.max(maxX,maxY,maxZ)*2, 100, 0xD1D1D1, 0xD1D1D1 );
					gridHelper.position.set((lenX===lenMin?minX-0.1: mdCenter.x),(lenY===lenMin?minY-0.1: mdCenter.y),(lenZ===lenMin?minZ-0.1: mdCenter.z) );
					if(lenX===lenMin)
						gridHelper.rotation.z = THREE.Math.degToRad( 90 );
					if(lenY===lenMin)
						gridHelper.rotation.y = THREE.Math.degToRad( 90 );
					if(lenZ===lenMin)
						gridHelper.rotation.x = THREE.Math.degToRad( 90 );
					scene.add( gridHelper );


					boxX = maxX*1.45;
					boxY = boxX*9933/7016;
					<?php if($_GET["name"]=="pcbim") :?>
					skyBoxGeometry = new THREE.BoxGeometry(boxX , boxY, 0.1 );  
					var texture = new THREE.TextureLoader().load("DRAFT/PM1_01.png");  
					var skyBoxMaterial = new THREE.MeshBasicMaterial( { 
						map:texture, 
						side: THREE.DoubleSide ,

					} );  
					skyBox = new THREE.Mesh( skyBoxGeometry, skyBoxMaterial );  
					skyBox.position.set(mdCenter.x,mdCenter.y-14.5,0);
					skyBox.receiveShadow = true;
					//skyBox.rotation.x = THREE.Math.degToRad( 90 );//旋转90
					scene.add(skyBox); 

					<?php endif; ?>


					console.log(Math.max(maxX,maxY,maxZ));
					console.log(mdCenter);
					//console.log(lenMax);
					var scaleCamera = lenMax+0;


					controls.target.set( mdCenter.x,mdCenter.y,mdCenter.z);
					camera.position.set( mdCenter.x + (lenX===lenMin?scaleCamera:0)  , mdCenter.y + (lenY===lenMin?scaleCamera:0) , mdCenter.z + (lenZ===lenMin?scaleCamera:0) ); 
					lightPos = camera.position; 
					lightTarget = controls.target;
					controls.update();


				    object.traverse(function(child) {

				    	//console.log(decodeURI(EncodeUtf8(child.name)));
				    	child.name = decodeURI(EncodeUtf8(child.name));

				    	if(child.name!=""){
				    		var optionContainer = document.createElement( 'option' );
							optionContainer.innerHTML = child.name ;
							optionContainer.onclick = function(){ 
								if(previousSelection!=null){
									previousSelection.material=previousSelectionMaterial; 
								}
								previousSelection=child;
								previousSelectionMaterial=child.material;
								child.material=materialSELECTED; 
								var selCenter = (child.geometry.boundingSphere.center); 
								scaleCamera = lenMax*0.2;
								controls.target.set( selCenter.x,selCenter.y,selCenter.z);
								camera.position.set( selCenter.x + (lenX===lenMin?scaleCamera:0)  , selCenter.y + (lenY===lenMin?scaleCamera:0) , selCenter.z + (lenZ===lenMin?scaleCamera:0) ); 
								controls.update();
							}

							elementListContainer = document.getElementById("elementlist");
							elementListContainer.appendChild( optionContainer );
				    	}



				    	//console.log(child);
				    	//document.getElementById("situation").innerHTML+=child.name;
				    	//infocontainer.innerHTML = gb2utf8(child.name);
				        if (child instanceof THREE.Mesh) {
				        	if(<?=$_GET["id"]?$_GET["id"]:0?> && child.name.indexOf("<?=$_GET["id"]?>")>-1){
				        		child.material = materialSELECTED;

							    var edges = new THREE.EdgesGeometry(child.geometry);
							    //edges = new THREE.EdgesHelper( child.geometry, 0x1535f7 );//设置边框，可以旋转
							    var line = new THREE.LineSegments(edges, materialLine);
							    scene.add(line);

			               		mdBorder = new THREE.BoxHelper( child,0x000000 );
			               		mdCenter = (mdBorder.geometry.boundingSphere.center); 
			               		controls.target.set( mdCenter.x,mdCenter.y,mdCenter.z);
			               		scaleCamera = lenMax*0.2;
								camera.position.set( mdCenter.x + (lenX===lenMin?scaleCamera:0)  , mdCenter.y + (lenY===lenMin?scaleCamera:0) , mdCenter.z + (lenZ===lenMin?scaleCamera:0) ); 

				        		//child.visible = false;
				        	}else if(child.name.indexOf("楼板")>-1){
				        		child.material = materialSlab;

							    var edges = new THREE.EdgesGeometry(child.geometry);
							    //edges = new THREE.EdgesHelper( child.geometry, 0x1535f7 );//设置边框，可以旋转
							    var line = new THREE.LineSegments(edges, materialLine);
							    scene.add(line);
							    
							    child.visible=false;
							    if(child.name.indexOf("60mm")>-1){
							    	child.visible=true;
							    }
							    if(child.name.indexOf("70mm")>-1){
							    	child.visible=true;
							    }

				        	}else if(child.name.indexOf("墙")>-1){
				        		child.material = materialWall;
							    var edges = new THREE.EdgesGeometry(child.geometry);
							    var line = new THREE.LineSegments(edges, materialLine);
							    scene.add(line);
				        		//child.visible = false;
				        	}else if(child.name.indexOf("HRB")>-1){
				        		child.material = materialHRB;
				        		//border = new THREE.BoxHelper( child,0x000000 );
				        		//scene.add( border );

							    var edges = new THREE.EdgesGeometry(child.geometry);
							    var line = new THREE.LineSegments(edges, materialLine);
							    scene.add(line);

				        		//child.visible = false;
				        	}else if(child.name.indexOf("楼梯")>-1){
				        		child.material = materialStair;
							    var edges = new THREE.EdgesGeometry(child.geometry);
							    var line = new THREE.LineSegments(edges, materialLine);
							    scene.add(line);
				        	}else if(child.name.indexOf("门")>-1 || child.name.indexOf("窗")>-1){
				        		child.material=materialSlab;
							    var edges = new THREE.EdgesGeometry(child.geometry);
							    var line = new THREE.LineSegments(edges, materialLine);
							    //scene.add(line);
				        	}else{
				        		child.material = materialBeam;
				        		border = new THREE.BoxHelper( child,0x000000 );
				        		//scene.add( border );
				        		//console.log(child);
				        	} 						
				        }
				    });
				    // then directly add the object
				    object.castShadow = true;
				    object.receiveShadow = true;
				    scene.add(object); 

				}, onProgress, onError );


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

			function exportGLTF( input ) {

				var gltfExporter = new THREE.GLTFExporter();

				var length=0;
				input.traverse(function(child) {
					length++;
				});
				console.log(length);
				var split = 30;
				var eachCount = Math.round(length/split);

				var currentId = 0;
				var elementSet = [];

				var currentSet = [];

				var i = 0;
				input.traverse(function(child) {
					currentSet.push(child);
					if(i<=eachCount)
						i++;
					else{
						i=0;
						elementSet[currentId]=currentSet;
						currentSet=[];
						currentId++;
					}
				});

				if(i!=0){
					elementSet[currentId] = currentSet;
					currentSet=[];
					currentId;
					i++;
				}

				//console.log(elementSet);

				for(var i = 0 ;i<currentId;i++){
					gltfExporter.parse( elementSet[i], function( result ) {
						var output = JSON.stringify( result, null, 2 );
						saveString( output, 'scene.gltf' );
					});
				}
/*				gltfExporter.parse( input, function( result ) {
					var output = JSON.stringify( result, null, 2 );
					///console.log( output );
					saveString( output, 'scene.gltf' );
				} );*/

			}

			function save( blob, filename ) {

				link.href = URL.createObjectURL( blob );
				link.download = filename || 'data.json';
				link.click();

				// URL.revokeObjectURL( url ); breaks Firefox...

			}

			function saveString( text, filename ) {

				save( new Blob( [ text ], { type: 'text/plain' } ), filename );

			}
			var link = document.createElement( 'a' );
			link.style.display = 'none';
			document.body.appendChild( link ); // Firefox workaround, see #6594



		</script>

	</body>
</html>
