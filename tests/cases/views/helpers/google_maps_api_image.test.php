<?php

//Import the helper to be tested.
//If the tested helper is using some other helper, like Html, 
//it should be imported in this line, and instantialized in startTest().
App::import('Helper', 'GoogleMapsImageApi.GoogleMapsImageApi');
App::import('Helper', 'Html');

class GoogleMapsApiImageTest extends CakeTestCase {

	private $baseUrl = 'http://maps.google.com/maps/api/staticmap';

    //Here we instantiate our helper, and all other helpers we need.
    public function startTest(){
        $this->googleMapsImageApi = new GoogleMapsImageApiHelper();
        $this->googleMapsImageApi->Html = new HtmlHelper();
    }

	public function testSetCenter(){
		// false
		$this->assertFalse($this->googleMapsImageApi->setCenter(array('nothing' => '7.657', 'bla' => '6')));
		$this->assertFalse($this->googleMapsImageApi->setCenter(array('latitude' => '7.657')));
		// address
		$this->assertEqual($this->googleMapsImageApi->setCenter('Brooklyn Bridge, New York'), 'Brooklyn+Bridge%2C+New+York');
		// latitude and longitude
		$this->assertEqual($this->googleMapsImageApi->setCenter(array('latitude' => '7.65799134', 'longitude' => '6.57899077')), '7.657991,6.578991');
		$this->assertEqual($this->googleMapsImageApi->setCenter(array('latitude' => '7.657', 'longitude' => '6')), '7.657,6');
		$this->assertEqual($this->googleMapsImageApi->setCenter(array('7.657990', '6.578992')), '7.65799,6.578992');
		$this->assertEqual($this->googleMapsImageApi->setCenter(array(), '7.657992', '6.578992'), '7.657992,6.578992');
	}

	public function testSetZoom(){
		$this->assertEqual($this->googleMapsImageApi->setZoom(6), 6);
		$this->assertEqual($this->googleMapsImageApi->setZoom(6.23), 6);
		$this->assertEqual($this->googleMapsImageApi->setZoom(-6.23), 6);
		$this->assertEqual($this->googleMapsImageApi->setZoom('6.23'), 6);
	}

	public function testSetSize(){
		// false
		$this->assertFalse($this->googleMapsImageApi->setSize(array('nothing' => '7', 'bla' => '6')));
		$this->assertFalse($this->googleMapsImageApi->setSize(array('width' => '7')));
		// string
		$this->assertEqual($this->googleMapsImageApi->setSize('450x320'), '450x320');
		// width and height
		$this->assertEqual($this->googleMapsImageApi->setSize(array('width' => 450, 'height' => 320)), '450x320');
		$this->assertEqual($this->googleMapsImageApi->setSize(array(450, 320)), '450x320');
		// exceeds MAX_WIDTH and or MAX_HEIGHT
		$this->assertFalse($this->googleMapsImageApi->setSize(array(1450, 1320)));
	}

	public function testSetScale(){
		// false
		$this->assertFalse($this->googleMapsImageApi->setScale(0));
		$this->assertFalse($this->googleMapsImageApi->setScale(3));
		$this->assertFalse($this->googleMapsImageApi->setScale('string'));
		// true
		$this->assertEqual($this->googleMapsImageApi->setScale(1), 1);
		$this->assertEqual($this->googleMapsImageApi->setScale(2), 2);
	}

	public function testSetFormat(){
		// false
		$this->assertFalse($this->googleMapsImageApi->setFormat('test'));
		// true
		$this->assertEqual($this->googleMapsImageApi->setFormat('png'), 'png');
		$this->assertEqual($this->googleMapsImageApi->setFormat('PNG'), 'png');
		$this->assertEqual($this->googleMapsImageApi->setFormat('jpg-baseline'), 'jpg-baseline');
	}

	public function testSetMaptype(){
		// false
		$this->assertFalse($this->googleMapsImageApi->setMaptype('test'));
		// true
		$this->assertEqual($this->googleMapsImageApi->setMaptype('roadmap'), 'roadmap');
		$this->assertEqual($this->googleMapsImageApi->setMaptype('Hybrid'), 'hybrid');
	}
	
	public function testSetLanguage(){
		$this->assertFalse($this->googleMapsImageApi->setLanguage(true));
		$this->assertEqual($this->googleMapsImageApi->setLanguage('en'), 'en');
		$this->assertEqual($this->googleMapsImageApi->setLanguage('EN'), 'en');
	}

