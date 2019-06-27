/* Начальное кол-во для смены в карточке и корзине */
okay.amount = 1;

/*Аяксовая подгрузка товаров на главной*/
$(window).load(function () {
	var page = 2;
	var limit = 8;
	$('.show_new_product_ajax').click(function (e) {
	    btn_ajax_product = $('.show_new_product_ajax');
	    var block_product = btn_ajax_product.parent().prev();
	    var loader_ajax = btn_ajax_product.children();
	    loader_ajax.css('display' , 'inline-block');
	    btn_ajax_product.prop('disabled', true);
	    $.ajax({
	        url: "ajax/ajax_product_show.php",
	        type: 'GET',
	        data: { 'limit': limit , 'page': page },
	        dataType: 'json',
	        success: function(data) {
	            setTimeout(function() {
	                block_product.append(data);
	                $('.products_item').matchHeight();
	                loader_ajax.attr('style' , '');
	                btn_ajax_product.removeAttr('disabled');
	                var position_block = block_product.offset().top;
	                var block_after_ajax = $('.new_products').height();
	                var height_window = $(window).height();
	                var item_product = $('.products_item').height();
            		$(document).scrollTop( position_block + ( block_after_ajax - height_window - item_product - 200));
	            },1500);
	        }
	    }).done(function(data){
	    	if (data) {
	    		page += 1;
	    	}
	    });
	});
});

/*Проверка на пустоту поля при поиске*/
$(document).ready(function(){
    $('form').on('submit',function (e) {
        var input_search = $($(this).context[0]);
        if (input_search.val().length == 0) {
            input_search.css('border' , '1px solid red').addClass('error_empty_text');
            e.preventDefault();
        }
        input_search.click(function () {
            $(this).attr('style' , '').removeClass('error_empty_text');
        })
    });
});

/*Функция добавление вариантов в корзину при клике на фото вариантов*/

function addVariantProductsCart (object) {
    var variant;
    var variant_name = $(object);
    if ($('.variant_select')) {
        if(variant_name.find('input[name=variant]:checked').size() > 0 ) {
            variant = variant_name.find('input[name=variant]:checked').val();
        } else if(variant_name.find('input[name=variant]').size() > 0 ) {
            variant = variant_name.find('input[name=variant]').val();
        } else if(variant_name.find('select[name=variant]').size() > 0 ) {
            variant = variant_name.find('select[name=variant]').val();
        }
    }

    if ($('.img-variants')) {
        $('.img-variants').each(function () {
            var img_var = $(this);
            var variant_prod = $('.fn_variants');
            var has_class = img_var.hasClass('variant_attachment_img');
            if (has_class == false) {
                if( variant_prod.find('input[name=variant]:checked').size() > 0 ) {
                    variant =  variant_prod.find('input[name=variant]:checked').val();
                }else if( variant_prod.find('select[name=variant]').size() > 0 ) {
                    variant =  variant_prod.find('select[name=variant]').val();
                }
            }
            else if (has_class == true) {
                variant = img_var.next().val();
                return false;
            }
        });
    }
    return variant ? variant : '';
}
/* Аяксовая корзина */
$(document).on('submit', '.fn_variants', function(e) {
    e.preventDefault();
    var variant,
        amount;
    /* Вариант */

    variant = addVariantProductsCart(this);
    
    /* Кол-во */
    if($(this).find('input[name=amount]').size()>0) {
        amount = $(this).find('input[name=amount]').val();
    } else {
        amount = 1;
    }
    /* ajax запрос */
    $.ajax( {
        url: "ajax/cart.php",
        data: {
            variant: variant,
            amount: amount
        },
        dataType: 'json',
        success: function(data) {
            $( '#cart_informer' ).html( data );
        }
    } );
    /* Улеталка */
    transfer( $('#cart_informer'), $(this) );
    /*ok_optimize_button*/
    $(this).find('.product_buttons .fn_is_stock').hide();
    $(this).find('.product_buttons .fn_is_preorder').hide();
    $(this).find('.product_buttons .fn_is_stock_checkout').show();
    /*/ok_optimize_button*/
});

/*Добавление товара в корзину при "Купить сейчас"*/
$('.buy_now').click(function(e) {
    e.preventDefault();
    var variant,
        amount;
    /* Вариант */

    variant = addVariantProductsCart(this);

    /* Кол-во */
    if($('.input_amount').size() > 0) {
        amount = $('.input_amount').val();
    } else {
        amount = 1;
    }
    /* ajax запрос */
    $.ajax( {
        url: "ajax/cart.php",
        data: {
            variant: variant,
            amount: amount
        },
        dataType: 'json',
        success: function(data) {
            $( '#cart_informer' ).html( data );
        }
    } );
    /* Улеталка */
    transfer( $('#cart_informer'), $(this) );
});

