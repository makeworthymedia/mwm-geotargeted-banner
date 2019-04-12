jQuery( document ).ready(function( $ ) {

	// Wrap banner content in DIV for targeting.
	mwmBannerContent = '<div class="geotarget-footer">' + mwmBannerContent + '</div>';
	
	// If the acceptance cookie is set, there is no need to check the IP address
	if ( Cookies.get('mwm_privacy_accepted') == '1') {
		//*console.log('Privacy policy has been accepted');
		return true;
	} else {
		//*console.log('Privacy policy has NOT been accepted');
		
		// Get continent code, either from cookie or the geoplugin.net server. Then display banner if necessary.
		checkContinent();
	}
	
	//*
	// Checks to see if continentCode cookie has ben set. 
	// If not, it sets that cookie. Then displays the banner to specific continents.
	//*
	function checkContinent() {
		if ( Cookies.get('mwm_continent_code') ) {
			//*console.log('ContentCode cookie has already been set');
			
			if ( mwmDisplayContinents.includes(Cookies.get('mwm_continent_code')) ) {
				//*console.log('Displaying banner after checking existing cookie');
				$('.geotarget-footer-container').html(mwmBannerContent);
			} else {
				//*console.log('This continent code does not need to see the privacy policy banner.');
			}
			
			return true;
			
		} else {
			// Continent cookie has NOT been set.
			//*console.log('ContentCode cookie has NOT been previously set');
			
			$.getJSON( mwmGeopluginScript, function( data ) {	

				// Set cookie
				Cookies.set('mwm_continent_code', data['geoplugin_continentCode'], { expires: 0.04 }); // Cookie expires in 1 hour.
				//*console.log('Set mwm_continent_code cookie');
				
				// Display banner if needed. Have to place this here because the getJSON command is run last.
				if ( mwmDisplayContinents.includes(data['geoplugin_continentCode']) ) {
					//*console.log('Displaying banner after querying GeoPlugin');
					$('.geotarget-footer-container').html(mwmBannerContent);
					
					// Assign function to the banner button when it's clicked
					$('.geotarget-footer button').click(function() {
						acceptPolicy();
						//*console.log('Function 2');
					});					
					
				} else {
					//*console.log('This continent code does not need to see the privacy policy banner.');
				}
			});
			
			return true;
		}
		
		return false;
	}
	
	
	// Assign function to the banner button when it's clicked
	$('.geotarget-footer button').click(function() {
		acceptPolicy();
		//*console.log('Function 1');
	});
	
	
	// When banner button is clicked, set mwm_privacy_accepted cookie that prevents banner from being displayed and hide banner
	function acceptPolicy() {
		Cookies.set('mwm_privacy_accepted', '1', { expires: mwmCookieExpiration });	// Expires in 30 days
		//*console.log('Set mwm_privacy_accepted cookie.');
		$('.geotarget-footer').addClass('geotarget-hide');
		//*console.log('Added CSS class to banner to hide it.');
	}
	
	
});

