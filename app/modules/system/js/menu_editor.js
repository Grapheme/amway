
var menu_items = $('.dd.menu-list');

var menu_editor = {

    'show_hide_info': function() {
        var info = $('.menu_list_info');
        if ($(menu_items).find('li').length)
            info.hide();
        else
            info.show();
    },

    'update_output': function() {
        updateOutput($(menu_items));
        menu_editor.show_hide_info();
    },

    'show_menu': function(order, items) {
        //console.log(order);
        //console.log(items);

        var $this = this;
        //var menu = '';
        var menu = $this.get_menu_list(order, items);

        //$('.dd').append(menu);
        $(menu_items).find('ol:first').append(menu);
        $this.update_output();
        showHideAllActiveRegExp();
    },


    'add_menu_item': function(type, params) {
        //alert(type);
        //console.log(params);

        var $this = this;
        params.type = type;

        var new_menu_item = $this.get_menu_list_item(params);
        //alert(new_menu_item);

        $(menu_items).find('ol:first').append(new_menu_item);
        $this.update_output();
    },


    'get_menu_list': function(order, items) {

        var $this = this;
        var menu_list = '';

        /**
         * Each item of the list
         */
        $.each(order, function(i, val) {

            /**
             *
             * Childrens
             */
            var inner_list = '';
            if (val.children) {
                inner_list = $this.get_menu_list(val.children, items);
            }
            //console.log(inner_list);

            /**
             * Menu item with childrens list
             */
            menu_item = $this.get_menu_list_item(items[val.id], inner_list);
            //console.log(menu_item);

            if (menu_item)
                menu_list += menu_item;
        });

        return menu_list;
    },

    'get_menu_list_item': function(params, inner_list) {
        if (typeof params == 'undefined')
            return false;
        //console.log(params);
        var type = params.type;
        var main_block = $('#templates .main').html();
        var block = $('#templates .' + type).html();
        if (!N) {

            //var N = $(menu_items).find('.dd-item').length;
            var temp = 0;
            $(menu_items).find('.dd-item').each(function(){
                var tmp = parseInt($(this).attr('data-id'), 10);
                if (tmp > 0 && tmp > temp)
                    temp = tmp;
            });
            var N = temp;
        }
        //alert(N);
        params.text = htmlentities(params.text);
        switch (type) {
            case 'page':
                var title = params.text;
                var mark = 'Страница';
                block = str_replace('%page_id%', params.page_id, block);
                block = str_replace('++page_id++', params.page_id, block);
                block = str_replace('%text%', params.text, block);
                break;
            case 'link':
                var title = params.text || params.url;
                var mark = 'Ссылка';
                block = str_replace('%url%', params.url, block);
                block = str_replace('%text%', params.text, block);
                break;
            case 'route':
                var title = params.text || params.route_name;
                var mark = 'Маршрут';
                block = str_replace('%route_name%', params.route_name, block);
                block = str_replace('%route_params%', params.route_params, block);
                block = str_replace('%text%', params.text || '', block);
                break;
            case 'function':
                var title = params.text || '<без названия>';
                var mark = 'Функция';
                block = str_replace('%function_name%', params.function_name, block);
                block = str_replace('%text%', params.text, block);
                block = str_replace('%use_function_data%', params.use_function_data ? 'checked' : '', block);
                block = str_replace('%as_is%', params.as_is ? 'checked' : '', block);
                break;
            default:
                break;
        }
        main_block = str_replace('%title%', title || '', main_block);
        main_block = str_replace('%mark%', mark || '', main_block);

        main_block = str_replace('%inner%', block, main_block);
        main_block = str_replace('%N%', params.id || N+1, main_block);
        main_block = str_replace('%attr_title%', params.title || '', main_block);

        main_block = str_replace('%target_blank%', params.target == '_blank' ? 'checked' : '', main_block);
        main_block = str_replace('%is_hidden%', params.hidden == '1' ? 'checked' : '', main_block);

        main_block = str_replace('%use_active_regexp%', params.use_active_regexp == '1' ? 'checked' : '', main_block);
        main_block = str_replace('%active_regexp%', typeof params.active_regexp != 'undefined' ? params.active_regexp : '', main_block);

        main_block = str_replace('%use_active_hierarchy%', params.use_active_hierarchy == '1' ? 'checked' : '', main_block);

        main_block = str_replace('%use_display_logic%', params.use_display_logic == '1' ? 'checked' : '', main_block);
        main_block = str_replace('%display_logic%', typeof params.display_logic != 'undefined' ? params.display_logic : '', main_block);

        main_block = str_replace('%li_class%', typeof params.li_class != 'undefined' ? params.li_class : '', main_block);

        var inner_list_block = '';
        if (inner_list) {
            inner_list_block = $('#templates .childrens').html();
            inner_list_block = str_replace('%block%', inner_list, inner_list_block);
        }
        main_block = str_replace('%childrens%', inner_list_block || '', main_block);

        return main_block;
    }

}


/**
 * PAGE
 */
$(document).on("click", ".add_to_menu.add_to_menu_page", function(e) {
    e.preventDefault();
    var page_id = $('[name=page_id]').val();
    var text = $('[name=page_id] :selected').text();

    if (!page_id)
        return false;

    menu_editor.add_menu_item('page', {'page_id': page_id, 'text': text});
    return false;
});