/*Выбор вариантов*/
$(window).load (function () {
    if($('.img-variants').first()) {
        $('.img-variants').first().addClass('variant_attachment_img');

    }
    $('.img-variants').click(function () {
        var img = $(this);
        var src_big = img.data("big-attachment"); //28.02.19
        var src_prev = img.data("attachment"); //28.02.19
        var prod_img = $('.product_image').children(); //28.02.19
        prod_img.attr("href", src_big); //28.02.19
        prod_img.children().attr("src", src_prev); //28.02.19
        
        img.toggleClass('variant_attachment_img');
        $('.fn_sku').text(img.data('sku'));
        $('.fn_price').text(img.data('price-attachment'));
        $('.img-variants').each(function () {
            if (img.hasClass('variant_attachment_img')) {
                if ($('.old_price').hasClass('hidden')) {
                    $('.old_price').removeClass('hidden');
                    $('.img-variants').removeClass('variant_attachment_img');
                }
                
                $('.fn_old_price').text(img.data('cprice'));
                $('.img-variants').removeClass('variant_attachment_img');
                img.addClass('variant_attachment_img');
            }
            else {
                $('.old_price').addClass('hidden');
            }
        });
    });
});

/* Смена варианта в превью товара и в карточке */
$(document).on('change click', '.fn_variant', function() {
    $('.img-variants').removeClass('variant_attachment_img');
    var selected = $( this ).children( ':selected' ),
        parent = selected.closest( '.fn_product' ),
        price = parent.find( '.fn_price' ),
        cprice = parent.find( '.fn_old_price' ),
        sku = parent.find( '.fn_sku' ),
        stock = parseInt( selected.data( 'stock' ) ),
        amount = parent.find( 'input[name="amount"]' ),
        camoun = parseInt( amount.val()),
        units = selected.data('units');
    price.html( selected.data( 'price' ) );
    amount.data('max', stock);

    /* Количество товаров */
    if ( stock < camoun ) {
        amount.val( stock );
    } else if ( okay.amount > camoun ) {
        amount.val( okay.amount );
    }
    else if(isNaN(camoun)){
        amount.val( okay.amount );
    }
    /* Цены */
    if( selected.data( 'cprice' ) ) {
        cprice.html( selected.data( 'cprice' ) );
        cprice.parent().removeClass( 'hidden' );
    } else {
        cprice.parent().addClass( 'hidden' );
    }
    /* Артикул */
    if( typeof(selected.data( 'sku' )) != 'undefined' ) {
        sku.text( selected.data( 'sku' ) );
        sku.parent().removeClass( 'hidden' );
    } else {
        sku.text( '' );
        sku.parent().addClass( 'hidden' );
    }
    /* Наличие на складе */
    if (stock == 0) {
        parent.find('.fn_not_stock').removeClass('hidden');
        parent.find('.fn_in_stock').addClass('hidden');
    } else {
        parent.find('.fn_in_stock').removeClass('hidden');
        parent.find('.fn_not_stock').addClass('hidden');
    }
    /* Предзаказ */
    if (stock == 0 && okay.is_preorder) {
        parent.find('.fn_is_preorder').removeClass('hidden');
        parent.find('.fn_is_stock, .fn_not_preorder').addClass('hidden');
    } else if (stock == 0 && !okay.is_preorder) {
        parent.find('.fn_not_preorder').removeClass('hidden');
        parent.find('.fn_is_stock, .fn_is_preorder').addClass('hidden');
    } else {
        parent.find('.fn_is_stock').removeClass('hidden');
        parent.find('.fn_is_preorder, .fn_not_preorder').addClass('hidden');
    }

    if( typeof(units) != 'undefined' ) {
        parent.find('.fn_units').text(', ' + units);
    } else {
        parent.find('.fn_units').text('');
    }
});

/* Количество товара в карточке и корзине */
$( document ).on( 'click', '.fn_product_amount span', function() {
    var input = $( this ).parent().find( 'input' ),
        action;
    if ( $( this ).hasClass( 'plus' ) ) {
        action = 'plus';
    } else if ( $( this ).hasClass( 'minus' ) ) {
        action = 'minus';
    }
    amount_change( input, action );
} );

