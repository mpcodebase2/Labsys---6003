/*
    Written by mridul ahuja
    HOW TO USE :
        >> Load JQuery library
        >> Load autocomplete-lite.js library
        >> Initialize autocomplete on element and pass autocomplete list as an array

           EXAMPLE :
               <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
               <script src="autocomplete-lite.js"></script>
               <input type="text" id="autocomplete_input">
               <script>
                   // initializing
                   $('#autocomplete_input').autocomplete_init(["aaa", "bbb", "ccc", "ddd", "eee", "fff"]);
               </script>
*/
console.log('Auto fill loaded')
var mridautocomplete_timer = 0;

jQuery.fn.extend({
    autocomplete_init: function (data) {
        me = $(this);
        mridautocomplete(me, data);
    }
});


function mridautocomplete(input, data) {

    if(!$('#mridautocomplete_css').length )
    {
        $('body').prepend('<style id="mridautocomplete_css">.mridautocomplete-list::-webkit-scrollbar{width: 12px;}.mridautocomplete-list::-webkit-scrollbar-track{-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);border-radius: 2px;}.mridautocomplete-list::-webkit-scrollbar-thumb{border-radius: 2px;-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5);}</style>');
    }

    var dataList = [];

    if (Array.isArray(data)) {
        dataList = dataList.concat(data);
    } else {
        console.error('Error : autocomplete-lite takes an array as parameter. ' + typeof data + ' given');
        return;
    }

    var matchData = function(input, dataList) {
        var reg = new RegExp(input.split('').join('\\w*').replace(/\W/, ""), 'i');
        return dataList.filter(function(data) {
            if (data.match(reg)) {
                return data;
            }
        });
    };

    var changeInput = function(input, dataList) {
        var val = input.val().toLowerCase();
        var inputAncestor = input.parent();
        var res = inputAncestor.find('.mridautocomplete-list');
        while(res.length == 0) {
            inputAncestor = inputAncestor.parent();
            res = inputAncestor.find('.mridautocomplete-list');
        }

        res.empty().hide();


        var autoCompleteResult = matchData(val, dataList);
        if (val == "" || autoCompleteResult.length == 0) {
            return;
        }

        autoCompleteResult.forEach(function(e) {
            var p = $('<p />');
            p.css({
                'margin': '0px',
                'padding-left': parseInt(input.css('padding-left'),10) + parseInt(input.css('border-left-width'),10),
                'text-align': 'left',
                'font-size': input.css('font-size'),
                'cursor': 'default'
            });

            p.addClass('mrid-autocomplete-item');

            e = e.toLowerCase();

            if(e.includes(val))
            {
                var first_part = e.split(val)[0];
                var second_part = e.split(val).splice(1).join(val);

                p.html(first_part + '<span style="color: #4682B4; font-weight: bold;">' + val + '</span>' + second_part);
            }

            p.click(function() {
                input.val(p.text());
                res.empty().hide();
            })
            p.mouseenter(function() {
                $(this).css("background-color", "#DCDCDC");
            }).mouseleave(function() {
                $(this).css("background-color", "white");
            });
            res.append(p);
        });

        res.css({
            'left': input.position().left,
            'width': input.css('width'),
            'position': 'absolute',
            'background-color': "white",
            'border': '1px solid #dddddd',
            'max-height': '250px',
            'overflow': 'scroll',
            'overflow-x': 'hidden',
            'font-family': input.css('font-family'),
            'font-size' : input.css('font-size'),
            'z-index' : '10'
        }).insertAfter(input).show();
    };

    var res = $("<div class='mridautocomplete-list' />");
    res.insertAfter(input);

    input.keyup(function() {
        clearTimeout(mridautocomplete_timer);
        mridautocomplete_timer = setTimeout(function() {
            changeInput(input, dataList);
        }, 100);
    });

    input.keydown(function() {
        clearTimeout(mridautocomplete_timer);
    });

    function search(input, dataList) {
        console.log(mridautocomplete_srch);
    }

    $(document).click(function(event) {
        if (!$(event.target).closest('.mridautocomplete-list').length) {
            res.empty().hide();
        }
    });
}