	public function testSetMarkerStyles(){
		// false
		$this->assertFalse($this->googleMapsImageApi->setMarkerStyles('test'));
		$this->assertFalse($this->googleMapsImageApi->setMarkerStyles(array()));
		// size 
		$this->assertEqual($this->googleMapsImageApi->setMarkerStyles(array('size' => 'abcd')), false);
		$this->assertEqual($this->googleMapsImageApi->setMarkerStyles(array('size' => 'tiny')), 'size:tiny');
		$this->assertEqual($this->googleMapsImageApi->setMarkerStyles(array('size' => 'MID')), 'size:mid');
		// color
		$this->assertEqual($this->googleMapsImageApi->setMarkerStyles(array('color' => 'blue')), 'color:blue');
		$this->assertEqual($this->googleMapsImageApi->setMarkerStyles(array('color' => '0xff0099')), 'color:0xff0099');
		$this->assertEqual($this->googleMapsImageApi->setMarkerStyles(array('color' => '0xff009933')), 'color:0xff009933');
		$this->assertEqual($this->googleMapsImageApi->setMarkerStyles(array('color' => '0xFF0099')), 'color:0xff0099');
		$this->assertEqual($this->googleMapsImageApi->setMarkerStyles(array('color' => 'ablue')), false);
		// size 
		$this->assertEqual($this->googleMapsImageApi->setMarkerStyles(array('label' => 'abcd')), 'label:A');
		$this->assertEqual($this->googleMapsImageApi->setMarkerStyles(array('label' => '1')), 'label:1');
		// compound
		$this->assertEqual($this->googleMapsImageApi->setMarkerStyles(array(
			'size' => 'tiny', 
			'color' => 'Green', 
			'label' => 'a')
			), 
			'size:tiny%7Ccolor:green%7Clabel:A'
		);
	}

	public function testSetMarker(){
		// false
		$this->assertFalse($this->googleMapsImageApi->setMarker('test'));
		$this->assertFalse($this->googleMapsImageApi->setMarker(array()));
		$this->assertFalse($this->googleMapsImageApi->setMarker(array('icon' => 'http://www.example.com/image.jpg')));
		
		// location
		$this->assertEqual($this->googleMapsImageApi->setMarker(array('location' => 'Brooklyn Bridge, NY')), array('location' => 'Brooklyn+Bridge%2C+NY'));
		$this->assertEqual($this->googleMapsImageApi->setMarker(array('location' => array('latitude' => '4.587999', 'longitude' => '90.55'))), array('location' => '4.587999,90.55'));
		$this->assertEqual($this->googleMapsImageApi->setMarker(array('location' => array('4.58799901', '90.5500'))), array('location' => '4.587999,90.55'));

		// icon
		$this->assertEqual($this->googleMapsImageApi->setMarker(
			array(
				'location' => array('4.58799901', '90.5500'),
				'icon' => 'http://www.example.com/image'
			)), 
			array('location' => '4.587999,90.55',
				'icon' => urlencode('http://www.example.com/image')
			)
		);
	}

	public function testSetMarkers(){
		
		// false
		$this->assertFalse($this->googleMapsImageApi->setMarkers('tests'));
		$this->assertFalse($this->googleMapsImageApi->setMarkers(array()));

		// markers
		$this->assertEqual($this->googleMapsImageApi->setMarkers(
			array(
				'markers' => array(
					array(
						'location' => array('4.58799901', '90.5500'),
					),
					array(
						'location' => array('latitude' => '81.220099', 'longitude' => '8.567831')		
					)
				),
				'markerStyles' => array(
					'size' => 'tiny',
					'color' => '0xFF3333'
				)
			)
		), 
		array(
			'markerStyles' => 'size:tiny%7Ccolor:0xff3333',	
			'markers' => array(
				array('location' => '4.587999,90.55'),
				array('location' => '81.220099,8.567831')
			)
		));
	}