/* Функция добавления / удаления в папку сравнения */
$(document).on('click', '.fn_comparison', function(e){
    e.preventDefault();
    var button = $( this ),
        action = $( this ).hasClass( 'selected' ) ? 'delete' : 'add',
        product = parseInt( $( this ).data( 'id' ) );
    /* ajax запрос */
    $.ajax( {
        url: "ajax/comparison.php",
        data: { product: product, action: action },
        dataType: 'json',
        success: function(data) {
            $( '#comparison' ).html( data );
            /* Смена класса кнопки */
            if( action == 'add' ) {
                button.addClass( 'selected' );
            } else if( action == 'delete' ) {
                button.removeClass( 'selected' );
            }
            /* Смена тайтла */
            if( button.attr( 'title' ) ) {
                var text = button.data( 'result-text' ),
                    title = button.attr( 'title' );
                button.data( 'result-text', title );
                button.attr( 'title', text );
            }
            /* Если находимся на странице сравнения - перезагрузить */
            if( $( '.fn_comparison_products' ).size() ) {
                window.location = window.location;
            }
        }
    } );
    /* Улеталка */
    if( !button.hasClass( 'selected' ) ) {
        transfer( $( '#comparison' ), $( this ) );
    }
});

/* Функция добавления / удаления в папку избранного */
$(document).on('click', '.fn_wishlist', function(e){
    e.preventDefault();
    var button = $( this ),
        action = $( this ).hasClass( 'selected' ) ? 'delete' : '';
    /* ajax запрос */
    $.ajax( {
        url: "ajax/wishlist.php",
        data: { id: $( this ).data( 'id' ), action: action },
        dataType: 'json',
        success: function(data) {
            $( '#wishlist' ).html( data.info );
            /* Смена класса кнопки */
            if (action == '') {
                button.addClass( 'selected' );
            } else {
                button.removeClass( 'selected' );
            }
            /* Смена тайтла */
            if( button.attr( 'title' ) ) {
                var text = button.data( 'result-text' ),
                    title = button.attr( 'title' );
                button.data( 'result-text', title );
                button.attr( 'title', text );
            }
            /* Если находимся на странице сравнения - перезагрузить */
            if( $( '.fn_wishlist_page' ).size() ) {
                window.location = window.location;
            }
        }
    } );
    /* Улеталка */
    if( !button.hasClass( 'selected' ) ) {
        transfer( $( '#wishlist' ), $( this ) );
    }
});

/* Отправка купона по нажатию на enter */
$( document ).on( 'keypress', '.fn_coupon', function(e) {
    if( e.keyCode == 13 ) {
        e.preventDefault();
        ajax_coupon();
    }
} );

/* Отправка купона по нажатию на кнопку */
$( document ).on( 'click', '.fn_sub_coupon', function(e) {
    ajax_coupon();
} );

function change_currency(currency_id) {
    $.ajax({
        url: "ajax/change_currency.php",
        data: {currency_id: currency_id},
        dataType: 'json',
        success: function(data) {
            if (data == true) {
                document.location.reload()
            }
        }
    });
    return false;
}

