/**
 * Created by Arty on 16.02.2018.
 */

;(function($){

    window.okConfirm = function(yes){
        var popup =
            '<div class="popupBody">' +
                '<div class="popupContent">' +
                    '<h3>Удаление объекта</h3>' +
                    '<p>Внимание! Это действие необратимо. Подтверждаете удаление?</p>' +
                    '<br>' +
                    '<div class="flex flexBetween">' +
                        '<button class="t-confirm-yes" type="submit">Да, удаляем</button>' +
                        '<button class="t-confirm-no">Ой, нет</button>' +
                    '</div>' +
                '</div>' +
            '</div>';

        $("body").append(popup);

        $(".t-confirm-yes, .t-confirm-no").click(function(){
            $(this).closest(".popupBody").remove();
            if( $(this).hasClass("t-confirm-yes") ){
                yes();
            } else {
                return false;
            }
        });
    };

    window.okAlert = function(title, message){
        var $popup =
            $('<div class="popupBody">' +
                '<div class="popupContent" style="max-width: 40em;">' +
                    '<h3>' + title + '</h3>' +
                    '<p>' + message + '</p>' +
                    '<div class="flex flexBetween">' +
                        '<div></div>' +
                        '<div class="buttonsMultiple">' +
                            '<button class="js-alert-ok" type="button">Ok</button>' +
                        '</div>' +
                        '<div></div>' +
                    '</div>' +
                '</div>' +
            '</div>');

        $("body").append($popup);

        $popup.find(".js-alert-ok").click(function(){
            $(this).closest(".popupBody").remove();
        });
    };

    var get_model = function(){
        return $("[model]").attr("model");
    };

    var run_sortable = function(){
        if( get_model() == 'products' ){
            $(".js-images").sortable({
                placeholder: "landingPlace"
            });
        }
    };

    var image_redactor = function(src, crop_settings, index) {

        crop_settings = crop_settings.split(":");

        $("body").append(
            '<div class="popupBody">' +
                '<div class="popupContent" style="min-width: 53em; min-height: 41em; text-align: center;">' +
                    '<h3>Редактрирование изображения</h3>' +
                    '<div id="image-cropper">' +
                        '<div class="cropit-preview"></div>' +
                        '<div class="controls-wrapper">' +
                            '<div class="slider-wrapper"><input type="range" class="cropit-image-zoom-input" min="0" max="1" step="0.01"></div>' +
                        '</div>'+
                    '</div>' +
                    '<div class="flex flexBetween">' +
                        '<button class="js-confirm-yes" type="submit">Готово!</button>' +
                        // '<div class="buttonsMultiple">' +
                        //     '<input type="hidden" class="js-crop-rotation" value="' + (typeof crop_settings[3] != "undefined" ? crop_settings[3] : 0) + '">' +
                        //     '<button class="js-rotate-left" type="button">&#8634;</button>' +
                        //     '<button class="js-rotate-right" type="button">&#8635;</button>' +
                        // '</div>' +
                        '<button class="js-confirm-no" onclick="$(this).closest(\'.popupBody\').remove();">Отмена</button>' +
                    '</div>' +
                '</div>' +
            '</div>'
        );

        var is_products = get_model() == 'products';

        $('#image-cropper').cropit({
            width: is_products ? 320 : 400,
            height: is_products ? 480 : 400,
            imageBackground: true,
            maxZoom: 1,
            allowDragNDrop: false,
            freeMove: false,
            imageState: { src: src },
            onImageLoading: function(){
                $(".popupContent").addClass("spinner");
            },
            onImageLoaded: function(){
                $(".popupContent").removeClass("spinner");
                var $crop = $('#image-cropper');

                if(crop_settings.length == 4) {

                    var coefficient = is_products ? 2.5 : 1;

                    if(crop_settings[3] != 0){
                        var orientation = crop_settings[3] > 0 ? 'rotateCCW' : 'rotateCW';
                        for(var i= 1, count=crop_settings[3]/90; i<=count; ++i){
                            $crop.cropit(orientation);
                        }
                    }

                    $crop.cropit('zoom', crop_settings[2]/coefficient);
                    $crop.cropit('offset', {x: parseInt(crop_settings[0]/coefficient), y: parseInt(crop_settings[1]/coefficient)});
                    crop_settings[3] = parseInt(crop_settings[3]);

                }
            }
        });

        // $('.js-rotate-left').click(function() {
        //     $('#image-cropper').cropit('rotateCCW');
        //     var $rot = $(".js-crop-rotation");
        //     $rot.val(parseInt($rot.val()) + 90);
        // });
        // $('.js-rotate-right').click(function() {
        //     $('#image-cropper').cropit('rotateCW');
        //     var $rot = $(".js-crop-rotation");
        //     $rot.val(parseInt($rot.val()) - 90);
        // });


        $(".js-confirm-yes").click(function(){
            var $crop = $('#image-cropper');
            var src = $crop.cropit('export');
            var offset = $crop.cropit('offset');
            var zoom = $crop.cropit('zoom');
            var rotation = $('.js-crop-rotation').val();
            var coefficient = is_products ? 2.5 : 1;

            var $input = $('input[name="crop-edit-images[' + index + ']"]');
            $input.val(parseInt(offset.x*coefficient) + ":" + parseInt(offset.y*coefficient) + ":" + (zoom*coefficient) + ":" + rotation);
            $input.prev().attr("src", src);

            $(this).closest('.popupBody').remove();
        });
    };

    var auto_crop_images = function(files){

        var URL = window.URL || window.webkitURL || window.mozURL;

        var is_products = get_model() == 'products';
        var width = is_products ? 320 : 400;
        var height = is_products ? 480 : 400;

        // проверять что бы ширина и высота рисунка были не меньше канвы!
        var is_valid_images = true;
        for( var j=0, l=files.length; j<l; ++j ){
            var img = new Image();
            img.onload = function(j){
                if( this.width < width || this.height < height ){
                    if(is_valid_images){
                        window.okAlert('Внимание!', 'Некоторые изображения не могут быть загружены из-за слишком малых размеров!<br>Минимальный размер изображения: ' + width + 'x' + height);
                    }
                    is_valid_images = false;
                    delete files[j];
                }
            };
            img.src = URL.createObjectURL(files[j]);
        }

        if( !$("#image-auto-cropper").length ) {
            $("body").append(
                '<div id="image-auto-cropper" style="display: none;">' +
                    '<div class="cropit-preview"></div>' +
                    '<div class="controls-wrapper">' +
                        '<div class="slider-wrapper"><input type="range" class="cropit-image-zoom-input" min="0" max="1" step="0.01"></div>' +
                    '</div>' +
                '</div>'
            );
        }


        var i = 0;
        $("#backend-form").next().find('button[type="submit"]').prop("disabled", true);
        $('#image-auto-cropper').cropit({
            width: width,
            height: height,
            maxZoom: 1,
            imageState: { src: URL.createObjectURL(files[i]) },
            onImageLoading: function(e){
                // do some actions..
            },
            onImageLoaded: function(){
                var $crop = $('#image-auto-cropper');
                var src = $crop.cropit('export');
                var offset = $crop.cropit('offset');
                var zoom = $crop.cropit('zoom');
                var coefficient = is_products ? 2.5 : 1;

                var cropSize = $crop.cropit('previewSize');
                var realSize = $crop.cropit('imageSize');
                var cropWidth = zoom*realSize.width;
                var cropHeight = zoom*realSize.height;

                if( cropWidth > cropSize.width ){
                    var deltaX = (cropWidth-cropSize.width)/2;
                    offset.x - deltaX;
                }

                if( cropHeight > cropSize.height ){
                    var deltaY = (cropHeight-cropSize.height)/2;
                    offset.y - deltaY;
                }

                $crop.cropit('offset', offset);

                $("div.images ul.js-images").append('<li><div class="icon iconClose"></div><div class="icon iconEdit"></div><img src="' + src + '" alt="" title=""><input type="hidden" crop-original-src="' + URL.createObjectURL(files[i]) + '" class="js-crop-input-data" name="crop-edit-images[' + i + ']" id="crop-edit-' + i + '"  value="' + parseInt(offset.x*coefficient) + ':' + parseInt(offset.y*coefficient) + ':' + (zoom*coefficient) + ':0" /></li>');

                if( ++i < files.length ){
                    $crop.cropit('imageSrc', URL.createObjectURL(files[i]));
                } else {
                    $crop.remove();
                    $("#backend-form").next().find('button[type="submit"]').prop("disabled", null);
                }
            }
        });
    };

    var collect_clothes = function(){
        var filter = [];
        $('.js-clothes-filter:not(.off)').each(function(i){
            var id = $(this).attr("id").replace("clothing_", "");
            filter[i] = id;
        });

        return filter;
    };

    $(function(){


        $('body').on("click", ".js-close-edit-section", function(){
            $(".formNote").html("");
            $(".formItem input, .formItem textarea").val("");
            $(".scrollbars").removeClass("editing");
        });

        $('body').on("click", ".t-open-editing", function(){
            $(".formNote").html("");
            var model = get_model();
            $("#backend-form input[id^='" + model + "-'], #backend-form select, #backend-form textarea[id^='" + model + "-'], #backend-form button").attr("invalid", null).val("");

            switch (model){
                case 'staff':
                case 'products':
                    $("div.images ul.js-images").html("");
                    run_sortable();
                    break;
                case 'posts':
                    $(".js-remain-text b").text(300);
                    break;
            }

            $(".scrollbars").addClass("editing");
        });



        /************************************************ MENU PJAX ACTIONS ************************************************/

        // $("body").on("click", ".reset a", function(){
        //     if( !$(this).closest("li").hasClass("current") ) {
        //         $(".scrollbars").removeClass("editing");
        //         $.pjax({container: '#js-content-article', url: $(this).attr("href"), 'push': true});
        //         $(".reset li").removeClass("current");
        //         $(this).closest("li").addClass("current");
        //     }
        //
        //     return false;
        // });

        /*************************************************************************************************************/



        /************************************************ FORM EVENTS ************************************************/

        $("body").on("click", ".js-edit-data", function(){
            $(".formNote").hide();
            var data = JSON.parse($(this).closest(".js-model-object").find(".js-json-data").text());
            var model = get_model();

            for (var key in data){
                var $field = $("#backend-form [id='" + model + "-" + key + "']");

                if( $field.length ){

                    if( key == 'image' && model == 'products'){
                        $("div.images ul.js-images").html("");

                        for(var i in data[key]){
                            var image = data[key][i];

                            $("div.images ul.js-images").append(
                                '<li>' +
                                    '<div class="icon iconClose"></div><div class="icon iconEdit"></div><img src="/img/' + model + '/' + image.image + '" alt="" title="">' +
                                    '<input type="hidden" crop-original-src="/img/' + model + '/original_' + image.image + '" class="js-crop-input-data" name="crop-edit-images[' + image.id + ']" id="crop-edit-' + image.id + '" value="' + image.settings + '">' +
                                '</li>'
                            );

                        }

                    } else {
                        $field.val(data[key]);
                    }

                } else {
                    if( key == 'photo' && model == 'staff' ){
                        $("div.images ul.js-images").html("");
                        $("div.images ul.js-images").append(
                            '<li>' +
                                '<div class="icon iconClose"></div><div class="icon iconEdit"></div><img src="/img/' + model + '/' + data[key] + '" alt="" title="">' +
                                '<input type="hidden" crop-original-src="/img/' + model + '/original_' + data[key] + '" class="js-crop-input-data" name="crop-edit-images[0]" id="crop-edit-0" value="' + data['settings'] + '">' +
                            '</li>'
                        );

                    }

                }
            }

            run_sortable();

            $("#backend-form .formItem input, #backend-form select, #backend-form .formItem textarea, #backend-form button").attr("invalid", null);
            $(".scrollbars").addClass("editing");

            if( model == 'posts' ){
                $('textarea[id="posts-introductory_text"]').trigger("keyup");
            }

        });

        $("body").on('afterValidateAttribute', '#backend-form', function(event, attribute, message) {
            $("#" + attribute.id).attr("invalid", message.length ? true : null);
        });

        $("body").on('afterValidate', '#backend-form', function(event, messages, errorAttributes) {
            for(var id in messages){
                if( messages[id].length ){
                    $(".formNote").removeClass("success").addClass("error").html(messages[id][0]).show();
                    return false;
                }
            }

            $(".formNote").removeClass("error").html("").hide();
            return true;
        });

        $("body").on('beforeSubmit', '#backend-form', function(event) {
            var model = get_model();

            if( model == 'products' || model == 'staff'  ){
                if( !$(".js-images li").length ) {
                    $("#backend-form .js-upload-image").attr("invalid", true);
                    $(".formNote").removeClass("success").addClass("error").html("«Фото» - не выбрано").show();
                    return false;
                } else {
                    $("#backend-form .js-upload-image").attr("invalid", null);
                    $(".formNote").removeClass("error").html("").hide();
                }

                if( model == 'products' && $(".js-images li").length > 20 ){
                    $("#backend-form .js-upload-image").attr("invalid", true);
                    $(".formNote").removeClass("success").addClass("error").html("Максимум - 20 фотографий").show();
                    return false;
                } else {
                    $("#backend-form .js-upload-image").attr("invalid", null);
                    $(".formNote").removeClass("error").html("").hide();
                }
            }

            if( !parseInt($("#backend-form input[id='" + model + "-id']").val()) ){
                $("#backend-form input[name='page']").val(0);
            }

            $(".formEdit").find("form").hide();
            $(".formEdit").addClass("spinner");
            $(this).next().find('button[type="submit"]').prop("disabled", true);

            if( model == 'products' ){
                $(this).append('<input type="hidden" name="filter" value="' + encodeURIComponent(JSON.stringify(collect_clothes())) + '">');
            }

            return true;
        });

        $("body").on('keyup', 'form[id="backend-form"] input[type="text"]', function(e){
            if(e.keyCode == 13){
                $(this).closest("form").submit();
            }
        });

        $("body").on('keyup', 'textarea[id="posts-introductory_text"]', function(e){
            var remain_text = 300 - parseInt($(this).val().length);
            $(".js-remain-text b").text(remain_text);
        });

        $("#js-content-article").on("pjax:end", function(e) {
            $(".formNote").removeClass("error").addClass("success").html("Сохранено").show();
        });

        /*************************************************************************************************************/



        /************************************************ IMAGE EVENTS ***********************************************/
        $("body").on("click", ".js-upload-image", function(){
            $(".js-load-image").click();
        });

        $("body").on("change", ".js-load-image", function(e){
            var URL = window.URL || window.webkitURL || window.mozURL;

            if( URL ) {
                $("div.images ul.js-images").html("");

                if( e.target.files.length ) {
                    auto_crop_images(e.target.files);
                }

                // reset error message for image button
                $("#backend-form button").attr("invalid", null);
                $(".formNote").removeClass("error").html("").hide();

            }
        });

        $("body").on("click", "div.js-crop-images .iconClose", function(e){
            $(this).closest("li").remove();
        });

        $("body").on("click", "div.js-crop-images .iconEdit", function(e){
            var $input = $(this).closest("li").find('input.js-crop-input-data');
            image_redactor($input.attr("crop-original-src"), $input.val(), $input.attr("id").replace("crop-edit-", ""));
        });

        /*************************************************************************************************************/



        /************************************************ Products EVENTS ***********************************************/

        $("body").on("click", ".js-clothes-filter", function(){
            if( $(this).hasClass("off") ){
                $(this).removeClass("off").removeClass("gray");
            } else {
                $(this).addClass("off").addClass("gray");
            }

            var url = $(this).closest("div").attr("url") + '?filter=' + encodeURIComponent(JSON.stringify(collect_clothes()));
            $(".js-close-edit-section").click();
            $.pjax({container: '#js-content-article', url: url, 'push': 0});
            return false;
        });

    });
})(jQuery);