	public function testSetPathStyles(){
		// false
		$this->assertFalse($this->googleMapsImageApi->setPathStyles('test'));
		$this->assertFalse($this->googleMapsImageApi->setPathStyles(array()));
		// weight 
		$this->assertEqual($this->googleMapsImageApi->setPathStyles(array('weight' => '12')), 'weight:12');
		// color
		$this->assertEqual($this->googleMapsImageApi->setPathStyles(array('color' => 'blue')), 'color:blue');
		$this->assertEqual($this->googleMapsImageApi->setPathStyles(array('color' => '0xff0099')), 'color:0xff0099');
		$this->assertEqual($this->googleMapsImageApi->setPathStyles(array('color' => '0xff009933')), 'color:0xff009933');
		$this->assertEqual($this->googleMapsImageApi->setPathStyles(array('color' => '0xFF0099')), 'color:0xff0099');
		$this->assertEqual($this->googleMapsImageApi->setPathStyles(array('color' => 'ablue')), false);
		// color
		$this->assertEqual($this->googleMapsImageApi->setPathStyles(array('fillcolor' => 'blue')), 'fillcolor:blue');
		$this->assertEqual($this->googleMapsImageApi->setPathStyles(array('fillcolor' => '0xff0099')), 'fillcolor:0xff0099');
		$this->assertEqual($this->googleMapsImageApi->setPathStyles(array('fillcolor' => '0xff009933')), 'fillcolor:0xff009933');
		$this->assertEqual($this->googleMapsImageApi->setPathStyles(array('fillcolor' => '0xFF0099')), 'fillcolor:0xff0099');
		$this->assertEqual($this->googleMapsImageApi->setPathStyles(array('fillcolor' => 'ablue')), false);
		// compound
		$this->assertEqual($this->googleMapsImageApi->setPathStyles(
			array(
				'weight' => '9', 
				'color' => 'Green', 
				'fillcolor' => '0xFF0000'
			)
		), 
		'weight:9%7Ccolor:green%7Cfillcolor:0xff0000'
		);
	}

	public function testSetPath(){
		// false
		$this->assertFalse($this->googleMapsImageApi->setPath('test'));
		$this->assertFalse($this->googleMapsImageApi->setPath(array()));
		
		// location
		$this->assertEqual($this->googleMapsImageApi->setPath(array('points' => array(array('5.678903', '56.45')))), '5.678903,56.45');
		$this->assertEqual($this->googleMapsImageApi->setPath(array('points' => array(array('5.678903', '56.45'), array('50.678987', '90')))), '5.678903,56.45%7C50.678987,90');
		$this->assertEqual($this->googleMapsImageApi->setPath(array('points' => array(array('latitude' => '5.678903', 'longitude' => '56.45'), array('latitude' => '50.678987', 'longitude' => '90'), array('latitude' => '55.678987', 'longitude' => '90')))), '5.678903,56.45%7C50.678987,90%7C55.678987,90');
	}

	public function testSetPaths(){
		// false
		$this->assertFalse($this->googleMapsImageApi->setPaths('test'));
		$this->assertFalse($this->googleMapsImageApi->setPaths(array()));
		// compound
		$this->assertEqual(
			$this->googleMapsImageApi->setPaths(
				array(
					'paths' => array(
						array('points' => array(
							array('latitude' => '5.678903', 'longitude' => '56.45'), 
							array('latitude' => '50.678987', 'longitude' => '90'), 
							array('latitude' => '55.678987', 'longitude' => '90')
							)
						)
					),
					'pathStyles' => array(
						'color' => 'blue'
					)
				)
			),
			array(
				'paths' => array(
					'5.678903,56.45%7C50.678987,90%7C55.678987,90'
				),
				'pathStyles' => 'color:blue'
			)
		);
	}

	public function testSetVisible(){
		// false
		$this->assertFalse($this->googleMapsImageApi->setVisible('test'));
		$this->assertFalse($this->googleMapsImageApi->setVisible(array()));
		// addresses
		$this->assertEqual($this->googleMapsImageApi->setVisible(array('Brooklyn Bridge, New York')), array('Brooklyn+Bridge%2C+New+York'));
		$this->assertEqual($this->googleMapsImageApi->setVisible(array('Brooklyn Bridge, New York', 'New Jersey')), array('Brooklyn+Bridge%2C+New+York', 'New+Jersey'));
	}