/* Document ready */
$(function(){

    $(document).on("click", ".fn_menu_toggle", function() {
        $(this).next(".fn_menu_list").first().slideToggle(300);
        return false;
    });

    $(document).on("click", ".fn_filter_link", function() {
        location.href = location.protocol + "//" + location.hostname + $(this).attr("href");
        return false;
    });

    $(function(){
        $(window).scroll(function() {
            var screen = $(document);
            if (screen.scrollTop() < 140) {
                $(".header_bottom").removeClass('fixed');
            } else {
                $(".header_bottom").addClass('fixed');
            }
        });
    });

    /* Обратный звонок */
    $('.fn_callback').fancybox();

    /*Купить сейчас*/
    $('.buy_now').fancybox();


    // Выпадающие блоки
    $('.fn_switch').click(function(e){
        e.preventDefault();

        $(this).next().slideToggle(300);

        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        }
        else {
            $(this).addClass('active');
        }
    });

    // Эмуляция наведения мыши на планшете и мобильном
    $('ul li.category_item.has_child').on("touchstart", function (e) {
        'use strict'; //satisfy code inspectors
        var link = $(this);
        if (link.hasClass('hover')) {
            return true;
        } else {
            if (link.parent().is('.level_1')) {
                $('ul li.category_item.has_child').removeClass('hover');
            } else {
                link.siblings('.hover').removeClass('hover');
            }
            link.addClass('hover');
            e.preventDefault();
            return false;
        }
    });

     /* Главное меню для мобильных */
    $('.fn_menu_switch').on("click", function(){
        $('.menu').toggle("normal");
        $('body').toggleClass('openmenu');
    })

    //Фильтры мобильные, каталог мобильные
    $('.subswitch').click(function(){
        $(this).parent().next().slideToggle(500);

        if ($(this).hasClass('down')) {
            $(this).removeClass('down');
        }
        else {
            $(this).addClass('down');
        }
    });
    $('.catalog_menu .selected').parents('.parent').addClass('opened').find('> .switch').addClass('active');



    //Табы в карточке товара
    var nav = $('.tabs').find('.tab_navigation');
    var tabs = $('.tabs').find('.tab_container');

    if(nav.children('.selected').size() > 0) {
        $(nav.children('.selected').attr("href")).show();
    } else {
        nav.children().first().addClass('selected');
        tabs.children().first().show();
    }

    $('.tab_navigation a').click(function(e){
        e.preventDefault();
        if($(this).hasClass('selected')){
            return true;
        }
        tabs.children().hide();
        nav.children().removeClass('selected');
        $(this).addClass('selected');
        $($(this).attr("href")).fadeIn(200);
    });

    //Кнопка вверх
    $(window).scroll(function () {
    var scroll_height = $(window).height();

     if ($(this).scrollTop() >= scroll_height) {
            $('.to_top').fadeIn();
        } else {
            $('.to_top').fadeOut();
        }
    });

    $('.to_top').click(function(){
        $("html, body").animate({scrollTop: 0}, 500);
    });


    // Проверка полей на пустоту для плейсхолдера
    $('.placeholder_focus').blur(function() {
        if( $(this).val().trim().length > 0 ) {
            $(this).next().next().addClass('active');
        } else {
            $(this).next().removeClass('active');
        }
    });

    $('.form_placeholder').click(function(){
        $(this).prev().focus();
    });

    $('.placeholder_focus').each(function() {
        if( $(this).val().trim().length > 0 ) {
            $(this).next().addClass('active');
        }
    });


    /* Инициализация баннера */
    $('.fn_banner_group1').slick({
        infinite: true,
        speed: 1000,
        slidesToShow: 1,
        slidesToScroll: 1,
        swipeToSlide : true,
        dots: true,
        arrows: false,
        adaptiveHeight: true,
        autoplaySpeed: 5000,
        autoplay: true,
        fade: true
    });


    /* Бренды слайдер*/
    $(".fn_all_brands").slick({
        infinite: true,
        speed: 500,
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: true,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 420,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });

    /* Инициализация доп. фото в карточке */
    $(".fn_images").slick({
        infinite: false,
        speed: 500,
        slidesToShow: 6,
        slidesToScroll: 1,
        swipeToSlide : true,
        arrows: true,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 5
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 4
                }
            },
            {
                breakpoint: 481,
                settings: {
                    slidesToShow: 3
                }
            }
        ]
    });


    $('.blog_item').matchHeight();
    $('.news_item').matchHeight();
    $('.brand_item').matchHeight();
    $('.products_item').matchHeight();
    $('.fn_col').matchHeight();


    /* Зум картинок в карточке */
    $('[data-fancybox]').fancybox({
        image : {
            protect: true
        }
    });
    
    $.fancybox.defaults.hash = false;

    /* Аяксовый фильтр по цене */
    if( $( '#fn_slider_price' ).size() ) {
        var slider_all = $( '#fn_slider_min, #fn_slider_max' ),
            slider_min = $( '#fn_slider_min' ),
            slider_max = $( '#fn_slider_max' ),
            current_min = slider_min.val(),
            current_max = slider_max.val(),
            range_min = slider_min.data( 'price' ),
            range_max = slider_max.data( 'price' ),
            link = window.location.href.replace( /\/page-(\d{1,5})/, '' ),
            ajax_slider = function() {
                $.ajax( {
                    url: link,
                    data: {
                        ajax: 1,
                        'p[min]': slider_min.val(),
                        'p[max]': slider_max.val()
                    },
                    dataType: 'json',
                    success: function(data) {
                        $( '#fn_products_content' ).html( data.products_content );
                        $( '.fn_pagination' ).html( data.products_pagination );
                        $('.fn_products_sort').html(data.products_sort);

                        $('.fn_ajax_wait').remove();
                    }
                } );
            };
        link = link.replace(/\/sort-([a-zA-Z_]+)/, '');

        $( '#fn_slider_price' ).slider( {
            range: true,
            min: range_min,
            max: range_max,
            values: [current_min, current_max],
            slide: function(event, ui) {
                slider_min.val( ui.values[0] );
                slider_max.val( ui.values[1] );
            },
            stop: function(event, ui) {
                slider_min.val( ui.values[0] );
                slider_max.val( ui.values[1] );
                $('.fn_categories').append('<div class="fn_ajax_wait"></div>');
                ajax_slider();
            }
        } );

        slider_all.on( 'change', function() {
            $( "#fn_slider_price" ).slider( 'option', 'values', [slider_min.val(), slider_max.val()] );
            ajax_slider();
        } );

        // Если после фильтрации у нас осталось товаров на несколько страниц, то постраничную навигацию мы тоже проведем с помощью ajax чтоб не сбить фильтр по цене
        $( document ).on( 'click', '.fn_is_ajax a', function(e) {
            e.preventDefault();
            $('.fn_categories').append('<div class="fn_ajax_wait"></div>');
            var link = $(this).attr( 'href' ),
                send_min = $("#fn_slider_min").val();
                send_max = $("#fn_slider_max").val();
            $.ajax( {
                url: link,
                data: { ajax: 1, 'p[min]': send_min, 'p[max]': send_max },
                dataType: 'json',
                success: function(data) {
                    $( '#fn_products_content' ).html( data.products_content );
                    $( '.fn_pagination' ).html( data.products_pagination );
                    $('.fn_products_sort').html(data.products_sort);

                    $('.fn_ajax_wait').remove();
                }
            } );
        } );
    }

    /* Автозаполнитель поиска */
    $( ".fn_search" ).autocomplete( {
        serviceUrl: 'ajax/search_products.php',
        minChars: 1,
        noCache: true,
        onSelect: function(suggestion) {
            $( "#fn_search" ).submit();
        },
        transformResult: function(result, query) {
            var data = JSON.parse(result);
            $(".fn_search").autocomplete('setOptions', {triggerSelectOnValidInput: data.suggestions.length == 1});
            return data;
        },
        formatResult: function(suggestion, currentValue) {
            var reEscape = new RegExp( '(\\' + ['/', '.', '*', '+', '?', '|', '(', ')', '[', ']', '{', '}', '\\'].join( '|\\' ) + ')', 'g' );
            var pattern = '(' + currentValue.replace( reEscape, '\\$1' ) + ')';
            return "<div>" + (suggestion.data.image ? "<img align=absmiddle src='" + suggestion.data.image + "'> " : '') + "</div>" + "<a href=" + suggestion.lang + "products/" + suggestion.data.url + '>' + suggestion.value.replace( new RegExp( pattern, 'gi' ), '<strong>$1<\/strong>' ) + '<\/a>' + "<span>" + suggestion.price + " " + suggestion.currency + "</span>";
        }
    } );

    /* Слайдер в сравнении */
    if( $( '.fn_comparison_products' ).size() ) {
            $( '.fn_comparison_products' ).slick( {
                infinite: true,
                slidesToShow: 3,
                slidesToScroll: 1,
                arrows: true,
                responsive: [
                    {
                      breakpoint: 1200,
                      settings: {
                        slidesToShow: 2,
                      }
                    },
                    {
                      breakpoint: 992,
                      settings: {
                        slidesToShow: 1
                      }
                    }
                ]
            } );

        resize_comparison();

        /* Показать / скрыть одинаковые характеристики в сравнении */
        $( document ).on( 'click', '.fn_show a', function(e) {
            e.preventDefault();
            $( '.fn_show a.active' ).removeClass( 'active' );
            $( this ).addClass( 'active' );
            if( $( this ).hasClass( 'unique' ) ) {
                $( '.cell.not_unique' ).hide();
            } else {
                $( '.cell.not_unique' ).show();
            }
        } );
    };
    /* Рейтинг товара */
    $('.product_rating').rater({ postHref: 'ajax/rating.php' });

    /* Переключатель способа оплаты */
    $( document ).on( 'click', '[name="payment_method_id"]', function() {
        $( '[name="payment_method_id"]' ).parent().removeClass( 'active' );
        $( this ).parent().addClass( 'active' );
    } );
});


