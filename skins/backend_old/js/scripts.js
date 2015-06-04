
// Function show dialog loading. 
// Use: dialogLoading.show(); dialogLoading.hide();
var dialogLoading = (function ($) {
    return {
        show: function () {
            $('#dialog-icon').removeClass('messeage-icon');
            $('#dialog-icon').addClass('loading-icon');
            $('.overlay').show();
        }
        ,
        hide: function () {
            $('.overlay').hide();
        }
    };
})(jQuery);

// Function show dialog with messeage. 
// Use: dialogMesseage.show('Alert', 'Have an error in here'); dialogMesseage.hide();
var dialogMesseage = (function ($) {
    return {
        show: function (title, messeage) {
            $('#dialog-icon').removeClass('loading-icon');
            $('#dialog-icon').addClass('messeage-icon');
            $('#dialog-box').addClass('dialog-box-messeage');
            $('#dialog-box').find('.dialog-title').html(title);
            $('#dialog-box').find('.dialog-messeage').html(messeage);
            $('#dialog-box .dialog-btn').show();
            $('.overlay').show();
        },
        hide: function () {
            $('.overlay').hide();
        }
    };
})(jQuery);

// show Modal
var modalLoading = (function ($) {
    // Creating modal dialog's DOM
    var $dialog = $(
            '<div class="modal modal-static fade" id="processing-modal" role="dialog" aria-hidden="true" data-backdrop="static"  >' +
            '<div class="modal-dialog" style="position:fixed; top:33%; left:33%" data-backdrop="static" >' +
            '<div class="modal-content" data-backdrop="static">' +
            '<div class="modal-body" data-backdrop="static">' +
            '<div class="text-center">' +
            '<img width="50" height="50" src="skins/backend/images/loading_5.gif">' +
            '<h4>Đang xử lý... <button type="button" data-backdrop="static" class="close" style="float: none;" data-dismiss="modal" aria-hidden="true">x</button></h4>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>'
            );
    return {
        /**
         * Opens our dialog
         * @param message Custom message
         * @param options Custom options:
         * 				  options.dialogSize - bootstrap postfix for dialog size, e.g. "sm", "m";
         * 				  options.progressType - bootstrap postfix for progress bar type, e.g. "success", "warning".
         */
        show: function (message, options) {
            // Assigning defaults
            var settings = $.extend({
                dialogSize: 'm',
                progressType: ''
            }, options);
            if (typeof message === 'undefined') {
                message = 'Loading';
            }
            if (typeof options === 'undefined') {
                options = {};
            }
            // Configuring dialog
            $dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
            $dialog.find('.progress-bar').attr('class', 'progress-bar');
            if (settings.progressType) {
                $dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
            }
            $dialog.find('h3').text(message);
            // Opening dialog
            $dialog.modal();
        },
        /**
         * Closes dialog
         */
        hide: function () {
            $dialog.modal('hide');
        }
    };

})(jQuery);
var modalMesseage = (function ($) {
    // Creating modal dialog's DOM
    var $dialog = $(
            '<div class="modal modal-static fade" id="processing-modal overlay" role="dialog" aria-hidden="true"   >' +
            '<div class="modal-dialog" style="position:fixed; top:33%; left:33%" >' +
            '<div class="modal-content" >' +
            '<div class="modal-body">' +
            '<div class="text-center">' +
            '<h6>Cảnh báo!</h6>' +
            '<h5></h5> <button type="button" class="close btn btn-md btn-warning" style="float: none;" data-dismiss="modal" aria-hidden="true">Đóng</i></button>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>'
            );
    return {
        /**
         * Opens our dialog
         * @param message Custom message
         * @param options Custom options:
         * 				  options.dialogSize - bootstrap postfix for dialog size, e.g. "sm", "m";
         * 				  options.progressType - bootstrap postfix for progress bar type, e.g. "success", "warning".
         */
        show: function (message, options) {
            // Assigning defaults
            var settings = $.extend({
                dialogSize: 'm',
                progressType: ''
            }, options);
            if (typeof message === 'undefined') {
                message = 'Loading';
            }
            if (typeof options === 'undefined') {
                options = {};
            }
            // Configuring dialog
            $dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
            $dialog.find('.progress-bar').attr('class', 'progress-bar');
            if (settings.progressType) {
                $dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
            }
            $dialog.find('h5').text(message);
            // Opening dialog
            $dialog.modal();
        },
        /**
         * Closes dialog
         */
        hide: function () {
            $dialog.modal('hide');
        }
    }

})(jQuery);

// Show notice. 
// Use noticeMeseage.show('Notice content', 'success|warning|danger'); noticeMeseage.clear(); noticeMeseage.hide(); 
var noticeMeseage = (function ($) {
    return {
        clear: function () {
            $('.message .alert').removeClass('alert-danger');
            $('.message .alert').removeClass('alert-warning');
            $('.message .alert').removeClass('alert-success');
        },
        show: function (messeage, status) {
            this.clear();
            $('.alert-message').html(messeage);
            $('.message .alert').addClass('alert-' + status);
            $('.message').show();
        },
        hide: function () {
            $('.overlay').hide();
        }
    };
})(jQuery);




