$.extend($.validator.messages, {
    required: "Это поле необходимо заполнить.",
    remote: "Пожалуйста, введите правильное значение.",
    email: "Пожалуйста, введите корректный адрес электронной почты.",
    url: "Пожалуйста, введите корректный URL.",
    date: "Пожалуйста, введите корректную дату.",
    dateISO: "Пожалуйста, введите корректную дату в формате ISO.",
    number: "Пожалуйста, введите число.",
    digits: "Пожалуйста, вводите только цифры.",
    creditcard: "Пожалуйста, введите правильный номер кредитной карты.",
    equalTo: "Пожалуйста, введите такое же значение ещё раз.",
    extension: "Пожалуйста, выберите файл с правильным расширением.",
    maxlength: $.validator.format("Пожалуйста, введите не больше {0} символов."),
    minlength: $.validator.format("Пожалуйста, введите не меньше {0} символов."),
    rangelength: $.validator.format("Пожалуйста, введите значение длиной от {0} до {1} символов."),
    range: $.validator.format("Пожалуйста, введите число от {0} до {1}."),
    max: $.validator.format("Пожалуйста, введите число, меньшее или равное {0}."),
    min: $.validator.format("Пожалуйста, введите число, большее или равное {0}.")
});

    function init_sortable(url, selector, success, connect_with) {

        if (typeof success != 'function')
            success = function(){};

        if (typeof connect_with == 'undefined')
            connect_with = '';
        else
            connect_with = '.sortable' + connect_with;

        $(document).on("mouseover", ".sortable" + selector, function(e){
            // Check flag of sortable activated
            if ( !$(this).data('sortable') ) {
                // Activate sortable, if flag is not initialized
                $(this).sortable({
                    // On finish of sorting
                    stop: function() {
                        if (url) {
                            // Find all child elements
                            var pls = $(this).find('tr, .sortable_item');
                            var poss = [];
                            // Make array with current sorting order
                            $(pls).each(function(i, item) {
                                poss.push($(item).data('id'));
                            });
                            // Send ajax request to server for saving sorting order
                            $.ajax({
                                url: url,
                                type: "post",
                                data: { poss: poss },
                                success: success
                            });
                        }
                    },
                    cancel: ".not-sortable,.popover,.popover textarea",
                    distance: 5,
                    connectWith: connect_with
                });
            }
        });
    }



    Array.max = function( array ){
        return Math.max.apply( Math, array );
    };

    // Function to get the Min value in Array
    Array.min = function( array ){
        return Math.min.apply( Math, array );
    };

    /*
    //updated as per Sime Vidas comment.
    var widths= $('img').map(function() {
        return $(this).width();
    }).get();

    alert("Max Width: " + Array.max(widths));
    alert("Min Width: " + Array.min(widths));
    */

    jQuery.fn.tagName = function() {
        return this.prop("tagName");
    };


    function array_merge() {
        //  discuss at: http://phpjs.org/functions/array_merge/
        // original by: Brett Zamir (http://brett-zamir.me)
        // bugfixed by: Nate
        // bugfixed by: Brett Zamir (http://brett-zamir.me)
        //    input by: josh
        //   example 1: arr1 = {"color": "red", 0: 2, 1: 4}
        //   example 1: arr2 = {0: "a", 1: "b", "color": "green", "shape": "trapezoid", 2: 4}
        //   example 1: array_merge(arr1, arr2)
        //   returns 1: {"color": "green", 0: 2, 1: 4, 2: "a", 3: "b", "shape": "trapezoid", 4: 4}
        //   example 2: arr1 = []
        //   example 2: arr2 = {1: "data"}
        //   example 2: array_merge(arr1, arr2)
        //   returns 2: {0: "data"}

        var args = Array.prototype.slice.call(arguments),
            argl = args.length,
            arg,
            retObj = {},
            k = '',
            argil = 0,
            j = 0,
            i = 0,
            ct = 0,
            toStr = Object.prototype.toString,
            retArr = true;

        for (i = 0; i < argl; i++) {
            if (toStr.call(args[i]) !== '[object Array]') {
                retArr = false;
                break;
            }
        }

        if (retArr) {
            retArr = [];
            for (i = 0; i < argl; i++) {
                retArr = retArr.concat(args[i]);
            }
            return retArr;
        }

        for (i = 0, ct = 0; i < argl; i++) {
            arg = args[i];
            if (toStr.call(arg) === '[object Array]') {
                for (j = 0, argil = arg.length; j < argil; j++) {
                    retObj[ct++] = arg[j];
                }
            } else {
                for (k in arg) {
                    if (arg.hasOwnProperty(k)) {
                        if (parseInt(k, 10) + '' === k) {
                            retObj[ct++] = arg[k];
                        } else {
                            retObj[k] = arg[k];
                        }
                    }
                }
            }
        }
        return retObj;
    }


    $("input[type=checkbox].system_checkbox.mark_all_checkbox").click(function(){
        var checked = $(this).is(':checked');

        $('input[type=checkbox]').each(function(i){
            if (!$(this).hasClass('system_checkbox')) {
                if (checked)
                    $('input[type=checkbox]').prop('checked', true);
                else
                    $('input[type=checkbox]').prop("checked", false);
            }
        });
    });

    //*
    $(".system_checkbox.toggle_all_checkbox").click(function(){
        $('input[type=checkbox]').each(function(i){
            if (!$(this).hasClass('system_checkbox')) {
                var checked = $(this).prop("checked");
                $(this).prop("checked", !checked)
            }
        });
    });
    //*/


    $('label[data-helpmessage]').each(function(){
        //alert($(this).data('helpmessage'));
        var content = $(this).html();
        var helpmessage = $(this).data('helpmessage');
        $(this).html(content + "\n" + '<i class="fa fa-question-circle cursor-help" data-toggle="tooltip" data-placement="bottom" title="' + helpmessage + '"></i>');
    });

    $('.dropdown-menu>li>span').click(function(){
        return false;
    });