/* Обновление блоков: cart_informer, cart_purchases, cart_deliveries */
function ajax_set_result(data) {
    $( '#cart_informer' ).html( data.cart_informer );
    $( '#fn_purchases' ).html( data.cart_purchases );
    $( '#fn_ajax_deliveries' ).html( data.cart_deliveries );
}

/* Аяксовое изменение кол-ва товаров в корзине */
function ajax_change_amount(object, variant_id) {
    var amount = $( object ).val(),
        coupon_code = $( 'input[name="coupon_code"]' ).val(),
        delivery_id = $( 'input[name="delivery_id"]:checked' ).val(),
        payment_id = $( 'input[name="payment_method_id"]:checked' ).val();
    /* ajax запрос */
    $.ajax( {
        url: 'ajax/cart_ajax.php',
        data: {
            coupon_code: coupon_code,
            action: 'update_citem',
            variant_id: variant_id,
            amount: amount
        },
        dataType: 'json',
        success: function(data) {
            if( data.result == 1 ) {
                ajax_set_result( data );
                $( '#deliveries_' + delivery_id ).trigger( 'click' );
                $( '#payment_' + delivery_id + '_' + payment_id ).trigger( 'click' );
            } else {
                $( '#cart_informer' ).html( data.cart_informer );
                $(".fn_ajax_content").html( data.content );
            }
        }
    } );
}

