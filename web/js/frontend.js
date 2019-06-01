/**
 * Created by Arty on 16.02.2018.
 */

;(function($){

    var collect_clothes = function(){
        var filter = [];
        $('.js-clothes-filter:not(.off)').each(function(i){
            var id = $(this).attr("id").replace("clothing_", "");
            filter[i] = id;
        });

        return filter;
    };

    var review_page = 1;

    function slideReviews(page){
        var offset = parseInt($(".sliderCrop").width()) + 32;
        $(".js-list-reviews li:first").css("margin-left", 32-offset* (page - 1));
    }

    $(function(){


        /************************************************ MENU PJAX ACTIONS ************************************************/

        $(document).on('pjax:complete', function(e) {
            var url_data = e.delegateTarget.URL.split("?");
            if( url_data[1] == "to_loc=1" ){
                $("section.mainCover").hide();
                window.scrollTo(0, $(".js-atelier-coords").offset().top);
            }
        });

        $("body").on("click", ".js-frontend-menu a", function(){
            var $li = $(this).closest("li");
            if( !$li.hasClass("current") ) {
                $.pjax({container: '#js-frontend-content', url: $(this).attr("href"), push: true, timeout: 500});
                $(".reset li").removeClass("current");
                $(".reset li[item='" + $li.attr("item") + "']").addClass("current");

                $("section.mainCover").hide();
            }

            return false;
        });

        $("body").on("click", ".js-atelier-location", function(){

            if( $('.reset li.current[item="atelier"]').length ){
                window.scrollTo(0, $(".js-atelier-coords").offset().top);
                return false;
            } else {
                $.pjax({container: '#js-frontend-content', url: $(this).attr("href"), push: true, timeout: 500});

                $(".reset li").removeClass("current");
                $('.reset li[item="atelier"]').addClass("current");
            }

            return false;
        });

        $("body").on("click", ".js-post-link", function(){
            $(".reset li").removeClass("current");
            $("section.mainCover").hide();
            return true;
        });

        $("body").on("click", ".js-view-item", function(){
            var item = $(this).attr("item");
            $(".js-frontend-menu li[item='" + item + "'] a:first").trigger("click");
            return false;
        });

        /*************************************************************************************************************/


        /************************************************ Products EVENTS ***********************************************/

        $("body").on("click", ".js-clothes-filter", function(){
            if( $(this).hasClass("off") ){
                $(this).removeClass("off");
            } else {
                $(this).addClass("off");
            }

            var url = $(this).closest("ul").attr("url") + '?filter=' + encodeURIComponent(JSON.stringify(collect_clothes()));
            $.pjax({container: '#js-frontend-content', url: url, push: 0, scrollTo: false, timeout: 3000});
            return false;
        });


        $("body").on("click", ".js-portfolio-card .js-left-arrow, .js-portfolio-card .js-right-arrow", function(){
            var $li = $(this).closest("li");
            var $slides = $li.find(".js-marker-slides");
            var $images = $li.find("figure");
            var $img = $images.find("img.showItem, img.showItemReverse");
            var $span = $slides.find("span.current");
            var $nextImg = null, $nextSpan = null;

            $img.removeClass('showItem showItemReverse');

            if ($(this).hasClass("js-left-arrow")) {

                if ($img.prev().length) {
                    $nextImg = $img.prev();
                    $nextSpan = $span.prev();
                } else {
                    $nextImg = $images.find('img:last');
                    $nextSpan = $slides.find('span:last');
                }

                $nextImg.removeClass('hideItem hideItemReverse');

                $img.addClass('hideItemReverse');
                $nextImg.addClass('showItemReverse');

            } else {

                if ($img.next().length) {
                    $nextImg = $img.next();
                    $nextSpan = $span.next();
                } else {
                    $nextImg = $images.find('img:first');
                    $nextSpan = $slides.find('span:first');
                }

                $nextImg.removeClass('hideItem hideItemReverse');

                $img.addClass('hideItem');
                $nextImg.addClass('showItem');

            }

            $span.removeClass('current');
            $nextSpan.addClass('current');

            return false;
        });

        $("body").on("click", ".js-portfolio-card .js-clothes-name", function(){
            var clothes_id = $(this).attr("id");
            $(".js-clothes-filter").addClass("off");
            $(".js-clothes-filter[id='clothing_" + clothes_id + "']").trigger("click");

            return false;
        });

        $("body").on("click", ".js-portfolio-card .js-index-clothes-name", function(){
            var clothes_id = $(this).attr("id");
            $.pjax({
                container: '#js-frontend-content',
                url: '/products.html?filter=' + encodeURIComponent(JSON.stringify([clothes_id])),
                push: 0,
                scrollTo: false,
                timeout: 3000
            });

            return false;
        });

        $("body").on("click", ".js-portfolio-card .js-show-desc", function(){
            var $li = $(this).closest("li");
            $li.find(".sideFront").addClass("flipBack");
            $li.find(".sideBack").removeClass("hide").addClass("flip");

            return false;
        });

        $("body").on("click", ".js-portfolio-card .js-close-description", function(){
            var $li = $(this).closest("li");
            var $sideBack = $li.find(".sideBack");
            var $sideFront = $li.find(".sideFront");
            $sideBack.removeClass("flip").addClass("flipBackReverse");
            $sideFront.removeClass("flipBack").addClass("flipReverse");

            $sideBack.get(0).addEventListener('animationend', function fn (){
                $sideBack.get(0).removeEventListener("animationend", fn);
                $sideBack.addClass('hide').removeClass('flipBackReverse');
                $sideFront.removeClass("flipReverse");
            });

            return false;
        });

        $("body").on("click", ".js-left-reviews", function(){
            review_page = Math.max(1, review_page - 1);
            slideReviews(review_page);
        });

        $("body").on("click", ".js-right-reviews", function(){
            review_page = Math.min(review_page + 1, $(".js-list-reviews li").length/2);
            slideReviews(review_page);
        });

        $(window).on('resize', function () {
            slideReviews(review_page);
        });



        /**************************************** FORM EVENTS ****************************************/

        $("body").on('afterValidateAttribute', '#frontend-form', function(event, attribute, message) {
            $("#" + attribute.id).attr("invalid", message.length ? true : null);
        });

        $("body").on('afterValidate', '#frontend-form', function(event, messages, errorAttributes) {
            for(var id in messages){
                if( messages[id].length ){
                    $(".validation .error").removeClass("hide");
                    $(".validation .success").addClass("hide");
                    return false;
                }
            }

            $(".validation .error").addClass("hide");
            $(".validation .success").removeClass("hide");

            return true;
        });


        $("body").on('keyup', '#frontend-form input[type="text"]', function(e){
            if(e.keyCode == 13){
                $(this).closest("form").submit();
            }
        });

        $("body").on("click", "#frontend-form .iconClose", function(){
            $(".feedbackForm").addClass("hide");
            $(".js-open-review-form a").removeClass("hide");
        });

        $("body").on("click", ".js-open-review-form a", function(){
            $(this).addClass("hide");
            $(".feedbackForm").removeClass("hide");
            $('form[id="frontend-form"] input[type="text"], form[id="frontend-form"] textarea').attr("invalid", null).val("");
        });

    });

})(jQuery);