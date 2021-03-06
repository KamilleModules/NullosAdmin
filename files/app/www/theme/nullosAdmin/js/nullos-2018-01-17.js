/**
 * This file is owned by the nullosAdmin theme, which was made for the NullosAdmin module.
 */

(function ($) {


    var options;


    //----------------------------------------
    // SOME PRIVATE FUNCTIONS
    //----------------------------------------
    function getActionLinkDataAttributes(jEl) {
        return $.extend({
            id: 0,
            confirm: "", // false=>empty, true=>1
            confirmtext: "Are you sure you want to execute this action?", // note, jquery lower cases the attributes names
            label: "",
            uri: "/actionlink-handler",
            type: "modal"
        }, jEl.data());
    }


    function error(msg) {
        console.log("dataTable error: " + msg);
    }


    //----------------------------------------
    // CREATING THE NULLOS NAMESPACE
    //----------------------------------------
    $.fn.nullos = function () {

    };


    //----------------------------------------
    // HANDLE RESPONSE (GSCP)
    //----------------------------------------
    $.fn.nullos.handleResponse = function (response, success) {
        if ('type' in response) {
            if ('data' in response) {
                if ('success' === response.type) {
                    success(response.data);
                }
                else if ('error' === response.type) {
                    options.modalResponse('error', response.data);
                }
                else {
                    error("Unknown response type: " + response.type);
                }
            }
            else {
                error("key 'data' not found in response");
            }
        }
        else {
            error("key 'type' not found in response");
        }
    };


    //----------------------------------------
    // HANDLE ACTION LINK
    //----------------------------------------
    $.fn.nullos.handleActionLink = function (jEl) {

        var value = jEl.val();
        if ('0' === value) {
            return;
        }

        /**
         * Note: data-* attributes case seemed
         * to be strlowered
         */
        var data = getActionLinkDataAttributes(jEl);

        var fn = function () {


            if (
                'modal' === data.type ||
                'post' === data.type ||
                'refreshOnSuccess' === data.type ||
                'quietOnSuccess' === data.type
            ) {

                var postData = {
                    id: data.id
                };

                $.post(data.uri, postData, function (response) {
                    if ('post' === data.type) {
                        window.location.reload();
                        return;
                    }

                    $.fn.nullos.handleResponse(response, function (d) {
                        if ('refreshOnSuccess' === data.type) {
                            window.location.reload();
                            return;
                        }
                        if ('modal' === data.type) {
                            options.modalResponse('success', d);
                        }
                        if ('quietOnSuccess' === data.type) {
                            // does nothing
                        }
                    });
                }, 'json');
            }
            else if ('flat' === data.type) {
                location.href = data.uri;
            }
        };
        if (1 === data.confirm) {
            if (true === window.confirm(data.confirmtext)) {
                fn();
            }
        }
        else {
            fn();
        }
    };


    //----------------------------------------
    // UTIL
    //----------------------------------------
    /**
     * Post a form to the server at the given uri.
     * The server responds with a gscp response.
     *
     * We can choose whether to close the modal, or to inject the response into the modal with the mode.
     *
     * mode: reloadIfSuccess|...
     *
     *      If reloadIfSuccess: the page is reloaded if the response is a success.
     *
     *
     */

    $.fn.nullos.postForm = function (jForm, uri, mode) {
        $.post(uri, jForm.serialize(), function (r) {
            if ('success' === r.type) {
                if ('reloadIfSuccess' === mode) {
                    window.location.reload();
                    return;
                }
            }
            jForm.closest('.modal-body').empty().append(r.data);

        }, 'json');
    };

    //----------------------------------------
    // INIT FUNCTIONS
    //----------------------------------------
    function initActionLinks() {
        // $('body').on('click', function (e) {
        //     var jTarget = $(e.target);
        //     if (jTarget.hasClass('special-link')) {
        //         $.fn.nullos.handleActionLink(jTarget);
        //     }
        // });
    }


    $(document).ready(function () {
        console.log("nullos init");
        initActionLinks();
        if ("dataTable" in $.fn) {

            $.fn.dataTable.defaults.modalResponse = function (type, msg) {
                var jLink = $('<button style="display: none" type="button" class="btn btn-primary" data-toggle="modal" data-target="#ajax-modal-main">Large modal</button>');
                $('body').append(jLink);
                jLink.trigger('click');
                $('#ajax-modal-main .modal-content').empty().append(msg);
            };
            $.fn.dataTable.defaults.renderer = 'Module\\NullosAdmin\\ModelRenderers\\DataTable\\NullosDataTableRenderer';


            options = $.extend({}, $.fn.dataTable.defaults);
        }
    });

})(jQuery);