/* Функция изменения количества товаров */
function amount_change(input, action) {
    var max_val,
        curr_val = parseFloat( input.val() ),
        step = 1,
        id = input.data('id');
        if(isNaN(curr_val)){
            curr_val = okay.amount;
        }

    /* Если включен предзаказ макс. кол-во товаров ставим максимально количество товаров в заказе */
    if ( input.parent().hasClass('fn_is_preorder')) {
        max_val = okay.max_order_amount;
    } else {
        max_val = parseFloat( input.data( 'max' ) );
    }
    /* Изменение кол-ва товара */
    if( action == 'plus' ) {
        input.val( Math.min( max_val, Math.max( 1, curr_val + step ) ) );
        input.trigger('change');
    } else if( action == 'minus' ) {
        input.val( Math.min( max_val, Math.max( 1, (curr_val - step) ) ) );
        input.trigger('change');
    } else if( action == 'keyup' ) {
        input.val( Math.min( max_val, Math.max( 1, curr_val ) ) );
        input.trigger('change');
    }
    okay.amount = parseInt( input.val() );
    /* в корзине */
    if( $('div').is('#fn_purchases') && ( (max_val != curr_val && action == 'plus' ) || ( curr_val != 1 && action == 'minus' ) ) ) {
        ajax_change_amount( input, id );
    }
}

/* Функция анимации добавления товара в корзину */
function transfer(informer, thisEl) {
    var o1 = thisEl.offset(),
        o2 = informer.offset(),
        dx = o1.left - o2.left,
        dy = o1.top - o2.top,
        distance = Math.sqrt(dx * dx + dy * dy);

    thisEl.closest( '.fn_transfer' ).find( '.fn_img' ).effect( "transfer", {
        to: informer,
        className: "transfer_class"
    }, distance );

    var container = $( '.transfer_class' );
    container.html( thisEl.closest( '.fn_transfer' ).find( '.fn_img' ).parent().html() );
    container.find( '*' ).css( 'display', 'none' );
    container.find( '.fn_img' ).css( {
        'display': 'block',
        'height': '100%',
        'z-index': '2',
        'position': 'relative'
    } );
}

/* Аяксовый купон */
function ajax_coupon() {
    var coupon_code = $('input[name="coupon_code"]').val(),
        delivery_id = $('input[name="delivery_id"]:checked').val(),
        payment_id = $('input[name="payment_method_id"]:checked').val();
    /* ajax запрос */
    $.ajax( {
        url: 'ajax/cart_ajax.php',
        data: {
            coupon_code: coupon_code,
            action: 'coupon_apply'
        },
        dataType: 'json',
        success: function(data) {
            if( data.result == 1 ) {
                ajax_set_result( data );
                $( '#deliveries_' + delivery_id ).trigger( 'click' );
                $( '#payment_' + delivery_id + '_' + payment_id ).trigger( 'click' );
            } else {
                $( '#cart_informer' ).html( data.cart_informer );
                $(".fn_ajax_content").html( data.content );
            }
        }
    } );
}

/* Изменение способа доставки */
function change_payment_method($id) {
    $( "#fn_delivery_payment_" + $id + " [name='payment_method_id']" ).first().trigger('click');
    $( ".fn_delivery_payment" ).hide();
    $( "#fn_delivery_payment_" + $id ).show();
    $( 'input[name="delivery_id"]' ).parent().removeClass( 'active' );
    $( '#deliveries_' + $id ).parent().addClass( 'active' );
}

/* Аяксовое удаление товаров в корзине */
function ajax_remove(variant_id) {
    var coupon_code = $('input[name="coupon_code"]').val(),
        delivery_id = $('input[name="delivery_id"]:checked').val(),
        payment_id = $('input[name="payment_method_id"]:checked').val();
    /* ajax запрос */
    $.ajax( {
        url: 'ajax/cart_ajax.php',
        data: {
            coupon_code: coupon_code,
            action: 'remove_citem',
            variant_id: variant_id
        },
        dataType: 'json',
        success: function(data) {
            if( data.result == 1 ) {
                ajax_set_result( data );
                $( '#deliveries_' + delivery_id ).trigger( 'click' );
                $( '#payment_' + delivery_id + '_' + payment_id ).trigger( 'click' );
            } else {
                $( '#cart_informer' ).html( data.cart_informer );
                $(".fn_ajax_content").html( data.content );
            }
        }
    } );
}