/**
 * LINK
 */
$(document).on("click", ".add_to_menu.add_to_menu_link", function(e) {
    e.preventDefault();
    var url = $('[name=link_url]').val();
    var text = $('[name=link_text]').val();

    if (url == 'http://')
        return false;

    menu_editor.add_menu_item('link', {'url': url, 'text': text});
    return false;
});


/**
 * ROUTE
 */
$(document).on("click", ".add_to_menu.add_to_menu_route", function(e) {
    e.preventDefault();
    var route_name = $('[name=route_name]').val();
    var route_params = $('[name=route_params]').val();

    if (!route_name)
        return false;

    menu_editor.add_menu_item('route', {'route_name': route_name, 'route_params': route_params});
    return false;
});


/**
 * FUNCTION
 */
$(document).on("click", ".add_to_menu.add_to_menu_function", function(e) {
    e.preventDefault();
    var function_name = $('[name=function_name]').val();
    var text = $('[name=function_name] :selected').text();

    if (!function_name)
        return false;

    menu_editor.add_menu_item('function', {'function_name': function_name, 'text': text});
    return false;
});


$(document).on("keyup", ".text_for_title", function(e) {
    var title = $(this).val();

    if (title == '') {
        //console.log($(this).parents('.menu_item_type_content'));
        title = $(this).parents('.menu_item_type_content').find('.default_text_for_title').val();
    }

    $(this).parents('.panel').find('.menu_item_title').text(title);
});


$(document).on("click", ".delete_menu_item", function(e) {

    e.preventDefault();

    //var block = $(this).parents('.menu_item');
    var block = $(this).parents('.dd-item').first();
    //console.log(block);

    // ask verification
    $.SmartMessageBox({
        title : "<i class='fa fa-refresh txt-color-orangeDark'></i> Удалить элемент меню?",
        content : "Восстановить его будет невозможно",
        buttons : '[Нет][Да]'
    }, function(ButtonPressed) {
        if (ButtonPressed == "Да") {
            $.when(
                $(block).slideUp()
            ).done(function( x ) {
                $(block).remove();
                menu_editor.update_output();
            });
        }
    });

    return false;
});


var nestable_output = $('#nestable-output');
var updateOutput = function(e) {

    var list = e.length ? e : $(e.target),
        output = list.data('output')
        //output = nestable_output
        ;

    //alert(typeof output);
    if (typeof output == 'undefined') {
        return false;
    }

    //console.log(list.nestable('serialize'));
    //console.log(output);
    if (window.JSON) {
        output.val(window.JSON.stringify(list.nestable('serialize')));
        //, null, 2));
    } else {
        output.val('JSON browser support required for this demo.');
    }
};

//init_sortable(false, '.menu_items');
//if ($(menu_items).length) {

    /*
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = base_url + '/private/js/plugin/jquery-nestable/jquery.nestable.js';
    document.getElementsByTagName('head')[0].appendChild(script);
    //*/

    //loadScript(base_url + '/private/js/plugin/jquery-nestable/jquery.nestable.js', function() {

        //console.log($.nestable);

        if ($(menu_items).length) {

            //alert(nesting_level);

            if (typeof nesting_level == 'undefined' || !nesting_level) {
                var nesting_level = 5;
            }

            $(menu_items).nestable({
                //group : 1
                maxDepth: nesting_level || 5,
                expandBtnHTML: '',
                collapseBtnHTML: ''
            }).on('change', updateOutput);

            updateOutput($(menu_items).data('output', $(nestable_output)));
        }
    //});
//}


function showHideActiveRexExp(_this) {
    //var checked = $(this).is(':checked');
    var checked = $(_this).attr('checked');
    checked = (typeof checked !== typeof undefined && checked !== false);
    //var element = $(_this).parent().parent().find(".active_regexp").parent();
    var element = $(_this).parent().next('label.input');

    if (checked) {
        //console.log(checked);
        //console.log($(_this));
        $(element).show();
    } else
        $(element).hide();

}

function showHideAllActiveRegExp() {
    $(".click_hidded_option").each(function(e) {
        showHideActiveRexExp($(this));
    });
}

$(document).on("click", ".click_hidded_option", function(e) {
    var _this = $(this);
    var checked = $(_this).is(':checked');
    //var checked = $(_this).attr('checked');
    //checked = (typeof checked !== typeof undefined && checked !== false);
    //var element = $(_this).parent().parent().find(".active_regexp").parent();
    var element = $(_this).parent().next('label.input');

    if (checked) {
        //console.log(checked);
        //console.log($(_this));
        $(element).show();
    } else
        $(element).hide();

});

function str_replace(search, replace, subject) {
    return subject.split(search).join(replace);
}

function htmlentities(s){	// Convert all applicable characters to HTML entities
    //
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)

    var div = document.createElement('div');
    var text = document.createTextNode(s);
    div.appendChild(text);
    return div.innerHTML;
}

function addslashes(str) {
    str=str.replace(/\\/g,'\\\\');
    str=str.replace(/\'/g,'\\\'');
    str=str.replace(/\"/g,'\\"');
    str=str.replace(/\0/g,'\\0');
    return str;
}