	public function testSetStyle(){
		// false
		$this->assertFalse($this->googleMapsImageApi->setStyle('test'));
		$this->assertFalse($this->googleMapsImageApi->setStyle(array()));
		// styles
		$this->assertEqual($this->googleMapsImageApi->setStyle(array('feature' => 'test')), array('feature' => 'test'));
		$this->assertEqual($this->googleMapsImageApi->setStyle(array('element' => 'geometry')), array('element' => 'geometry'));
		// rules hue
		$this->assertEqual($this->googleMapsImageApi->setStyle(array('rules' => array('hue' => '0xFF0000'))), array('rules' => array('hue:0xff0000')));
		$this->assertEqual($this->googleMapsImageApi->setStyle(array('rules' => array('hue' => '0xFF0000FF'))), false);
		// rules lightness
		$this->assertEqual($this->googleMapsImageApi->setStyle(array('rules' => array('lightness' => '54.4'))), array('rules' => array('lightness:54.4')));
		$this->assertEqual($this->googleMapsImageApi->setStyle(array('rules' => array('lightness' => '-63.88'))), array('rules' => array('lightness:-63.88')));
		$this->assertEqual($this->googleMapsImageApi->setStyle(array('rules' => array('lightness' => '155'))), false);
		// rules saturation
		$this->assertEqual($this->googleMapsImageApi->setStyle(array('rules' => array('saturation' => '54.4'))), array('rules' => array('saturation:54.4')));
		$this->assertEqual($this->googleMapsImageApi->setStyle(array('rules' => array('saturation' => '-63.88'))), array('rules' => array('saturation:-63.88')));
		$this->assertEqual($this->googleMapsImageApi->setStyle(array('rules' => array('saturation' => '155'))), false);
		// rules gamma
		$this->assertEqual($this->googleMapsImageApi->setStyle(array('rules' => array('gamma' => '0.02'))), array('rules' => array('gamma:0.02')));
		$this->assertEqual($this->googleMapsImageApi->setStyle(array('rules' => array('gamma' => '9'))), array('rules' => array('gamma:9')));
		$this->assertEqual($this->googleMapsImageApi->setStyle(array('rules' => array('gamma' => '15.5'))), false);
		// rules inverse_lightness
		$this->assertEqual($this->googleMapsImageApi->setStyle(array('rules' => array('inverse_lightness' => 'a'))), array('rules' => array('inverse_lightness:true')));
		$this->assertEqual($this->googleMapsImageApi->setStyle(array('rules' => array('inverse_lightness' => true))), array('rules' => array('inverse_lightness:true')));
		$this->assertEqual($this->googleMapsImageApi->setStyle(array('rules' => array('inverse_lightness' => 'false'))), array('rules' => array('inverse_lightness:false')));
		// rules visibility
		$this->assertEqual($this->googleMapsImageApi->setStyle(array('rules' => array('visibility' => 'on'))), array('rules' => array('visibility:on')));
		$this->assertEqual($this->googleMapsImageApi->setStyle(array('rules' => array('visibility' => 'simplified'))), array('rules' => array('visibility:simplified')));
		$this->assertEqual($this->googleMapsImageApi->setStyle(array('rules' => array('visibility' => 'of'))), false);
	}

	public function testSetSensor(){
		$this->assertEqual($this->googleMapsImageApi->setSensor('test'), 'true');
		$this->assertEqual($this->googleMapsImageApi->setSensor(true), 'true');
		$this->assertEqual($this->googleMapsImageApi->setSensor(false), 'false');
		$this->assertEqual($this->googleMapsImageApi->setSensor(null), 'false');
		$this->assertEqual($this->googleMapsImageApi->setSensor('false'), 'false');
	}

	public function testSetGetParameters(){
		$parameters = array(
			'center' => 'Brooklyn Bridge, New York',
			'size' => array(
				'width' => 500,
				'height' => 320
			),
			'zoom' => 9
		);
		$parameters2 = $parameters;
		$parameters2['center'] = 'Brooklyn+Bridge%2C+New+York';
		$parameters2['size'] = '500x320';
		$this->assertEqual($this->googleMapsImageApi->setParameters($parameters), $parameters2);
		$parameters2['sensor'] = 'false';
		$this->assertEqual($this->googleMapsImageApi->getParameters(), $parameters2);
		$this->assertEqual($this->googleMapsImageApi->getParameter('zoom'), 9);
		$this->assertEqual($this->googleMapsImageApi->getParameter('size'), '500x320');
		// set paths
		$this->assertEqual($this->googleMapsImageApi->setParameters(array(
			'paths' => array(
				array(
					'pathStyles' => array(
						'weight' => 2,
						'color' => 'blue'
					),
					'paths' => array(
						array(
							'points' => array(
								array('61.23456', '31.234567'),
								array('68.23456', '31.234567'),
								array('68.23456', '34.234567')
							)
						)
					)
				),
				array('pathStyles' => array(
						'weight' => 0,
						'fillcolor' => '0xFF990033'
					),
					'paths' => array(
						array(
							'points' => array(
								array('71.23456', '21.234567'),
								array('78.23456', '21.234567'),
								array('78.23456', '24.234567')
							)
						)
					)
				)
			)
		)), 
		array(
			'paths' => array(
				array(
					'pathStyles' => 'weight:2%7Ccolor:blue',
					'paths' => array(
						'61.23456,31.234567%7C68.23456,31.234567%7C68.23456,34.234567'
					)
				),
				array(
					'pathStyles' => 'weight:0%7Cfillcolor:0xff990033',
					'paths' => array(
						'71.23456,21.234567%7C78.23456,21.234567%7C78.23456,24.234567'
					)
				)
			)
		));
	}