/* Формирование ровных строчек для характеристик */
function resize_comparison() {
    var minHeightHead = 0;
    $('.fn_resize' ).each(function(){
        if( $(this ).height() > minHeightHead ) {
            minHeightHead = $(this ).height();
        }
    });
    $('.fn_resize' ).height(minHeightHead);
    if ($('[data-use]').size()) {
        $('[data-use]').each(function () {
            var use = '.' + $(this).data('use');
            var minHeight = $(this).height();
            if ($(use).size()) {
                $(use).each(function () {
                    if ($(this).height() >= minHeight) {
                        minHeight = $(this).height();
                    }
                });
                $(use).height(minHeight);
            }
        });
    }
}

/* В сравнении выравниваем строки */
$( window ).load( resize_comparison );

/* Звёздный рейтинг товаров */
$.fn.rater = function (options) {
    var opts = $.extend({}, $.fn.rater.defaults, options);
    return this.each(function () {
        var $this = $(this);
        var $on = $this.find('.rating_starOn');
        var $off = $this.find('.rating_starOff');
        opts.size = $on.height();
        if (opts.rating == undefined) opts.rating = $on.width() / opts.size;

        $off.mousemove(function (e) {
            var left = e.clientX - $off.offset().left;
            var width = $off.width() - ($off.width() - left);
            width = Math.ceil(width / (opts.size / opts.step)) * opts.size / opts.step;
            $on.width(width);
        }).hover(function (e) { $on.addClass('rating_starHover'); }, function (e) {
            $on.removeClass('rating_starHover'); $on.width(opts.rating * opts.size);
        }).click(function (e) {
            var r = Math.round($on.width() / $off.width() * (opts.units * opts.step)) / opts.step;
            $off.unbind('click').unbind('mousemove').unbind('mouseenter').unbind('mouseleave');
            $off.css('cursor', 'default'); $on.css('cursor', 'default');
            opts.id = $this.attr('id');
            $.fn.rater.rate($this, opts, r);
        }).css('cursor', 'pointer'); $on.css('cursor', 'pointer');
    });
};

$.fn.rater.defaults = {
    postHref: location.href,
    units: 5,
    step: 1
};

$.fn.rater.rate = function ($this, opts, rating) {
    var $on = $this.find('.rating_starOn');
    var $off = $this.find('.rating_starOff');
    $off.fadeTo(600, 0.4, function () {
        $.ajax({
            url: opts.postHref,
            type: "POST",
            data: 'id=' + opts.id + '&rating=' + rating,
            complete: function (req) {
                if (req.status == 200) { /* success */
                    opts.rating = parseFloat(req.responseText);

                    if (opts.rating > 0) {
                        opts.rating = parseFloat(req.responseText);
                        $off.fadeTo(200, 0.1, function () {
                            $on.removeClass('rating_starHover').width(opts.rating * opts.size);
                            var $count = $this.find('.rating_count');
                            $count.text(parseInt($count.text()) + 1);
                            $this.find('.rating_value').text(opts.rating.toFixed(1));
                            $off.fadeTo(200, 1);
                        });
                    }
                    else
                    if (opts.rating == -1) {
                        $off.fadeTo(200, 0.6, function () {
                            $this.find('.rating_text').text('Ошибка');
                        });
                    }
                    else {
                        $off.fadeTo(200, 0.6, function () {
                            $this.find('.rating_text').text('Вы уже голосовали!');
                        });
                    }
                } else { /* failure */
                    alert(req.responseText);
                    $on.removeClass('rating_starHover').width(opts.rating * opts.size);
                    $this.rater(opts);
                    $off.fadeTo(2200, 1);
                }
            }
        });
    });
};
    
// Добавлено Z_Brothers

