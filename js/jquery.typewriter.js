// jTypeWriter, JQuery plugin
// v 1.1 
// Licensed under GPL licenses.
// Copyright (C) 2008 Nikos "DuMmWiaM" Kontis, info@dummwiam.com
// http://www.DuMmWiaM.com/jTypeWriter
// ----------------------------------------------------------------------------
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
// 
// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.
// 
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
// THE SOFTWARE.
// ----------------------------------------------------------------------------

(function($) {
    $.fn.jTypeWriter = function (b) {
        var c, nIntervalCounter, nSequentialCounter, nSequentialCounterInternal, nInterval, nLoopInterval;
        var d = $.extend({}, $.fn.jTypeWriter.defaults, b);
        var e = d.duration * 1000;
        var f = d.type.toLowerCase();
        var g = d.sequential;
        var h = d.onComplete;
        var j = d.text;
        var k = d.loop;
        var l = d.loopDelay;
        var m = (f == "word") ? " " : ".";
        var n = new Array();
        var o = 0;
        for (i = 0; i < this.length; i++) {
            if (j) {
                $(this[i]).text(j)
            }
            if (f == "letter") n.push({
                obj: $(this[i]),
                initialText: $(this[i]).text()
            });
            else n.push({
                obj: $(this[i]),
                initialText: $(this[i]).text().split(m)
            });
            if (!g) o = n[i].initialText.length > o ? n[i].initialText.length : o;
            else o += n[i].initialText.length;
            $(this[i]).text("")
        }
        init();

        function init() {
            c = e / o;
            nIntervalCounter = 0;
            nSequentialCounter = nSequentialCounterInternal = 0;
            nInterval = (!g) ? setInterval(typerSimultaneous, c) : setInterval(typerSequential, c)
        };

        function typerSimultaneous() {
            nIntervalCounter++;
            for (i = 0; i < n.length; i++) {
                var a = n[i];
                if (a.initialText.length >= nIntervalCounter) {
                    if (f == "letter") {
                        a.obj.text(a.initialText.substr(0, nIntervalCounter))
                    } else {
                        a.obj.append(a.initialText[nIntervalCounter - 1]);
                        if (nIntervalCounter < o) {
                            a.obj.append(m)
                        }
                    }
                }
            }
            if (nIntervalCounter >= o) {
                circleEnd()
            }
        };

        function typerSequential() {
            $obj = n[nSequentialCounter];
            if (f == "letter") {
                $obj.obj.text($obj.initialText.substr(0, ++nSequentialCounterInternal))
            } else {
                $obj.obj.append($obj.initialText[nSequentialCounterInternal++]);
                if (nSequentialCounterInternal < $obj.initialText.length) $obj.obj.append(m)
            }
            if (nSequentialCounterInternal >= $obj.initialText.length) {
                nSequentialCounter++;
                nSequentialCounterInternal = 0
            }
            nIntervalCounter++;
            if (nIntervalCounter >= o) {
                circleEnd()
            }
        };

        function circleEnd() {
            clearInterval(nInterval);
            if (f != "letter") {}
            if (k) {
                if (l) nLoopInterval = setInterval(loopInterval, l * 1000);
                else newLoop()
            }
            h()
        };

        function newLoop() {
            for (i = 0; i < n.length; i++) {
                n[i].obj.text("")
            }
            init()
        };

        function loopInterval() {
            newLoop();
            clearInterval(nLoopInterval)
        };

        function endEffect() {
            clearInterval(nInterval);
            for (i = 0; i < n.length; i++) {
                n[i].obj.text(n[i].initialText)
            }
        };
        this.endEffect = endEffect;
        return this
    };
    $.fn.jTypeWriter.defaults = {
        duration: 30,
        type: "letter",
        sequential: true,
        onComplete: function () {},
        text: "",
        loop: false,
        loopDelay: 0
    };
    $.fn.jTypeWriter.variables = {
        aObjects: new Array()
    }
})(jQuery);