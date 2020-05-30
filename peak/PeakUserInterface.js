function AutoResizeFont(id, minfont) {
    var defaultFontSize = 11;
    var elementId = "#" + id;
    minfont = minfont || defaultFontSize;

    $(elementId).css("word-break", "break-all");

    var currentwidth = $(elementId).innerWidth();

    $(elementId).css("white-space", "nowrap");

    var inlinewidth = $(elementId).innerWidth();
    var difwidth = inlinewidth - currentwidth;
    
    var loopOut = 19;
    var loopCount = 0;

    if (difwidth > 0) {
        while (1) {         
            if (parseFloat($(elementId).css('font-size')) <= minfont) {
                $(elementId).css('font-size', minfont + 'px');
                break;
            } 

            if ($(elementId).width() - currentwidth > 0) {
                $(elementId).css('font-size', parseFloat($(elementId).css('font-size')) - 1 + 'px');
            } else {
                break;
            }

            if (loopCount > loopOut) {
                break;
            }
            loopCount++;
        }
    }
}

function SetFontAutoResize(selection, maxLengthInput, noDefaultFontSize) {
    $(selection).each(function () {
        var maxLength = maxLengthInput;
        var currentTextLength = parseInt($(this).text().length);
        var diff = currentTextLength - maxLength;

        if (diff > 0) {
            var currentFontSize = parseInt($(this).css("font-size"));
            var percentageDecrease = ((currentTextLength * 100) / maxLength) - 100;
            var newFontSize = currentFontSize - (currentFontSize * ConvertPercentageToFraction(percentageDecrease));

            if (noDefaultFontSize !== true) {
                if (newFontSize <= 18) newFontSize = 18;
            }

            $(this).css("font-size", newFontSize + "px");
        }
    });
}

function SetFontSize(selector, newFontSize) {
    $(selector).css("font-size", newFontSize + "px");
}

function ConvertPercentageToFraction(percentage) {
    return percentage / 100;
}

function ControlTextareaLineLimit(textAreaSelector) {
    $(textAreaSelector).textareamaxrows({
        maxrows: 11,
        maxcharsinrow: 55
    });
}

(function ($) {

    $.fn.textareamaxrows = function (options) {

        var defaults = {
            alert: false,
            alertmessage: 'too many rows and chars',
            maxrows: 9,
            maxcharsinrow: 55,
            usecounter: false,
            counterelem: '',
            errorclass: 'error'
        }

        var down = {};

        var opts = $.extend(defaults, options);

        // prevent right click paste
        //$(this).on("paste contextmenu", function (e) { e.preventDefault(); });

        return this.each(function (event) {
            $(this).keyup(onKeyup);
            $(this).keydown(onKeyDown);
        });

        // prevent holding enter key
        function onKeyDown(event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);

            if (keycode == '13') {
                if (down['13'] == null) { // first press
                    down['13'] = true; // record that the key's down
                }
                else {

                    // Cut down the string
                    //var current_length = $(this).val().length;
                    //var new_length = current_length - 1;
                    //$(this).val($(this).val().substr(0, new_length));
                }
            }
        }

        function onKeyup() {

            var number_breaks = $(this).val().split('\n').length;
            // first break does not count
            number_breaks--;

            var number_fake_breaks = 0;
            var text_paragraphs = $(this).val().split('\n');
            for (var i in text_paragraphs) {
                var number_fake_breaks_paragraph = parseInt(getNumberOfChunks(opts.maxcharsinrow, text_paragraphs[i]));
                if (number_fake_breaks_paragraph > 1) {
                    number_fake_breaks = number_fake_breaks + number_fake_breaks_paragraph;
                }
            }

            var total_breaks = parseInt(number_breaks + number_fake_breaks);

            if (total_breaks >= opts.maxrows) {
                if (opts.alert) {
                    alert(opts.alertmessage);
                }

                $(this).addClass("input-warning");

                // add error class to textarea
                $(this).addClass(opts.errorclass);

                // Cut down the string
                //var current_length = $(this).val().length;
                //var new_length = current_length - 1;
                //$(this).val($(this).val().substr(0, new_length));
            }
            else {
                // remove error class textarea
                $(this).removeClass("input-warning");
                $(this).removeClass(opts.errorclass);
            }

            // set counter if option usecounter and counterelem and is set
            if ((opts.usecounter) && opts.counterelem != '') {
                if ((opts.maxrows - total_breaks) < 0) {
                    $(opts.counterelem).html('0');
                }
                else {
                    $(opts.counterelem).html(opts.maxrows - total_breaks);
                }
            }

            return false;
        }


        function getNumberOfChunks(chunkSize, checkString) {

            var chunks = [];

            while (checkString) {
                if (checkString.length < chunkSize) {
                    chunks.push(checkString);
                    break;
                }
                else {
                    chunks.push(checkString.substr(0, chunkSize));
                    checkString = checkString.substr(chunkSize);
                }
            }

            return chunks.length;
        }

    }

})(jQuery);


function CutThaiVowel(inputString) {
    var thaiVowelList = ["ิ", "ี", "ึ", "ื", "ุ", "ู", "่", "้", "๊", "๋", "ั", "์", "็","ํ"];
    var strAfterCutThaiVowel = inputString;

    thaiVowelList.forEach(thaiVowel => strAfterCutThaiVowel = strAfterCutThaiVowel.replace(new RegExp(thaiVowel, 'g'), ""));
    
    return strAfterCutThaiVowel;
}