'use strict';
class zipcode_assigner{
    constructor() {
        //console.log('const');
        this.init();
        this.lastValue = '';
        this.lastAddress = '';
    }
    init() {
        //console.log('init');
        let _this = this;
        if (document.readyState||document.body.readyState=='complete') {
            setTimeout( function() { _this.input_init(); }, 3000 );
        } else {
            setTimeout( function() { _this.init(); }, 3000 );
        }
    }
    input_init() {
        //console.log('in init');
        let inps = document.getElementsByClassName('placeholder_focus');
        let i = 0;
        while (inps[i]) {
            let cata = inps[i];
            //console.log(cata);
            if (cata.getAttribute('name') == 'address') { this.register_input(cata); break; }
        i++;
        }
    }
    register_input(inp) {
        //console.log('register');
        this.input = inp;
        this.lastValue = this.input.value;
        this.bg = this.input.parentNode;
        let _this = this;
        this.input.addEventListener('change', function() { _this.on_change(); });
        this.input.addEventListener('input', function() { _this.on_input(); });
    }
    on_input() {
        let _this = this;
        this.lastValue = this.input.value;
        clearTimeout(this.input_timer);
        this.inputTimer = setTimeout( function() { _this.onInputTimed(); }, 2000);
    }
    on_change() {
        this.lastValue = this.input.value;
        this.onInputTimed();
    }
    onInputTimed() {
        if ((this.lastAddress != this.lastValue) && (this.lastValue != '')) this.send();
    }
    responseObject() { // создает объект Ajax-зпросов
        let xmlhttp;
        try {
            xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (E) {
                xmlhttp = false;
            }
        }
        if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
            xmlhttp = new XMLHttpRequest();
        }
        this.req = xmlhttp;
    }
    send() { // отправка запроса
        let _this = this;
        this.responseObject();
        this.lostTimer = setTimeout(function() { _this.lostTime(); } , _this.waitTime);
        this.req.onreadystatechange = function() { _this.stateChange(); };
        let q0 = 'https://maps.googleapis.com/maps/api/geocode/json?address=';
        let q1 = encodeURIComponent(this.lastValue);
        let q2 = '';
        q2 = '&language=en';
        let q3 = '&key=AIzaSyBVFFSMbFAaxyzLM4SbbqleYWHZkCAXmPg';
      
      
        this.req.open("GET", q0+q1+q2+q3, true); 
        this.req.send(null);
        this.status = 1;
    }
    stateChange() {
        if (this.req.readyState == 4) {
            if (this.req.status == 200) {
                clearTimeout(this.lostTimer); // если все ок, ответ пришел - убираем таймер потери соединения.
                this.response(this.req.responseText);
            } else {
                this.error();
            }
        }   
    }
    response(data) { // пришел ответ
        let resAgent = JSON.parse(data);
        let status = resAgent.status;
        // status == 'OK' - good; status == 'ZERO_RESULTS' - гугл не знает таких мест; все остальные значения говорят об ошибках, о которых юзеру знать не надо.
        let results = resAgent.results; // массив, обычно с одним адресом. Но может быть и несколько, если ввод неоднозначен.
        this.open(results, status);
    }
    error() { // случилась ошибка
        //if (this.callbacks[this.status - 2]) this.callbacks[this.status - 2](this.req.status);
    }
    lostTime() { // сервер google не отвечает / нет соединения с интернетом.
        //if (this.callbacks[this.status - 2]) this.callbacks[this.status - 2]();
    }
    open(results, status) {
        let ibcr = this.input.getBoundingClientRect();
        let _this = this;

        if (!this.openList) {
            this.openList = document.createElement('div');
            this.input.insertAdjacentElement("afterEnd", this.openList);
            this.openList.style.left = '0px';
            this.openList.style.top = '5px';
            //this.openList.style.width = (ibcr.width) + 'px';
            this.openList.style.maxHeight = (ibcr.width) + 'px';
        }
        this.clearChilds(this.openList);
        this.openList.className = 'zipcodeList';
        if (status == 'OK') {
            for (let i = 0; i < results.length; i++) {
                let variant = results[i];
                let variant_text = variant.formatted_address;
                let adrItem = document.createElement('div');
                this.openList.appendChild(adrItem);
                adrItem.className = 'zipcodeItem';
                adrItem.innerHTML = variant_text;
                adrItem.addEventListener('mousedown', function(ev) {
                    let a = variant_text;
                    setTimeout(function() { _this.setAdress(a); }, 20);
                }, true);
                adrItem.addEventListener('touchend', function(ev) {
                    let a = variant_text;
                    setTimeout(function() { _this.setAdress(a); }, 20);
                }, true);
            }
        } else {
            let adrNoItem = document.createElement('div');
            this.openList.appendChild(adrNoItem);
            adrNoItem.className = 'zipcodeBadResults';
            adrNoItem.innerHTML = 'Try again';
        }
    }
    clearChilds(myNode) {
        while (myNode.firstChild) {
            myNode.removeChild(myNode.firstChild);
        }
    }
    setAdress(adr) {
        this.clearChilds(this.openList);
        this.lastAddress = adr;
        this.lastValue = adr;
        this.input.value = adr;
    }
}
okay.zipcode_controller = new zipcode_assigner();