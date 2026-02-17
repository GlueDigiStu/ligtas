(function( $ ) {
	'use strict';

    $( document ).ready( function() {
        console.log( 'Dotstore Analytics JS Loaded' );
        // Open popup
        $(document).on('click', '.marketing_section .dsmrkt_checkbox', function() {
            if( ! $(this).parent().hasClass('disabled') ) {

                var plugin_id = $(this).data('plugin_id');
                if ( isNaN(plugin_id) || plugin_id < 0 ) {
                    console.error('Invalid or missing plugin_id.');
                    return;
                }
                plugin_id = parseInt(plugin_id);

                var coupon_id = $(this).data('coupon_id');
                if ( isNaN(coupon_id) || coupon_id < 0 ) {
                    console.warn('Invalid coupon_id. Proceeding without a coupon.');
                    coupon_id = null;
                }
                coupon_id = parseInt(coupon_id);

                recordEvent( 'dotstore_popup_open', plugin_id );

                $('.all-pad').block({
                    message: null,
                    overlayCSS: {
                        background: 'rgb(255, 255, 255)',
                        opacity: 0.6,
                    },
                });

                // Before opening popup we need to fill data from APIs
                if( plugin_id ) {

                    var bearer_token = dsmrkt_data.dsmrkt_plugins[plugin_id].bearer_token;

                    $.ajax({
                        url: 'https://api.freemius.com/v1/plugins/' + plugin_id + '/info.json',
                        method: 'GET',
                        headers: {
                            Accept: 'application/json',
                            Authorization: 'Bearer ' + bearer_token,
                        },
                        success: function(info_data) {

                            if( !info_data ) {
                                console.error('No data found for Plugin ' + plugin_id );
                                $('.all-pad').unblock();
                            }
                            let feature_image = info_data.url;
                            if( feature_image ){
                                $('.marketing-modal-main .pro-modal-header img').attr( 'src', feature_image );
                            } else {
                                $('.marketing-modal-main .pro-modal-header img').remove();
                            }
                            if(info_data.short_description){
                                $('.marketing-modal-main .pro-modal-body p').html( info_data.short_description );
                            } else{
                                $('.marketing-modal-main .pro-modal-body p').remove();
                            }

                            // Get Coupon data from API
                            if( coupon_id > 0 ) {
                                
                                $.ajax({
                                    url: 'https://api.freemius.com/v1/plugins/' + plugin_id + '/coupons/' + coupon_id + '.json',
                                    method: 'GET',
                                    headers: {
                                        Accept: 'application/json',
                                        Authorization: 'Bearer ' + bearer_token,
                                    },
                                    success: function(coupon_data) {
                                        if( coupon_data ) {
                                            let discount_number = coupon_data.discount ? coupon_data.discount : 0;
                                            let discount_text = coupon_data.discount_type && 'percentage' === coupon_data.discount_type ? discount_number + '%' : coditional_vars.currency_symbol+''+discount_number;

                                            let offer_title = dsmrkt_data.dsmrkt_offer_text.replace( '{{offer}}', discount_text );
                                            $('.marketing-modal-main .pro-modal-body h3.pro-feature-title').html(offer_title);

                                            // Open popup
                                            $('div[data-plugin_id="'+plugin_id+'"]').addClass('dsmrkt-modal-visible');
                                        } else {
                                            console.warn('No coupon data found for Plugin ' + plugin_id );
                                            $('.all-pad').unblock();
                                        }
                                    },
                                    error: function() {
                                        console.error( 'Error while fetching coupon data of ' + coupon_id );
                                        $('.all-pad').unblock();
                                    }
                                });
                            } else {
                                console.warn('No coupon id found for Plugin ' + plugin_id );
                                // Open popup if coupon not available then we can still show up popup without coupon discount
                                $('div[data-plugin_id="'+plugin_id+'"]').addClass('dsmrkt-modal-visible');
                            }
                        },
                        error: function() {
                            console.error('API Error while fetching plugin info data of ' + plugin_id );
                            $('.all-pad').unblock();
                        }
                    });
                }  else {
                    console.warn('No plugin id found!');
                    $('.all-pad').unblock();
                }
            }
        });

        // Click get now button from popup
        $(document).on('click', '.marketing-modal-main .get-now', function(e) {
            e.preventDefault();
            var plugin_id = $(this).data('plugin_id');
            var coupon_id = $(this).data('coupon_id');

            recordEvent( 'clicked_upgrade_now', plugin_id );

            dotstorePluginMarketing( plugin_id, coupon_id );
            $('div[data-plugin_id="'+plugin_id+'"]').removeClass('dsmrkt-modal-visible');
        });

        // Close popup
        $(document).on('click', '#dotsstoremain .modal-close-btn', function(){
            $('.marketing_section .dsmrkt_checkbox').prop('checked', false);
            var plugin_id = $(this).closest('.marketing-modal-main').data('plugin_id');
            $('div[data-plugin_id="'+plugin_id+'"]').removeClass('dsmrkt-modal-visible');
            $('.all-pad').unblock();
        });

    } );

    /** Script for Freemius upgrade popup */
    function dotstorePluginMarketing( plugin_id, coupon_id ) {

        plugin_id = parseInt(plugin_id);
        if ( isNaN(plugin_id) || plugin_id < 0 ) {
            console.error('Invalid or missing plugin_id.');
            return;
        }

        coupon_id = parseInt(coupon_id);
        if ( isNaN(coupon_id) || coupon_id < 0 ) {
            console.warn('Invalid coupon_id. Proceeding without a coupon.');
            coupon_id = null;
        }

        $('.all-pad').block({
            message: null,
            overlayCSS: {
                background: 'rgb(255, 255, 255)',
                opacity: 0.6,
            },
        });

        var bearer_token = dsmrkt_data.dsmrkt_plugins[plugin_id].bearer_token;

        $.ajax({
            url: 'https://api.freemius.com/v1/plugins/' + plugin_id + '.json',
            method: 'GET',
            headers: {
                Accept: 'application/json',
                Authorization: 'Bearer ' + bearer_token,
            },
            success: function(data) {
                
                if ( !data.public_key ) {
                    console.warn( 'No public_key found for Plugin', plugin_id );
                    $('.all-pad').unblock();
                    return;
                }

                fetchPluginPlans( plugin_id, bearer_token, data.public_key, coupon_id );
            },
            error: function(xhr, status, error) {
                console.error('Failed to fetch plugin details:', error);
                $('.all-pad').unblock();
            }
        });
    }

    function fetchPluginPlans( plugin_id, bearer_token, public_key, coupon_id ) {

        plugin_id = parseInt(plugin_id);
        if ( isNaN(plugin_id) || plugin_id < 0 ) {
            console.error('Invalid or missing plugin_id.');
            return;
        }

        if ( !bearer_token ) {
            console.error('Invalid or missing bearer_token.');
            return;
        }

        if ( !public_key ) {
            console.error('Invalid or missing public_key.');
            return;
        }

        coupon_id = parseInt(coupon_id);
        if ( isNaN(coupon_id) || coupon_id < 0 ) {
            console.warn('Invalid coupon_id. Proceeding without a coupon.');
            coupon_id = null;
        }

        $.ajax({
            url: 'https://api.freemius.com/v1/plugins/' + plugin_id + '/plans.json',
            method: 'GET',
            headers: {
                Accept: 'application/json',
                Authorization: 'Bearer ' + bearer_token,
            },
            success: function(result) {
                
                if ( result.plans.length <= 0) {
                    console.warn('No plans available for Plugin ' + plugin_id );
                    $('.all-pad').unblock();
                    return;
                }

                // Find the first featured plan
                let plan_id = result.plans.find(plan => plan.is_featured)?.id;
    
                if (!plan_id) {
                    console.warn('No featured plan found for Plugin', plugin_id);
                    $('.all-pad').unblock();
                    return;
                }
                
                if( coupon_id ) {
                    fetchCouponDetails( plugin_id, bearer_token, public_key, plan_id, coupon_id );
                } else {
                    callFreemiusCheckout( plugin_id, plan_id, public_key );
                    $('.all-pad').unblock();
                }
            },
            error: function(xhr, status, error) {
                console.error('Plan call error:', status, error);
                $('.all-pad').unblock();
            }
        });
    }

    function fetchCouponDetails( plugin_id, bearer_token, public_key, plan_id, coupon_id ) {

        plugin_id = parseInt(plugin_id);
        if ( isNaN(plugin_id) || plugin_id < 0 ) {
            console.error('Invalid or missing plugin_id.');
            return;
        }

        if ( !bearer_token ) {
            console.error('Invalid or missing bearer_token.');
            return;
        }

        if ( !public_key ) {
            console.error('Invalid or missing public_key.');
            return;
        }

        if ( !plan_id ) {
            console.error('Invalid or missing plan_id.');
            return;
        }

        coupon_id = parseInt(coupon_id);
        if ( isNaN(coupon_id) || coupon_id < 0 ) {
            console.warn('Invalid coupon_id. Proceeding without a coupon.');
            coupon_id = null;
        }

        $.ajax({
            url: 'https://api.freemius.com/v1/plugins/' + plugin_id + '/coupons/' + coupon_id + '.json',
            method: 'GET',
            headers: {
                Accept: 'application/json',
                Authorization: 'Bearer ' + bearer_token,
            },
            success: function(coupon) {
                
                if (!coupon.code) {
                    console.warn('No valid coupon code found.');
                    callFreemiusCheckout( plugin_id, plan_id, public_key );
                    $('.all-pad').unblock();
                }

                callFreemiusCheckout( plugin_id, plan_id, public_key, coupon.code );
                $('.all-pad').unblock();
            },
            error: function() {
                console.error( 'Coupon call error:' + plugin_id );
                $('.all-pad').unblock();
            }
        });
    }

    function callFreemiusCheckout( plugin_id, plan_id, public_key, couponCode ) {
        let handler;
        handler = new FS.Checkout({
            plugin_id: plugin_id,
            plan_id: plan_id,
            public_key: public_key,
            coupon: couponCode,
            hide_coupon: true, // For security reasons, we recommend setting this to true. So no one can know the coupon code.
            show_reviews: true,
            show_refund_badge: true,
            always_show_renewals_amount: true,
        });
        
        handler.open({
            purchaseCompleted: function( response ) {
                console.log ('Completed for plugin ID:', response.purchase.plugin_id);
                recordEvent( 'purchased_completed', parseInt( response.purchase.plugin_id ) );
            },
            success: function (response) {
                console.log (response);
            },
            afterClose: function( ) {
                recordEvent( 'closed_freemius_popup', plugin_id );
                $('.marketing_section .dsmrkt_checkbox').prop('checked', false);
            },
        });
    }

    function recordEvent( event_type, plugin_id ){
        
        if( ! event_type ) {
            console.error('Event type is missing.');
            return;
        }

        $.ajax({
            type: 'POST',
            url: dsmrkt_data.ajaxurl,
            data: {
                'action': 'dsmrkt_'+dsmrkt_data.dsmrkt_prefix+'_dotstore_event_tracking',
                'security': dsmrkt_data.dsmrkt_nonce,
                'event_type': event_type,
                'plugin_id': plugin_id,
                'marketing_plugin_title': dsmrkt_data.dsmrkt_plugins[plugin_id].title,
            },
            success: function( response ) {
                if( response.success ) {
                    console.log( 'Dotstore Analytics', response.data.message );
                } else {
                    console.error( response.data.message );
                    console.log( 'Dotstore Analytics Request Data', response.data.request_parameter );
                }
            }
        });
    }

})( jQuery );