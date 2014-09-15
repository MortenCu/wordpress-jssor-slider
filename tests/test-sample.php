<?php

include '/lib/add-new-slider-class.php';
include 'front_views/jssorslider.helper.class.php';

class SampleTest extends WP_UnitTestCase {
	
	public $slideArray,$captionArray1,$captionArray2;
	
	function setUp() {
		
		parent::setUp();
		$init1 = new stdClass;
		$init1->slide_trans = '';
		
		$init2 = new stdClass;
		$init2->slide_trans = 'NULL';
		
		$init3 = new stdClass;
		$init3->slide_trans = '{Duration:1200,Zoom:11,Rotate:-1,FlyDirection:10}';
		
		$this->slideArray = array( $init1, $init2, $init3 );
		
		$init4 = new stdClass;
		$init4->caption_in = '';
		$init4->caption_out = 'NULL';
		$init4->description_in = '{JssorEasing.EaseInCubic,Top:JssorEasing.EaseInCubic}';
		$init4->description_out = '{Opacity:2,During:{Top:[0,.5]},Round:{Left:.3,Top:.5}}';
		
		$this->captionArray1 = array( $init4 );
		
		$this->captionArray2 = array(
			'----- Move -----' => 'NULL',
			'{JssorEasing.EaseInCubic,Top:JssorEasing.EaseInCubic}' => 'TR|IE',
			'{Opacity:2,During:{Top:[0,.5]},Round:{Left:.3,Top:.5}}' => 'TR-*IB'
		);
		
	}
	
	function tearDown() {
	
		unset( $this->slideArray );
		unset( $this->captionArray1 );
		unset( $this->captionArray2 );
		
	}
	
	function testPluginInitialization() {
		
		$inst = JssorSliderPlugin::getInstance();
		$this->assertFalse( null == $inst );
	
	}
	
	function testDatabaseTableNames () {
		
		global $wpdb;
		$this->assertTrue( $wpdb->prefix . 'jssor_sliders' == JssorSliderPlugin::jssor_sliders() );
		$this->assertTrue( $wpdb->prefix . 'jssor_slides' == JssorSliderPlugin::jssor_slides() );
		
	}
	
	function testCaptionSelectHtml () {
	
		$originalArray = array(
			'----- Move -----' => 'NULL',
			'L' => '{Duration:900,FlyDirection:1}'
		);
		$expectedResult = '<option value="NULL">----- Move -----</option><option value="{Duration:900,FlyDirection:1}">L</option>';
		$result1 = caption_select( $originalArray );
		$result2 = slide_select( $originalArray );
		$this->assertEquals( $result1, $expectedResult );
		$this->assertEquals( $result2, $expectedResult );
	
	}
	
	function testFormat_R () {
	
		$originalArray = array(
			'{Duration:900,FlyDirection:1}' => 'L',
			'NULL' => '------------ Move -----------'
		);
		$expectedResult = array(
			'{Duration:900,FlyDirection:1}' => 'L',
			'NULL' => 'NO'
		);
		$result = JssorSliderHelper::format_R( $originalArray );
		$this->assertEquals( $result, $expectedResult );
		
	}
	
	function testGetslide_Trans() {
		
		$originalArray = $this->slideArray;
		$expectedResult = "{Duration:1200,Zoom:11,Rotate:-1,FlyDirection:10},\n";
		$result = JssorSliderHelper::getslide_trans( $originalArray );
		$this->assertEquals( $result, $expectedResult );
		
	}
	
	/**
   	 * 
   	 * @param string $stringArray
   	 * @param string $expectedResult 
   	 * 
   	 * @dataProvider providerTestGetcaption_Trans
   	 */
	function testGetcaption_Trans( $stringArray, $expectedResult ) {
		
		$originalArray1 = $this->captionArray1;
		$originalArray2 = $this->captionArray2;
		
		$result = JssorSliderHelper::getcaption_trans( $originalArray1, $originalArray2, $stringArray );
		$this->assertEquals( $result, $expectedResult );
		
	}
	
	function providerTestGetcaption_Trans() {
	
		return array(
			array( 'caption_in', '' ),
			array( 'caption_out', '' ),
			array( 'description_in', "_CaptionTransitions[\"TR|IE\"] = {JssorEasing.EaseInCubic,Top:JssorEasing.EaseInCubic};\n" ),
			array( 'description_out', "_CaptionTransitions[\"TR-*IB\"] = {Opacity:2,During:{Top:[0,.5]},Round:{Left:.3,Top:.5}};\n" )
		);
		
	}
	
}