	public function testIsValidMap(){
		$this->assertFalse($this->googleMapsImageApi->isValidMap());
		$this->googleMapsImageApi->setParameters(array('center' => 'Brooklyn Bridge, New York'));
		$this->assertFalse($this->googleMapsImageApi->isValidMap());
		$this->googleMapsImageApi->setParameters(array('size' => '500x320'));
		$this->assertTrue($this->googleMapsImageApi->isValidMap());
	}

	public function testStaticMap(){
		// non-valid map
		$this->assertEqual($this->googleMapsImageApi->staticMap(), '');
		$this->assertEqual($this->googleMapsImageApi->staticMap(array(
			'size' => '500x400',
		)), 
		'');
		// no validity check
		$this->assertEqual($this->googleMapsImageApi->staticMap(array(
			'size' => '500x400',
		), null, false, false), 
		'http://maps.google.com/maps/api/staticmap?size=500x400&sensor=false');
		// createImage = false
		$this->assertEqual($this->googleMapsImageApi->staticMap(array(
			'size' => '500x400',
			'center' => array(
				'latitude' => 60.556677,
				'longitude' => 20.123456
			)
		)), 
		'http://maps.google.com/maps/api/staticmap?center=60.556677,20.123456&size=500x400&sensor=false');
		// createImage = true, including htmlAttributes
		$this->assertEqual($this->googleMapsImageApi->staticMap(array(
			'size' => '500x400',
			'center' => array(
				'latitude' => 60.556677,
				'longitude' => 20.123456
			)
		), array('alt' => 'alttext', 'class' => 'google-maps'), true), 
		'<img src="http://maps.google.com/maps/api/staticmap?center=60.556677,20.123456&size=500x400&sensor=false" alt="alttext" class="google-maps" />');
		// complex map
		$this->googleMapsImageApi->resetParameters();
		$this->assertEqual($this->googleMapsImageApi->staticMap(array(
			'size' => array(
				'width' => 500,
				'height' => 320
			),
			'markers' => array(
				array(
					'markerStyles' => array(
						'size' => 'mid',
						'color' => 'red',
						'label' => 'G'
					),
					'markers' => array(
						array(
							'location' => array(
								'latitude' => '62.345678',
								'longitude' => '30.456677'
							),
							'icon' => 'http://chart.apis.google.com/chart?chst=d_map_pin_icon&chld=cafe|996600'
						)
					)
				),
				array(
					'markerStyles' => array(
						'size' => 'tiny',
						'color' => 'blue',
						'label' => 'G'
					),
					'markers' => array(
						array(
							'location' => array(
								'latitude' => '61.345678',
								'longitude' => '31.456677'
							)
						)
					)
				)
			),
			'paths' => array(
				array(
					'pathStyles' => array(
						'weight' => 0,
						'fillcolor' => '0xFF000033'
					),
					'paths' => array(
						array(
							'points' => array(
								array('latitude' => '62.345678', 'longitude' => '30.456677'),
								array('latitude' => '62.345678', 'longitude' => '31.456677'),
								array('latitude' => '61.345678', 'longitude' => '31.456677'),
								array('latitude' => '61.345678', 'longitude' => '30.456677')
							)
						)
					)
				),
				array(
					'pathStyles' => array(
						'weight' => 5,
						'color' => 'black'
					),
					'paths' => array(
						array(
							'points' => array(
								array('62', '30'),
								array('60', '28')
							)
						)
					)
				)
			),
			'style' => array(
				'element' => 'all',
				'rules' => array(
					'hue' => '0xff0000',
					'lightness' => -25,
					'visibility' => 'simplified'
				)
			)	
		), null, true),
		'<img src="http://maps.google.com/maps/api/staticmap?size=500x320&markers=size:tiny%7Ccolor:blue%7Clabel:G%7C62.345678,30.456677%7Cicon:http%3A%2F%2Fchart.apis.google.com%2Fchart%3Fchst%3Dd_map_pin_icon%26chld%3Dcafe%7C996600&markers=61.345678,31.456677&path=weight:0%7Cfillcolor:0xff000033%7C62.345678,30.456677%7C62.345678,31.456677%7C61.345678,31.456677%7C61.345678,30.456677&path=weight:5%7Ccolor:black%7C62,30%7C60,28&style=element:all%7Chue:0xff0000%7Clightness:-25%7Cvisibility:simplified&sensor=false" alt="" />');
	}
}
?>
