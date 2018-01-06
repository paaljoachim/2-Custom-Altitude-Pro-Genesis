// Resources:
// https://github.com/daneden/animate.css
// https://spin.atomicobject.com/2015/05/31/scroll-anmiation-css-waypoints/


jQuery(function($) {


 $('#front-page-1').waypoint(function(direction) {
	if (direction == 'down') {
			$('#front-page-1 h1').toggleClass( 'animated fadeInUp' );
			$('#front-page-1 img').addClass('animated fadeInLeft');
			$('#front-page-1 p').toggleClass('animated fadeInUp');
		} 
		if (direction == 'up') {
			$('#front-page-1 h1').toggleClass( 'animated fadeOut' );
		}	
		},
		{ 
		offset: '80%',
			
		});	

$('#front-page-2 img').css('opacity', 0);
$('#front-page-2 h1').css('opacity', 0);

$('#front-page-2').waypoint(function() {
			$('#front-page-2 h1').toggleClass( 'animated fadeInUp' );
			$('#front-page-2 img').toggleClass('animated fadeIn');
		},
		{
			offset: '40%',
		});	
	
	
	// hide our element on page load --- WORKS!!
    $('#front-page-3 h1').css('opacity', 0);
	 		
	$('#front-page-3').waypoint(function() {
			$('#front-page-3 h1').toggleClass( 'animated fadeInLeftBig' );
		},
		{
			offset: '40%',
			
		});		
	
$('#front-page-4').waypoint(function() {
		$('#front-page-4 p').toggleClass( 'animated fadeInLeftBig' );
		$('#front-page-4 img').toggleClass( 'animated fadeIn' );
	},
	{
		offset: '80%',
		
	});
	
		

	$('#front-page-7').waypoint(function() {
			$('#front-page-7 p').addClass( 'animated fadeInUpBig' );
		},
		{
			offset: '60%',
		});

});