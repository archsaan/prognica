var DV = (function () {
    var data = null;
    var controlls = {
        activeCanvas: 0,
        overCanvas: null,
        key: null,
        pressedPosition: null,
        pressedCanvas: null,
        fullscreen: false,
        requestInterval: null,
        requestList: []
    };
    var enabledOptions = {};

    var canvasObjects = {
        0: null,
        1: null,
        2: null,
        3: null
    };
    var common = {};
    common.subscribeRequest = function (response, canvasId, type) {
        console.log('AWAIT', arguments);
        controlls.requestList.push({
            date: (new Date()).getTime(),
            requestId: response.request_id,
            canvasId: canvasId,
            type: type
        });
        if (!controlls.requestInterval) {
            controlls.requestInterval = setInterval(DV.common.checkRequests, 2000);
        }
    };
    common.checkRequests = function () {
        console.log(DV.controlls.requestList);
        if (!DV.controlls.requestList || DV.controlls.requestList.length === 0) {
            clearInterval(DV.controlls.requestInterval);
            DV.controlls.requestInterval = false;
            return false;
        }
        fetch('/dicom/requests', {
            method: 'POST',
            body: {}
        }).then(function (response) {
            return response.json();
        }).then(function (response) {
            if (response.request_response) {
                DV.common.showMessage(response.request_response);
            }
            if (response.examinations) {
                DV.data(response.examinations);
                DV.common.showExaminations();
            }
            var now = (new Date()).getTime();
            for (var i = 0; i < DV.controlls.requestList.length; i++) {
                if (response.data[DV.controlls.requestList[i].requestId]) {
                    if (DV.controlls.requestList[i].type === 'open') {
                        // DV.openSeries(
                        //     response.data[DV.controlls.requestList[i].requestId][1]['id'],
                        //     response.data[DV.controlls.requestList[i].requestId][1]['id'],
                        //     DV.controlls.requestList[i].canvasId);
                    } else {
                        DV.canvasObjects[DV.controlls.requestList[i].canvasId].handleRequest(response.data[DV.controlls.requestList[i].requestId], DV.controlls.requestList[i].type, DV.controlls.requestList[i].requestId);
                    }
                    DV.controlls.requestList = DV.controlls.requestList.filter(function (e) {
                        return e.requestId !== DV.controlls.requestList[i].requestId;
                    });
                } else if (DV.controlls.requestList[i].date < now - 60000) {
                    DV.controlls.requestList = DV.controlls.requestList.filter(function (e) {
                        return e.requestId !== DV.controlls.requestList[i].requestId;
                    });
                }
            }
        });
    };
    common.awaitRequestResponse = function (response, canvasId, type) {
        if (response.request_response === '') {
            DV.common.subscribeRequest(response, canvasId || +$('.border-active .dv-canvas').attr('id').substr(-1), type);
        }
    };

    common.showExaminations = function () {
        var examinations = Object.keys(DV.data());
        $('#dv-loaded-examinations').empty();
        var i = 0;
        Object.keys(DV.data()).forEach(function (element) {
            if (!DV.data()[element].series) {
                return false;
            }
            $('#dv-loaded-examinations').prepend(
                    '<div id="dv-ex-' + i + '-lbl" class="w-100 mt-4"><a href="#" class="d-block text-white dv-menu-ex-name collapsed" data-toggle="collapse" data-target="#dv-ex-' + i + '" aria-expanded="false" aria-controls="dv-ex-' + i + '">' + DV.data()[element].id + '</a></div>' +
                    '<div id="dv-ex-' + i + '" class="collapse w-100" aria-labelledby="dv-ex-' + i + '-lbl" data-parent="#dv-loaded-examinations"><div class="d-flex flex-column flex-wrap dv-examination-preview ">' +
                    Object.keys(DV.data()[element].series).map(function (v, i) {
                if (!DV.data()[element].series['' + v] || !DV.data()[element].series['' + v]['orig'].length) {
                    return '<img src="" alt="Open" />';
                }
                return '<div data-series-id="' + v + '" data-examination-id="' + DV.data()[element].id + '"><img src="' + DV.data()[element].path + '/series/' + v + '/thumbnail.png" alt=""/><span>' + i + '/' + DV.data()[element].series['' + v]['orig'].length + '</span> </div>';
            }).join('') +
                    '</div></div>');
            i++;
        });
        $('[data-examination-id][data-series-id]').on('click', function () {
            var seriesId = $(this).data('series-id');
            var examinationId = $(this).data('examination-id');
            if (!isNaN(seriesId) && examinationId) {
                DV.openSeries(examinationId, seriesId);
            }
        });
    };

    common.showSearchExaminations = function () {
        var examinations = Object.keys(DV.data());
        $('#dv-search-examinations').empty();
        for (var i = 0; i < examinations.length; i++) {
            if (!DV.data()[examinations[i]].series) {
                continue;
            }
            $('#dv-search-examinations').prepend(
                    '<div id="dv-ex-' + i + '-lbl" class="w-100 mt-4"><a href="#" class="d-block text-white dv-menu-ex-name collapsed" data-toggle="collapse" data-target="#dv-ex-' + i + '" aria-expanded="false" aria-controls="dv-ex-' + i + '">' + DV.data()[examinations[i]].id + '</a></div>' +
                    '<div id="dv-ex-' + i + '" class="collapse w-100" aria-labelledby="dv-ex-' + i + '-lbl" data-parent="#dv-search-examinations"><div class="d-flex flex-column flex-wrap dv-examination-preview ">' +
                    Object.keys(DV.data()[examinations[i]].series).map(function (v, i) {
                if (!DV.data()[examinations[i]].series['' + v] || !DV.data()[examinations[i]].series['' + v].length) {
                    return '';
                }
                return '<div data-series-id="' + v + '" data-examination-id="' + DV.data()[examinations[i]].id + '"><img src="' + DV.data()[examinations[i]].path + '/series/' + v + '/orig/thumbnail.png" alt=""/><span>' + i + '/' + DV.data()[examinations[i]].series['' + v].length + '</span> </div>';
            }).join('') +
                    '</div></div>')
        }
    }

    var openExamination = function (e) {
        e.preventDefault();
        var formData = new FormData();
        for (var i = 0; i < this.files.length; i++) {
            formData.append('files[]', this.files[i], this.files[i].name);
        }
        fetch('/dicom/upload', {
            method: 'POST',
            body: formData
        }).then(function (response) {
            return response.json();
        }).then(function (response) {
            if (response.request_error) {
                console.warn(response.request_error);
            }
            DV.common.awaitRequestResponse(response, null, 'open');
        });
    };
    var toggleFullscreen = function () {
        // toggleFullScreenButtons();
        // toggleHeaderFooter();

        if (!document.fullscreenElement && // alternative standard method
                !document.mozFullScreenElement && !document.webkitFullscreenElement) {  // current working methods
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen();
            } else if (document.documentElement.mozRequestFullScreen) {
                document.documentElement.mozRequestFullScreen();
            } else if (document.documentElement.webkitRequestFullscreen) {
                document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            }
        } else {
            if (document.cancelFullScreen) {
                document.cancelFullScreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            }
        }
    };

    var setMenu = function () {
    };
    var setActiveCanvas = function (element) {
        $('.border-active').removeClass('border-active');

        if (!$(element).hasClass('preview-window')) {
            element = $(element).parents('.preview-window');
        }
        if ($(element).length === 0) {
            return false;
        }
        $(element).addClass('border-active');
        controlls.activeCanvas = +$('.dv-canvas', element).attr('id').substr(-1);
        if (DV.activeCanvas()) {
            DV.activeCanvas().menuOptionsActive();
        }
    };
    var openSeries = function (examinationId, seriesId, canvasId) {
        if (!isNaN(canvasId)) {
            DV.setActiveCanvas($('#dv-canvas-' + canvasId).parent())
        }
        if (DV.activeCanvas()) {
            DV.activeCanvas().clearAll();
            DV.activeCanvas().destruct();
        }
        canvasObjects[controlls.activeCanvas] = DVSeries(controlls.activeCanvas, examinationId, seriesId);
        DV.common.toggleFullPreview(DV.activeCanvas().$dvCanvas, true);
        DV.activeCanvas().menuOptionsActive();
        $('.dv-search-tab').toggleClass('w-0', false);
    };

    var wheelHandler = function (e) {
        if (DV.activeCanvas() === null) {
            return false;
        }
        if (e.altKey) { // alt - rotate
        } else if (e.shiftKey) { // shift - rotate
            if (DV.controlls.overCanvas === null) {
                return false;
            }
            e.preventDefault();
            DV.activeCanvas().rotate(e.deltaY > 0 ? 15 : -15);
        } else if (e.ctrlKey) { // ctrl - zoom in/out
            if (DV.controlls.overCanvas === null) {
                return false;
            }
            e.preventDefault();
            DV.activeCanvas().zoom(e.deltaY > 0 ? -0.1 : 0.1);
        } else if (DV.overCanvas() !== null) {// sam scroll - slices
            e.preventDefault();
            if (DV.activeCanvas()) {
                DV.activeCanvas().scroll(e.deltaY > 0 ? -1 : 1);
            }
        }
    };
    var wheelDefine = function () {
        window.addWheelListener = function (elem, callback, useCapture) {
            _addWheelListener(elem, support, callback, useCapture);
            if (support == "DOMMouseScroll") {
                _addWheelListener(elem, "MozMousePixelScroll", callback, useCapture);
            }
        };

        var prefix = "", _addEventListener, support;
        if (window.addEventListener) {
            _addEventListener = "addEventListener";
        } else {
            _addEventListener = "attachEvent";
            prefix = "on";
        }
        support = "onwheel" in document.createElement("div") ? "wheel" : document.onmousewheel !== undefined ? "mousewheel" : "DOMMouseScroll";

        function _addWheelListener(elem, eventName, callback, useCapture) {
            elem[_addEventListener](prefix + eventName, support == "wheel" ? callback : function (originalEvent) {
                !originalEvent && (originalEvent = window.event);
                var event = {
                    originalEvent: originalEvent,
                    target: originalEvent.target || originalEvent.srcElement,
                    type: "wheel",
                    deltaMode: originalEvent.type == "MozMousePixelScroll" ? 0 : 1,
                    deltaX: 0,
                    deltaY: 0,
                    deltaZ: 0,
                    preventDefault: function () {
                        originalEvent.preventDefault ?
                                originalEvent.preventDefault() :
                                originalEvent.returnValue = false;
                    }
                };
                if (support == "mousewheel") {
                    event.deltaY = -1 / 40 * originalEvent.wheelDelta;
                    originalEvent.wheelDeltaX && (event.deltaX = -1 / 40 * originalEvent.wheelDeltaX);
                } else {
                    event.deltaY = originalEvent.deltaY || originalEvent.detail;
                }
                return callback(event);
            }, useCapture || false);
        }

    };
    common.showMessage = function (message) {
        $('.dv-message-box > div > div').html(message);
        $('.dv-message-box').fadeIn();
    };

    var init = function () {
        $('.dv-demo').hide();
        $('[data-examination-id][data-series-id]').on('click', function () {
            var seriesId = $(this).data('series-id');
            var examinationId = $(this).data('examination-id');
            if (!isNaN(seriesId) && examinationId) {
                DV.openSeries(examinationId, seriesId);
            }
        });
        $('.dv-preview > div').on('click', function () {
            setActiveCanvas(this);
        });
        $('.dv-preview').on('mouseover', function () {
            DV.controlls.overCanvas = +$('.dv-canvas', this).attr('id').substr(-1);
        }).on('mouseout', function () {
            DV.controlls.overCanvas = null;
        }).on('click', function (e) {
            if (DV.activeCanvas() && DV.activeCanvas().settings.options.annotation) {
                DV.activeCanvas().annotation(e);
            }
            if (DV.activeCanvas() && DV.activeCanvas().settings.options.measure) {//&& DV.pressedCanvas() && DV.pressedCanvas().settings.path === DV.activeCanvas().settings.path) {
                if (!DV.activeCanvas().settings.tempData['measureLine']) {
                    DV.activeCanvas().measure(e, true);
                } else {
                    DV.activeCanvas().measure(e, false);
                }
            }
            if (DV.activeCanvas() && DV.activeCanvas().settings.options.search) {
                DV.activeCanvas().search(e, true);
            }
            if (DV.activeCanvas()) {
                if (DV.activeCanvas().settings.options.search) {
                    DV.activeCanvas().search(e, false);
                }
            }
            DV.controlls.pressedCanvas = null;
        }).on('mousedown', function (e) {
            DV.controlls.pressedPosition = [e.pageX, e.pageY];
            DV.controlls.pressedCanvas = +$('.dv-canvas', this).attr('id').substr(-1);
        }).on('mouseup', function (e) {
            DV.controlls.pressedCanvas = null;
        }).on('mousemove', function (e) {
            e.preventDefault();
            if (DV.pressedCanvas() !== null && DV.pressedCanvas().settings.path === DV.activeCanvas().settings.path) {
                console.log('opt1', DV.controlls.pressedCanvas);
                if (DV.activeCanvas().settings.options.search) {
                    DV.activeCanvas().search(e);
                }
            } else if (DV.activeCanvas()) {
                if (DV.activeCanvas().settings.options.measure && DV.activeCanvas().settings.tempData['measureText']) {
                    DV.activeCanvas().measure(e);
                }
            }
        });

        document.addEventListener('keyup', function () {
            DV.controlls.key = null;
        });
        document.addEventListener('keydown', function (ev) {
            DV.controlls.key = ev.which || ev.keyCode;
            switch (DV.controlls.key) {
                case 27:
                    DV.activeCanvas().clearTemp();
                    DV.activeCanvas().drawScroll();
                    break;
                case 37: // left
                    if (DV.overCanvas()) {
                        DV.overCanvas().openImage(DV.activeCanvas().settings.currentSlice - 1);
                    }
                    break;
                case 39: // right
                    if (DV.overCanvas()) {
                        DV.overCanvas().openImage(DV.activeCanvas().settings.currentSlice + 1);
                    }
                    break;
            }
        });
        document.querySelectorAll('.dv-preview .dv-canvas').forEach(function (e) {
            e.addEventListener("dblclick", function () {
                //alert('double click');
                DV.common.toggleFullPreview(this);
            });
        });

        common.toggleFullPreview = function (dvCanvas, forceFull) {
            console.log(dvCanvas)
            if (DV.activeCanvas().settings.options.search) {
                return false;
            }
            if (!forceFull && $(dvCanvas).parent().hasClass('mw-100')) {
                //alert('for all');
                $('.dv-canvas').parent().removeClass('d-none');
                $(dvCanvas).parent().removeClass('w-100 mw-100 h-100');
            } else {
                DV.activeCanvas().clearTemp();
                DV.activeCanvas().drawScroll();
                $('.dv-preview > div').addClass('d-none');
                $(dvCanvas).parent().addClass('w-100 mw-100 h-100 aaaaa').removeClass('d-none')
            }
        };

        document.addEventListener("wheel", wheelHandler);// function(e){
        //     console.log(e.altKey, e.ctrlKey, e.shiftKey, e.deltaX, e.wheelDelta, e.deltaMode)
        // });

        // wheelDefine();
        // addWheelListener(document, wheelHandler);

        // document.addEventListener("fullscreenchange", DV.toggleFullscreen, false);
        // document.addEventListener("mozfullscreenchange", DV.toggleFullscreen, false);
        // document.addEventListener("webkitfullscreenchange", DV.toggleFullscreen, false);
        // wheelHandler();

        $('.t-icon-full-screen').on('click', function () {
            function toggleFull() {
                if (!document.fullscreenElement && // alternative standard method
                        !document.mozFullScreenElement && !document.webkitFullscreenElement) {  // current working methods
                    if (document.documentElement.requestFullscreen) {
                        document.documentElement.requestFullscreen();
                    } else if (document.documentElement.mozRequestFullScreen) {
                        document.documentElement.mozRequestFullScreen();
                    } else if (document.documentElement.webkitRequestFullscreen) {
                        document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                    }
                } else {
                    if (document.cancelFullScreen) {
                        document.cancelFullScreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.webkitCancelFullScreen) {
                        document.webkitCancelFullScreen();
                    }
                }
            }

            toggleFull();
        });

        $('.dv-search-tab > p').on('click', function () {
            $('.dv-search-tab').toggleClass('w-0');
        });
        $('.t-icon-zoom-in').on('click', function () {
            if (DV.activeCanvas()) {
                DV.activeCanvas().zoom(0.1);
            }
        });
        $('.t-icon-export').on('click', function () {
            if (DV.activeCanvas()) {
                DV.activeCanvas().export();
            }
        });
        $('.t-icon-zoom-out').on('click', function () {
            if (DV.activeCanvas()) {
                DV.activeCanvas().zoom(-0.1);
            }
        });
        $('.t-icon-rotate-left').on('click', function () {
            if (DV.activeCanvas()) {
                DV.activeCanvas().rotate(-15);
            }
        });
        $('.t-icon-rotate-right').on('click', function () {
            if (DV.activeCanvas()) {
                DV.activeCanvas().rotate(15);
            }
        });
        $('.t-icon-flip-horizontal').on('click', function () {
            if (DV.activeCanvas()) {
                DV.activeCanvas().flip('x');
            }
        });
        $('.t-icon-flip-vertical').on('click', function () {
            if (DV.activeCanvas()) {
                DV.activeCanvas().flip('y');
            }
        });

        $('.t-icon-fit-to-screen').on('click', function () {
            if (DV.activeCanvas()) {
                DV.activeCanvas().moveToInit();
                DV.activeCanvas().zoom(0);
                DV.activeCanvas().rotate(true);
                DV.activeCanvas().flip(true);
                DV.activeCanvas().drawScroll();
                DV.activeCanvas().$dvCanvas.removeClass('invert');
                DV.activeCanvas().$dvCanvas.removeClass('magnify');
                $('.t-icon-magnify').removeClass('active');
                $.removeData(DV.activeCanvas().$canvas.find('svg'), 'elevateZoom');
                $('.zoomContainer').remove();
                DV.activeCanvas().$dvCanvas.removeClass('adjust');
                DV.activeCanvas().$dvCanvas.find('svg').css("-webkit-filter", '');
                $('.adjust-slider').hide();
            }
        });

        $('.t-icon-negative').on('click', function () {
            if (DV.activeCanvas()) {
                DV.activeCanvas().negative();
            }
        });

        $('.t-icon-measure').on('click', function () {
            if (DV.activeCanvas()) {
                DV.activeCanvas().toggleOption('measure');
                if (DV.activeCanvas().settings.options.measure) {
                    DV.activeCanvas().$canvas.addClass('cursor-move');
                } else {
                    DV.activeCanvas().$canvas.removeClass('cursor-move');
                }
            }
        });
        $('.t-icon-annotation').on('click', function () {
            if (DV.activeCanvas()) {
                DV.activeCanvas().toggleOption('annotation');
                if (DV.activeCanvas().settings.options.annotation) {
                    DV.activeCanvas().$canvas.addClass('cursor-move');
                } else {
                    DV.activeCanvas().$canvas.removeClass('cursor-move');
                }
            }
        });
        $('.t-icon-search').on('click', function () {
            if (DV.activeCanvas()) {
                DV.activeCanvas().toggleOption('search');
                if (!DV.activeCanvas().settings.options.search) {
                    $('.dv-search-tab').addClass('d-none');
                    $('#dv-search-area').fadeOut(function () {
                        $(this).empty()
                    });
                    DV.activeCanvas().$canvas.removeClass('cursor-move');
                } else {
                    DV.activeCanvas().clearAll();
                    DV.common.toggleFullPreview(DV.activeCanvas().$dvCanvas, true);
                    DV.activeCanvas().toggleOption('search');
                    DV.activeCanvas().$canvas.addClass('cursor-move');
                }
                DV.activeCanvas().anomalies();
                DV.activeCanvas().drawScroll();
            }
        });
        $('.t-icon-rectify').on('click', function () {
            if (DV.activeCanvas()) {
                DV.activeCanvas().toggleOption('autoRotate');
                DV.activeCanvas().autoRotate();
            }
        });

        $('.t-icon-classification, .dv-image-classification > span').on('click', function classificationClick() {
            if (DV.activeCanvas()) {
                DV.activeCanvas().toggleOption('classification');
                DV.activeCanvas().classification();
            }
        });

        $('.t-icon-density').on('click', function () {
            if (DV.activeCanvas()) {
                if (!DV.activeCanvas().settings.options.anomalies) {
                    DV.activeCanvas().clearAll();
                }
                DV.activeCanvas().toggleOption('anomalies');
                DV.activeCanvas().anomalies();
            }
        });
        $('.files-dicom-input').on('change', openExamination);
        $('.t-icon-magnify').on('click', function () {
            if (DV.activeCanvas()) {
                DV.activeCanvas().magnify();
                if (DV.activeCanvas().$dvCanvas.hasClass('magnify')) {
                    DV.activeCanvas().$canvas.find('svg').elevateZoom({
                        zoomType: "lens",
                        lensShape: "round",
                        lensSize: 200
                    });
                    $(this).addClass('active');
                } else if (!DV.activeCanvas().$dvCanvas.hasClass('magnify')) {
                    $.removeData(DV.activeCanvas().$canvas.find('svg'), 'elevateZoom');
                    $('.zoomContainer').remove();
                    $(this).removeClass('active');
                }
            }
        });
        $('.t-icon-adjust').on('click', function () {
            if (DV.activeCanvas()) {
                DV.activeCanvas().adjust();
                if (DV.activeCanvas().$dvCanvas.hasClass('adjust')) {
                    $('.adjust-slider').show();
                    var slider = $('.range-slider'),
                            range = $('.range-slider__range'),
                            value = $('.range-slider__value');

                    slider.each(function () {
                        value.each(function () {
                            var value = $(this).prev().attr('value');
                            DV.activeCanvas().$dvCanvas.find('svg').css("-webkit-filter", "brightness(" + value + "%)");
                            $(this).html(value);
                        });

                        range.on('input', function () {
                            DV.activeCanvas().$dvCanvas.find('svg').css("-webkit-filter", "brightness(" + this.value + "%)");
                            $(this).next(value).html(this.value);
                        });
                    });
                } else if (!DV.activeCanvas().$dvCanvas.hasClass('adjust')) {
                    $('.adjust-slider').hide();
                }
            }
        });
    }();
    return {
        enabledOptions: enabledOptions,
        canvasObjects: canvasObjects,
        controlls: controlls,
        activeCanvas: function () {
            return DV.controlls.activeCanvas !== null ? DV.canvasObjects[DV.controlls.activeCanvas] : null;
        },
        overCanvas: function () {
            return DV.controlls.overCanvas !== null ? DV.canvasObjects[DV.controlls.overCanvas] : null;
        },
        pressedCanvas: function () {
            return DV.controlls.pressedCanvas !== null ? DV.canvasObjects[DV.controlls.pressedCanvas] : null;
        },
        data: function (d) {
            if (d) {
                data = d;
            }
            for (var i = 0; i < 4; i++) {
                if (DV.canvasObjects[i]) {
                    DV.canvasObjects[i].settings.data = data[DV.canvasObjects[i].settings.data.id];
                }
            }
            return data;
        },
        toggleFullscreen: toggleFullscreen,
        openSeries: openSeries,
        setActiveCanvas: setActiveCanvas,
        common: common
    };
}());

function DVSeries(canvasId, examination, seriesKey) {
    var settings = {
        currentSeries: [],
        path: DV.data()[examination]['path'] + '/series/' + seriesKey,
        seriesKey: seriesKey, // 002, 004...
        subseriesDir: 'orig', // aligned, normalized...
        data: DV.data()[examination],
        zoom: 1,
        rotation: 0,
        disabled: {
            'autoRotate': !DV.data()[examination]['series'][seriesKey]['aligned'],
            'classification': !DV.data()[examination]['series'][seriesKey]['classification'],
            'anomalies': !DV.data()[examination]['series'][seriesKey]['classification'] || !DV.data()[examination]['series'][seriesKey]['classification']['default'],
            'search': !DV.data()[examination]['series'][seriesKey]['search']
        },
        currentImage: DV.data()[examination]['series'][seriesKey]['orig'][0],
        currentSlice: 0,
        imagePosition: [0, 0],
        cache: {},
        tempData: {search: {}, overButton: false},
        requests: [],
        slicesInformation: [],
        options: {
            'flipHorizontal': false,
            'flipVertical': false,
            'negative': false,
            'autoRotate': false,
            'search': false,
            'classification': false,
            'anomalies': false,
            'measure': false,
            'annotation': false,
            'magnify': false,
            'adjust': false
        }
    };

    var menu = {};
    var ui = {
        '$canvas': $('#dv-canvas-' + canvasId).parent(),
        '$dvCanvas': $('#dv-canvas-' + canvasId),
        '$dvSearchTab': $('.dv-search-tab')
    };
    ui = Object.assign({}, ui, {
        'canvasSVG': null,
        'imageSVG': null,
        'coverImageSVG': null,
        '$left-top': ui.$canvas.find('.dv-image-left-top'),
        '$right-top': ui.$canvas.find('.dv-image-right-top'),
        '$left-bottom': ui.$canvas.find('.dv-image-left-bottom'),
        '$right-bottom': ui.$canvas.find('.dv-image-right-bottom'),
        '$scroll': ui.$canvas.find('.dv-image-scroll'),
        '$ruler': ui.$canvas.find('.dv-image-ruler'),
        '$description': ui.$canvas.find('.dv-image-description'),
        '$classification': ui.$canvas.find('.dv-image-classification')
    });
    var common = {};

    ui.drawScroll = function () {
        ui.$scroll.empty();
        ui.$scroll.toggleClass('heatmap', settings.options.anomalies);
        var sliderWidth = 100 / common.getSeries('orig').length;

        if (settings.options.anomalies) {
            sliderWidth = 100 / common.getSeries().length;
        }

        var annotationList = {};
        for (var i in settings.data.annotations) {
            annotationList[settings.data.annotations[i].slice] = true;
        }

        for (var j = 0; j < common.getSeries('orig').length; j++) {
            var scrollStep = document.createElement('div');
            scrollStep.style.width = (sliderWidth + 0.01) + '%';
            scrollStep.style.left = (j * sliderWidth) + '%';
            scrollStep.onclick = function () {
                DV.activeCanvas().openImage(this.j);
            }.bind({j: j});
            if (settings.options.search) {
                var search = common.getSeries('search')[Object.keys(common.getSeries('search'))[0]];
                var cropSize = common.getSeries().length * search['query']['crop_size_x'];
                var centerSlice = Math.floor(common.getSeries().length * search['query']['crop_center'][2]);
                if (j > centerSlice - cropSize && j < centerSlice + cropSize) {
                    scrollStep.classList.add('annotation-search');
                }
            }
            if (settings.options.anomalies) {
                var scaleJ = parseInt(j / common.getSeries().length * common.getSeries('weights')['scores'].length);
                var scrollStepAnomalies = scrollStep.cloneNode(true);
                scrollStepAnomalies.style.opacity = (+common.getSeries('weights')['scores'][scaleJ] - 0.3) || 0;
                scrollStepAnomalies.onclick = function () {
                    DV.activeCanvas().openImage(this.j);
                }.bind({j: j});
                scrollStepAnomalies.classList.add('is-top');
                scrollStepAnomalies.title = 'Slice: ' + (j + 1) + ' | Heatmap: ' + common.getSeries('weights')['scores'][scaleJ].toFixed(2);
                ui.$scroll.append(scrollStepAnomalies);
            }
            if (settings.slicesInformation[j] || annotationList[j]) {
                scrollStep.classList.add('annotation');
            }
            scrollStep.title = 'Slice: ' + (j + 1);
            ui.$scroll.append(scrollStep);
        }
    };

    /* MENU options */
    menu.zoom = function (change) {
        if (DV.activeCanvas().settings.disabled['zoom-in']) {
            return false;
        }
        common.clearTemp();
        if (change === 0) {
            settings.zoom = 1;
        } else {
            settings.zoom += change;
            settings.zoom = settings.zoom >= 5 ? 5 : settings.zoom <= 1 ? 1 : settings.zoom;
        }
        //ui.drawRuler();
        ui.imageSVG.scale(settings.zoom, settings.zoom);
        ui.coverImageSVG.scale(settings.zoom, settings.zoom);
        // Object.keys(settings.tempData.measureData).forEach(function (slice) {
        //     settings.tempData.measureData[slice].forEach(function (element) {
        //         element.scale(settings.zoom, settings.zoom);
        //     });
        // });
        //ui.updateRightTop();
    };
    menu.rotate = function (change) {
        if (DV.activeCanvas().settings.disabled['rotate-left']) {
            return false;
        }
        common.clearTemp();
        if (change === true) {
            settings.rotation = 0;
        } else {
            settings.rotation += change;
        }
        ui.imageSVG.rotate(settings.rotation);
        ui.coverImageSVG.rotate(settings.rotation);
    };
    menu.flip = function (axis) {
        if (DV.activeCanvas().settings.disabled['flip-horizontal']) {
            return false;
        }
        common.clearTemp();
        if (axis === true) {
            settings.options.flipHorizontal = false;
            settings.options.flipVertical = false;
            ui.imageSVG.untransform();
            ui.coverImageSVG.untransform();
        } else if (axis === 'x') {
            settings.options.flipHorizontal = !settings.options.flipHorizontal;
            if (settings.options.flipHorizontal) {
                ui.imageSVG.flip(axis);
                ui.coverImageSVG.flip(axis);
            } else {
                ui.imageSVG.untransform();
                ui.coverImageSVG.untransform();
            }
        } else {
            settings.options.flipVertical = !settings.options.flipVertical;
            if (settings.options.flipVertical) {
                ui.imageSVG.flip(axis);
                ui.coverImageSVG.flip(axis);
            } else {
                ui.imageSVG.untransform();
                ui.coverImageSVG.untransform();
            }
        }
    };
    menu.scroll = function (change) {
        if (DV.activeCanvas().settings.disabled['scroll']) {
            return false;
        }
        settings.currentSlice = settings.currentSlice + change < 1 ? 1 : settings.currentSlice + change > common.getSeries('orig').length ? common.getSeries('orig').length : settings.currentSlice + change;
        DV.activeCanvas().openImage(settings.currentSlice);
        ui.openImage(settings.currentSlice);
    };
    menu.moveToInit = function () {
        ui.imageSVG.move(0, 0);
        ui.coverImageSVG.move(0, 0);
        DV.activeCanvas().settings.imagePosition = [0, 0];
        DV.controlls.pressedPosition = [0, 0];
        common.clearTemp();
    };
    menu.move = function (e) {
        if (DV.activeCanvas().settings.disabled['move'] || DV.activeCanvas().settings.disabled['pan']) {
            return false;
        }
        if (isNaN(DV.controlls.overCanvas) || settings.options.measure || settings.options.search) {
            return false;
        }
        e.preventDefault();
        var init = DV.controlls.pressedPosition;
        var position = DV.overCanvas().settings.imagePosition;
        position[0] += e.pageX - init[0];
        position[1] += e.pageY - init[1];

        ui.imageSVG.move(position[0], position[1]);
        ui.coverImageSVG.move(position[0], position[1]);
        DV.overCanvas().settings.imagePosition = position;
        DV.controlls.pressedPosition = [e.pageX, e.pageY];
    };
    menu.autoRotate = function () {
        if (settings.options.autoRotate) {
            settings.subseriesDir = 'aligned'
        } else {
            settings.subseriesDir = 'orig'
        }
        if (DV.activeCanvas().settings.disabled['autoRotate']) {
            return false;
        }
        DV.activeCanvas().openImage(settings.currentSlice);
    };
    menu.destruct = function () {
        $(ui.canvasSVG.node).remove();
    };


    menu.classification = function (element, force) {
        if (DV.activeCanvas().settings.disabled['classification']) {
            return false;
        }
        if (force === 0) {
            ui.$classification.removeClass('in');
        } else {
            // var c = settings.data.classification;
            ui.$classification.toggleClass('in');
            $('div:first-child', ui.$classification).empty();
            var hI = 0;
            for (var h in settings.data.series[settings.seriesKey]['classification']) {
                if (h === 'default') {
                    continue;
                }
                hI++;
                var value = parseInt(settings.data.series[settings.seriesKey]['classification'][h] * 100 % 100);
                $('.dv-image-classification > div', ui.$canvas).append(
                        '<div class="dv-classification-bar dv-bar-' + (parseInt(value / 10)) + '" style="--dv-bar-width: ' + value + '%">' + h + ' ' + value + '%</div>'
                        );
            }
            $('.dv-image-classification', ui.$canvas).css({'top': '-' + Math.round(hI * 3) + '.3rem'});
            $('.dv-image-classification > div', ui.$canvas).css('height', (hI * 3) + 'rem');
        }
    };
    menu.export = function () {
        var data = {
            // examination: settings.data.id,
            examinationName: settings.data.name,
            series: settings.seriesKey,
            annotations: settings.data.annotations,
            measure: settings.data.measure,
            categories: settings.data.categories,
            description: settings.data.description
        };
        fetch('/dicom/export', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json; charset=utf-8",
                // "Content-Type": "application/x-www-form-urlencoded",
            },
            redirect: "follow",
            body: JSON.stringify(data)
        }).then(function (response) {
            return response.json();
        }).then(function (response) {
            if (response.message) {
                DV.common.showMessage(response.message);
            }

        });
    };
    menu.showAnnotation = function (slice) {
        $('.annotation-box:not(.edit)').remove();
        if (settings.data.annotations) {
            var canvasOffset = common.getImageBrowserArea()[0];
            var imageSize = common.getImageBrowserSize();
            for (var i in settings.data.annotations) {
                if (settings.data.annotations[i].slice === slice) {
                    ui.$canvas.append('<div class="annotation-box" style="left: ' + (canvasOffset[0] + settings.data.annotations[i].left * imageSize[0]) + 'px; top: ' + (canvasOffset[1] + settings.data.annotations[i].top * imageSize[1]) + 'px;"><span>&times;</span>' + settings.data.annotations[i].text + '</div>');
                }
            }
            $('.annotation-box:not(.edit) > span').on('click', function () {
                $(this).parent().remove();
            });
        }
    };
    menu.annotation = function (event) {
        if (settings.disabled['annotation'] || !settings.options.annotation || $('.annotation-box > textarea').length) {
            return false;
        }
        //<span onclick="$('.annotation-box').remove()">&times;</span>
        ui.$canvas.append('<div class="annotation-box edit" style="left: ' + (event.pageX - ui.$canvas.offset().left) + 'px; top: ' + (event.pageY - -ui.$canvas.offset().top) + 'px;">' +
                '<button onclick="" class="btn btn-primary">Accept</button><span class="btn btn-primary">&times;</span>' +
                '<textarea placeholder="Enter description..."></textarea></div>');
        $('.annotation-box.edit > span').on('mouseup', function (e) {
            $(this).parent().remove();
        });
        $('.annotation-box.edit > button').on('mouseup', function (e) {
            var left = (event.pageX - ui.$dvCanvas.offset().left - common.getImageBrowserArea()[0][0]) / common.getImageBrowserSize()[0];
            var top = (event.pageY - ui.$dvCanvas.offset().top - common.getImageBrowserArea()[0][1]) / common.getImageBrowserSize()[1];
            settings.data.annotations.push({
                left: left,
                top: top,
                text: $('.annotation-box.edit textarea').val(),
                slice: settings.currentSlice
            });
            $(this).parent().remove();
            menu.showAnnotation(settings.currentSlice);
            ui.drawScroll();
        });
        $('.annotation-box textarea', ui.$canvas).focus();

        // if (settings.data.annotations && settings.data.annotations[sliceImage]) {
        //     var annotation = settings.data.annotations[sliceImage];
        //     ui.$canvas.append('<div class="annotation-box" style="left: ' + annotation.x + '; top: ' + annotation.y + ';"><span>&times;</span>' + annotation.text + '</div>');
        //     $('.annotation-box > span').on('click', function () {
        //         $(this).parent().remove();
        //     });
        // }
    };
    menu.measureImage = function (sliceImage) {
        if (!DV.activeCanvas().settings.slicesInformation) {
            return false;
        }
        Object.keys(DV.activeCanvas().settings.slicesInformation).forEach(function (slice) {
            if (+slice === +sliceImage) {
                DV.activeCanvas().settings.slicesInformation[slice].forEach(function (element) {
                    element.node.style.display = 'block';
                });
            } else {
                DV.activeCanvas().settings.slicesInformation[slice].forEach(function (element) {
                    element.node.style.display = 'none';
                });
            }
        });
    };
    menu.anomalies = function () {
        ui.drawScroll();
        if (settings.options.anomalies) {
            ui.coverImageSVG.node.style.display = 'block';
        } else {
            ui.coverImageSVG.node.style.display = 'none';
        }
        if (DV.activeCanvas().settings.disabled['anomalies']) {
            return false;
        }
        DV.activeCanvas().moveToInit();
        DV.activeCanvas().zoom(0);
        DV.activeCanvas().openImage(settings.currentSlice);
    };
    menu.negative = function () {
        if (DV.activeCanvas().settings.disabled['invert']) {
            return false;
        }
        ui.$dvCanvas.toggleClass('invert');
    };
    menu.magnify = function () {
        if (DV.activeCanvas().settings.disabled['magnify']) {
            return false;
        }
        ui.$dvCanvas.toggleClass('magnify');
    };
    menu.adjust = function () {
        if (DV.activeCanvas().settings.disabled['adjust']) {
            return false;
        }        
        ui.$dvCanvas.toggleClass('adjust');
    };
    menu.measure = function (e, status) {
        if (DV.activeCanvas().settings.disabled['measure']) {
            return false;
        }
        if (!settings.options.measure) {
            return false;
        }

        if (!settings.slicesInformation[settings.currentSlice]) {
            settings.slicesInformation[settings.currentSlice] = [];
        }
        var current = [e.pageX - ui.$canvas.offset().left, e.pageY - ui.$canvas.offset().top];
        if (settings.tempData['measureLine']) {
            var length = Math.round(Math.sqrt(Math.pow(settings.tempData['measureLine'].width(), 2) + Math.pow(settings.tempData['measureLine'].height(), 2)));
        }
        if (status === false) { // finish
            var label = 'Length: ' + (length / common.getImageBrowserSize()[1] * settings.data.metadata[settings.seriesKey]['orig']['size_mm'][1]).toFixed(2) + 'mm';
            settings.tempData['measureText'].text(label);
            settings.slicesInformation[settings.currentSlice].push(settings.tempData['measureLine']);
            settings.slicesInformation[settings.currentSlice].push(settings.tempData['measureText']);

            var x0 = (settings.tempData['measureBegin'][0] - common.getImageBrowserArea()[0][0]) / common.getImageBrowserSize()[0];
            var y0 = (settings.tempData['measureBegin'][1] - common.getImageBrowserArea()[0][1]) / common.getImageBrowserSize()[1];
            var x1 = (current[0] - common.getImageBrowserArea()[0][0]) / common.getImageBrowserSize()[0];
            var y1 = (current[1] - common.getImageBrowserArea()[0][1]) / common.getImageBrowserSize()[1];

            settings.data.measure.push({x0: x0, y0: y0, x1: x1, y1: y1, label: label, slice: settings.currentSlice, elementIndex: settings.slicesInformation[settings.currentSlice].length - 1});
            settings.tempData['measureLine'] = null;
            settings.tempData['measureText'] = null;
        } else if (status === true) { // begin
            settings.tempData['measureBegin'] = current;
            settings.tempData['measureLine'] = ui.canvasSVG.line([settings.tempData['measureBegin'], settings.tempData['measureBegin']]);
            settings.tempData['measureLine'].stroke({color: '#feb24c', width: 2});
            settings.tempData['measureText'] = ui.canvasSVG.text('Length: 0').move(current[0] + 5, current[1] - 5);
            settings.tempData['measureText'].font({size: '0.7rem', weight: 500}).fill('#feb24c');
        } else { // move second point
            settings.tempData['measureText'].text('Length: ' + (length / common.getImageBrowserSize()[1] * settings.data.metadata[settings.seriesKey]['orig']['size_mm'][1]).toFixed(2) + 'mm');
            if (e.ctrlKey) {
                var deg = Math.atan2(current[1] - settings.tempData['measureBegin'][1], current[0] - settings.tempData['measureBegin'][0]) * 180 / Math.PI;
                var m = Math.round(deg / 45) * 45;
                var theta = m / 180 * Math.PI;
                var r = Math.sqrt(Math.pow(current[1] - settings.tempData['measureBegin'][1], 2) + Math.pow(current[0] - settings.tempData['measureBegin'][0], 2));
                settings.tempData['measureLine'].plot([settings.tempData['measureBegin'], [settings.tempData['measureBegin'][0] + r * Math.cos(theta), settings.tempData['measureBegin'][1] + r * Math.sin(theta)]]);
            } else {
                settings.tempData['measureLine'].plot([settings.tempData['measureBegin'], [current[0], current[1]]]);
            }
        }
        ui.drawScroll();
    };
    menu.drawSearchArea = function (imageIndex) {
        if (!common.getSeries('search') || !settings.options.search) {
            $('.dv-selection-area').css('display', 'none');
            return false;
        }
        common.toggleOption('anomalies', false);
        var search = common.getSeries('search')[Object.keys(common.getSeries('search'))[0]];
        var cropSize = common.getSeries().length * search['query']['crop_size_x'];
        var centerSlice = Math.floor(common.getSeries().length * search['query']['crop_center'][2]);
        if (imageIndex > centerSlice - cropSize && imageIndex < centerSlice + cropSize) {
            ui.$dvSearchTab.removeClass('d-none');
            DV.activeCanvas().search_fake(Object.keys(common.getSeries('search'))[0]);
            $('.dv-selection-area').html('<button onclick="$(\'.dv-search-tab\').toggleClass(\'w-0\')" class="btn btn-primary">Search</button><span class="btn btn-primary">&times;</span>');
            var imageBrowserArea = common.getImageBrowserArea();
            var imageBrowserSize = common.getImageBrowserSize();
            //(offsetLeft + wielkoscObrazu * center[0] - (wielkoscObrazu * cropSize / 2))
            $('.dv-selection-area').css({
                display: 'block',
                left: ($('.border-active').offset().left + imageBrowserArea[0][0] + (search['query']['crop_center'][0] - (search['query']['crop_size_x'] / 2)) * imageBrowserSize[0]) + 'px',
                top: ($('.border-active').offset().top + imageBrowserArea[0][1] + (search['query']['crop_center'][1] - (search['query']['crop_size_x'] / 2)) * imageBrowserSize[1]) + 'px',
                // left: ((imageBrowserArea[0][0] + search['query']['crop_center'][0] * imageBrowserSize[0]) - (imageBrowserSize[0] * search['query']['crop_size_x']) / 2) + 'px',
                // top: ((imageBrowserArea[0][1] + search['query']['crop_center'][1] * imageBrowserSize[1]) - (imageBrowserSize[0] * search['query']['crop_size_x']) / 2) + 'px',
                width: (imageBrowserSize[0] * search['query']['crop_size_x']) + 'px',
                height: (imageBrowserSize[1] * search['query']['crop_size_x']) + 'px'
            });
        } else {
            ui.$dvSearchTab.addClass('d-none');
            $('.dv-selection-area').css('display', 'none');
        }
    };
    menu.search_fake = function (reqId) {
        var result = settings.data.series[seriesKey].search[reqId];
        // draw area
        // ok
        // show on bar
        ui.$dvSearchTab.removeClass('d-none').find('div').first().empty();
        var $searchTabDiv = $('.dv-search-tab > div');
        for (var res in result.results) {
            if (res > 6) {
                return false;
            }
            var r = result.results[res];
            var rand = r['score']; //Math.floor(Math.random() * 100);
            if (!r['description']) {
                r['description'] = '<span class="i">No description</span>';
            }
            $searchTabDiv.append('<div>No preview' +
                    '<div>' + r['description'].replace(/\\T\\nbsp;/gi, '').replace(/\\n/gi, '') + '<div class="dv-classification-bar dv-bar-' + Math.floor(rand / 10) + '" style="--dv-bar-width: ' + rand + '%">similarity ' + rand + '%</div></div></div>');
        }

    };
    menu.search = function (e, status) {
        return false;
    };
    menu.search_async = function (e, status) {
        if (settings.disabled['search']) {
            return false;
        }
        if (!settings.options.search || settings.tempData.overButton) {
            return false;
        }
        var current = [e.pageX - ui.$canvas.offset().left, e.pageY - -ui.$canvas.offset().top];
        if (status === false) { // finish
            var begin = settings.tempData['search']['begin'];
            if ($('.dv-selection-area').width() < 30 || $('.dv-selection-area').height() < 30) {
                $('.dv-selection-area').css('display', 'none');
                settings.tempData['search'] = {};
            } else if (begin && !settings.tempData['search']['finish']) {
                settings.tempData['search']['finish'] = current;
                $('.dv-selection-area').html('<button class="btn btn-primary">Search</button><span class="btn btn-primary" onclick="DV.activeCanvas().clearTemp()">&times;</span>');
                $('.dv-selection-area button').on('mousedown', function (e) {
                    DV.activeCanvas().settings.tempData.overButton = false;
                    DV.activeCanvas().sendSearch(current, begin);
                    $(this).html('<div class="loader10"></div>').off('mousedown');
                });
                // var imageLeft =  ui.$dvCanvas.width() -
                // settings.cache[imageIndex].width
            }
            // else{
            //     $('.dv-selection-area').css({display: 'none'});
            // }
        } else if (status === true && !settings.tempData['search']['begin']) {
            var iw = (ui.$dvCanvas.height() / settings.cache[settings.seriesKey + settings.subseriesDir][settings.currentSlice].height) * settings.cache[settings.seriesKey + settings.subseriesDir][settings.currentSlice].width;
            var imageBegin = (ui.$dvCanvas.width() - iw) / 2;
            if (imageBegin > current[0]) {
                return;
            }
            $('.dv-selection-area').appendTo(ui.$canvas);
            settings.tempData['search']['begin'] = current;
            $('.dv-selection-area').css({
                left: current[0] + 'px',
                top: current[1] + 'px',
                display: 'block',
                width: '1px',
                height: '1px'
            }).html('');
        } else if (settings.tempData['search']['begin'] && !settings.tempData['search']['finish']) {
            var width = (current[0] - settings.tempData['search']['begin'][0] - 3);
            if (width > 300) {
                width = 300;
            }
            $('.dv-selection-area').css({
                width: width + 'px',
                height: width + 'px'
            });
        }
    };
    common.getImageBrowserSize = function () {
        var imageScale = (ui.$dvCanvas.height() / settings.cache[settings.seriesKey + settings.subseriesDir][settings.currentSlice].height);
        var imageBrowserWidth = imageScale * settings.cache[settings.seriesKey + settings.subseriesDir][settings.currentSlice].width * settings.zoom;
        var imageBrowserHeight = imageScale * settings.cache[settings.seriesKey + settings.subseriesDir][settings.currentSlice].height * settings.zoom;
        return [imageBrowserWidth, imageBrowserHeight];
    };
    common.getImageBrowserArea = function () {
        var imageSize = common.getImageBrowserSize();
        var dvWidth = ui.$dvCanvas.width();
        var dvHeight = ui.$dvCanvas.height();
        var imageLeft = (dvWidth - imageSize[0]) / 2;
        var imageRight = imageLeft + imageSize[0];
        return [[imageLeft < 0 ? 0 : imageLeft, 0], [imageRight > dvWidth ? dvWidth : imageRight, dvHeight]];
    };

    common.menuOptionsActive = function () {
        var list = ['zoom-in', 'fit-to-screen', 'full-screen', 'negative', 'pan', 'zoom-out', 'density', 'measure', 'flip-vertical', 'flip-horizontal', 'autoRotate', 'rotate-right', 'rotate-left', 'annotation', 'anomalies', 'search', 'classification', 'fullScreen'];
        list.forEach(function (option) {
            if (settings.options[option]) {
                $('.t-icon-' + option).addClass('icon-active');
            } else {
                $('.t-icon-' + option).removeClass('icon-active');
            }
        });
    };

    common.disableMenuTile = function (option, enableOption) {
        settings.disabled[option] = !enableOption;
        if (enableOption) {
            $('.t-icon-' + option).removeClass('t-icon-disabled');
        } else {
            $('.t-icon-' + option).addClass('t-icon-disabled');
        }
    };

    common.disableMenuTiles = function (option, enableMenu) {
        var list = [];
        switch (option) {
            case 'search':
                list = ['zoom-in', 'pan', 'zoom-out', 'density', 'measure', 'flip-vertical', 'flip-horizontal', 'autoRotate', 'rotate-right', 'rotate-left', 'annotation', 'anomalies', 'magnify', 'adjust'];
                break;
            case 'annotation':
                list = ['zoom-in', 'pan', 'zoom-out', 'density', 'measure', 'flip-vertical', 'flip-horizontal', 'autoRotate', 'rotate-right', 'rotate-left', 'search', 'anomalies', 'magnify', 'adjust'];
                break;
            case 'init':
                list = ['zoom-in', 'fit-to-screen', 'full-screen', 'negative', 'pan', 'zoom-out', 'density', 'measure', 'flip-vertical', 'flip-horizontal', 'autoRotate', 'rotate-right', 'rotate-left', 'annotation', 'anomalies', 'search', 'classification', 'fullScreen', 'magnify', 'adjust'];
                break;
        }
        list.forEach(function (option) {
            common.disableMenuTile(option, enableMenu === undefined ? !settings.disabled[option] : enableMenu);
        });
    };

    common.clearAll = function () {
        DV.activeCanvas().clearTemp();
        DV.activeCanvas().turnOffAllOptions();
        DV.activeCanvas().autoRotate();
        DV.activeCanvas().moveToInit();
        DV.activeCanvas().zoom(0);
        DV.activeCanvas().flip(true);
        DV.activeCanvas().rotate(true);
        DV.activeCanvas().openImage(DV.activeCanvas().settings.currentSlice);
        DV.activeCanvas().drawScroll();
    };

    common.turnOffAllOptions = function () {
        Object.keys(DV.activeCanvas().settings.options).forEach(function (option) {
            DV.activeCanvas().settings.options[option] = false;
            $('.t-icon-' + option).removeClass('icon-active');
        });
    };

    common.sendSearch = function (current, begin) {
        var imageSize = common.getImageBrowserSize();
        var imageArea = common.getImageBrowserArea();

        var width = imageSize[0];
        var imageBegin = imageArea[0][0];
        var height = imageSize[1];

        var crop_center = [
            (begin[0] - imageBegin + (+$('.dv-selection-area').width() / 2)) / width,
            (begin[1] + (+$('.dv-selection-area').height() / 2)) / height,
            (settings.currentSlice / common.getSeries().length)
        ];

        var formData = new FormData();
        formData.append('search_data', JSON.stringify({
            'series_dir': 'series/' + seriesKey,
            'data_dir': DV.data()[examination]['path'],
            'crop_center': crop_center,
            'crop_size_x': $('.dv-selection-area').width() / width
        }));
        fetch('/dicom/search', {
            method: 'POST',
            // mode: "cors",
            // cache: "no-cache",
            // credentials: "same-origin",
            // headers: {
            //     "Content-Type": "application/json; charset=utf-8"
            // },
            body: formData
        }).then(function (response) {
            return response.json();
        }).then(function (response) {
            if (response.request_response) {
                DV.common.showMessage(response.request_response);
                DV.activeCanvas().clearTemp();
            }
            DV.common.awaitRequestResponse(response, canvasId, 'search');
        });
    };

    /* UI options */
    ui.drawRuler = function () {
        // 1 px = 0.264583mm
        ui.$ruler.html('<div>' + (0.6 * settings.data.metadata[settings.seriesKey]['orig']['size_mm'][1] / settings.zoom).toFixed(0) + ' mm</div>');
    };
    ui.showImageAdditions = function (imageIndex) {
        menu.showAnnotation(imageIndex);
        menu.measureImage(imageIndex);
        menu.drawSearchArea(imageIndex);
    };
    ui.updateLeftTop = function () {
        var imageSize = settings.data.metadata[settings.seriesKey]['orig']['size_mm'] ? Math.round(settings.data.metadata[settings.seriesKey]['orig']['size_mm'][0]) + 'x' + Math.round(settings.data.metadata[settings.seriesKey]['orig']['size_mm'][1]) + ' mm' : '&mdash;';
        var html = '<div>patient: <div class="ellipsis-100">' + (settings.data.metadata[settings.seriesKey]['patient_name'] || 'anonymous') + '</div>, ' + (settings.data.metadata[settings.seriesKey]['patient_sex'] || '&mdash;') + ', ' + (settings.data.metadata[settings.seriesKey]['patient_age'] || '&mdash;') + '</div>' +
                '<div>id: <div class="ellipsis-100">' + (settings.data.metadata[settings.seriesKey]['patient_id'] || '&mdash;') + '</div></div>' +
                '<div>image size: ' + imageSize + ' </div>' +
                '<div>date: ' + (settings.data.metadata[settings.seriesKey]['series_date'].substr(0, 15).split('').map(function (e, i) {
                    return [3, 5].indexOf(i) !== -1 ? e + '-' : ([10, 12].indexOf(i) !== -1 ? e + ':' : e);
                }).join('') || '&mdash;') + '</div>';
        ui['$left-top'].html(html);
    };
    ui.updateRightTop = function () {
        var html = '<div>zoom: ' + (settings.zoom * 100).toFixed(0) + '%</div>' +
                '<div>orientation: ' + (settings.data.orientation || '&mdash;') + '</div>';
        ui['$right-top'].html(html);
    };
    ui.updateRightBottom = function () {
        var slice = common.getSeries('orig').length;
        var html = '<div>slice: ' + ((settings.currentSlice || 0) + 1) + '/' + slice + '</div>';
        ui['$right-bottom'].html(html);
    };
    ui.coverImage = function (imageIndex) {
        imageIndex = parseInt(imageIndex / common.getSeries().length * settings.data.series[seriesKey]['classification']['default']['slices'].length);
        ui.coverImageSVG.load(settings.path + '/classification/heatmaps/default/' + settings.data.series[seriesKey]['classification']['default']['slices'][imageIndex]);
    };
    ui.openImage = function (imageIndex) {
        if (!common.getSeries() || !common.getSeries()[imageIndex] || settings.tempData.search.begin) {
            return false;
        }
        if (settings.options.anomalies) {
            common.openCacheImage(imageIndex, settings.seriesKey + settings.subseriesDir + 'anomalies', function () {
            }, common.getPath(imageIndex, 'anomalies'));
            ui.coverImage(imageIndex);
        }
        var path = common.getPath(imageIndex);
        if (settings.subseriesDir !== 'orig') {
            var tmp = parseInt(imageIndex / common.getSeries('orig').length * common.getSeries('aligned').length);
            path = common.getPath(tmp);
        }
        common.openCacheImage(imageIndex, settings.seriesKey + settings.subseriesDir, DV.activeCanvas().loadPart, path);
        ui.imageSVG.load(path);

        settings.currentSlice = imageIndex;
        ui.updateRightBottom();

        var sliderWidth = 100 / common.getSeries('orig').length;
        ui.$canvas.css({
            '--dv-scroll-pointer-left': sliderWidth * imageIndex + '%',
            '--dv-scroll-pointer-width': sliderWidth + '%'
        });

        ui.showImageAdditions(imageIndex);
        ui.$scroll.find('.current').css('left', (imageIndex / common.getSeries()[imageIndex].length) + '%');
    };

    /* COMMON options */
    common.getSeries = function (series) {
        // if (settings.options.anomalies) {
        //     return settings.data.series[settings.seriesKey]['heatmaps']['default'];
        // }
        if (series) {
            if (series === 'weights') {
                return settings.data.series[settings.seriesKey]['classification']['default'];
            } else if (series === 'search') {
                return settings.data.series[settings.seriesKey]['search'];
            }
            return settings.data.series[settings.seriesKey][series];
        }
        return settings.data.series[settings.seriesKey]['orig'];

    };
    common.getPath = function (imageIndex, type) {
        var path = null;
        if (!imageIndex) {
            imageIndex = 0;
        }
        // if (settings.options.anomalies) {
        //     path = settings.path + '/' + settings.subseriesDir + '/' + common.getSeries()[imageIndex];
        // }else
        if (settings.options.autoRotate) {
            path = settings.path + '/aligned/top/' + common.getSeries('aligned')[imageIndex];
        } else if (type === 'anomalies') {
            path = settings.path + '/classification/heatmaps/default/' + common.getSeries('weights')['slices'][imageIndex]
        } else {
            path = settings.path + '/' + settings.subseriesDir + '/' + common.getSeries()[imageIndex];
        }
        return path;
    };
    common.loadPart = function () {
        ui.$dvCanvas.addClass('loading');
        var from = settings.currentSlice < 25 ? 0 : settings.currentSlice - 25;
        var to = settings.currentSlice + 25 > common.getSeries().length ? common.getSeries().length : settings.currentSlice + 25;
        var step = 100 / to - from;
        var onload = function () {
            var progress = parseFloat(ui.$dvCanvas.css('--dv-loader-width')) || 0;
            ui.$dvCanvas.css('--dv-loader-width', progress + step + '%');
            if (progress + step >= 97) {
                ui.$dvCanvas.removeClass('loading');
                ui.$dvCanvas.css('--dv-loader-width', '0%');
            }
        };
        for (var i = from; i < to; i++) {
            if (!common.getSeries()[i]) {
                continue;
            }
            common.openCacheImage(i, settings.seriesKey + settings.subseriesDir, onload);
            if (settings.options.anomalies) {
                common.openCacheImage(i, settings.seriesKey + settings.subseriesDir + 'anomalies', onload, common.getPath(i, 'anomalies'));
            }
        }
        return true;
    };
    common.openCacheImage = function (i, key, onload, path) {
        if (!settings.cache[key]) {
            settings.cache[key] = [];
        }
        settings.cache[key][i] = new Image();
        settings.cache[key][i].onload = onload;
        settings.cache[key][i].src = path ? path : common.getPath(i);
    };
    common.processSeries = function () {
        var formData = new FormData();
        formData.append('path', settings.path);
        fetch('/dicom/open', {
            method: 'POST',
            // mode: "cors",
            // cache: "no-cache",
            // credentials: "same-origin",
            // headers: {
            //     "Content-Type": "application/json; charset=utf-8"
            // },
            body: formData
        }).then(function (response) {
            return response.json();
        }).then(function (response) {
            if (response.request_response) {
                DV.common.showMessage(response.request_response);
                DV.activeCanvas().clearTemp()
            }
            DV.common.awaitRequestResponse(response, canvasId, 'process');
        });
    };

    common.toggleOption = function (option, force) {
        if (settings.disabled[option]) {
            return false;
        }

        settings.options[option] = force === undefined ? !settings.options[option] : force;
        var ticons = ['zoom-in', 'pan', 'zoom-out', 'density', 'measure', 'flip-vertical', 'flip-horizontal', 'autoRotate', 'rotate-right', 'rotate-left', 'annotation', 'anomalies', 'search', 'classification', 'fullScreen'];
        common.disableMenuTiles(option, !settings.options[option]);
        for (var ti in ticons) {
            if (settings.options[ticons[ti]]) {
                $('.t-icon-' + ticons[ti]).addClass('icon-active');
            } else {
                $('.t-icon-' + ticons[ti]).removeClass('icon-active');
            }
        }
    };
    common.clearTemp = function () {
        Object.keys(settings.slicesInformation).forEach(function (slice) {
            settings.slicesInformation[slice].forEach(function (element) {
                element.remove();
            });
        });
        settings.data.annotations = [];
        settings.data.measure = [];
        settings.slicesInformation = {};
        $('.dv-selection-area').css({display: 'none'});
        settings.tempData['search'] = {};
    };
    common.awaitRequestResponse = function (response, type) {
        settings.requests[response.request_id] = type;
        DV.common.subscribeRequest(response.request_id, canvasId);
    };
    common.handleRequest = function (response, type, requestId) {
        DV.common.showExaminations();
        // console.log(response, type, requestId);
        switch (type) {
            case 'search':
                if (!DV.activeCanvas().settings.data.series[settings.seriesKey]['search'][requestId]) {
                    console.log('fail');
                    return;
                }
                // requestId = Object.keys(DV.activeCanvas().settings.data.series[settings.seriesKey]['search'])[Object.keys(DV.activeCanvas().settings.data.series[settings.seriesKey]['search']).length - 1];

                $('.dv-selection-area').fadeOut();
                $('#dv-search-examinations').empty();
                if (DV.activeCanvas().settings.data.series[settings.seriesKey]['search'][requestId]) {
                    var i = 0;
                    DV.activeCanvas().settings.data.series[settings.seriesKey]['search'][requestId]['results'].forEach(function (result) {
                        if (i > 4) {
                            return;
                        }
                        ;
                        $('#dv-search-examinations').append(
                                '<div id="dv-ex-' + i + '-lbl" class="w-100 mb-2"><a href="#" class="d-block text-white dv-menu-ex-name collapsed show" data-toggle="collapse" data-target="#dv-ex-' + i + '" aria-expanded="true" aria-controls="dv-ex-' + i + '">Result #' + (i + 1) + '</a></div>' +
                                '<div id="dv-ex-' + i + '" class="collapse show w-100" aria-labelledby="dv-ex-' + i + '-lbl" data-parent="#dv-search-examinations"><div class="d-flex flex-column flex-wrap dv-examination-preview ">' +
                                '<div data-search-path="' + result['series_dir'] + '"><img src="./' + result['thumbnail'].substr(result['thumbnail'].indexOf('uploads')) + '" alt=""/><span></span> </div>' +
                                '</div></div>');
                        i++;
                    });

                    // $('#dv-search-area [data-search-path]').on('click', function () {
                    //     DV.common.toggleFullPreview(DV.activeCanvas().$dvCanvas);
                    //
                    //     var formData = new FormData();
                    //     formData.append('path', $(this).data('search-path'));
                    //
                    //     fetch('/dicom/upload', {
                    //         method: 'POST',
                    //         body: formData
                    //     }).then(function (response) {
                    //         return response.json();
                    //     }).then(function (response) {
                    //         if (response.request_response) {
                    //             DV.common.showMessage(response.request_response);
                    //         } else {
                    //             if (settings.options.search) {
                    //                 $('.t-icon-search').click();
                    //             }
                    //             DV.common.awaitRequestResponse(response, null, 'open');
                    //         }
                    //     });
                    // });
                    $('#dv-search-area').fadeIn();
                    DV.activeCanvas().clearTemp();
                }
                break;
        }
        // settings.requests[response.request_id] = type;
        // DV.controlls.subscribeRequest(response.request_id, canvasId);
    };


    /* INIT function */
    var init = function () {
        ui.canvasSVG = SVG('dv-canvas-' + canvasId).size('100%', '100%').attr('data-zoom-image', settings.path + '/orig/' + settings.currentImage);
        common.openCacheImage(settings.currentSlice, settings.seriesKey + settings.subseriesDir, function () {
            // DV.activeCanvas().imageSVG.size(null, '100%');//.size('100%', '100%');
            // DV.activeCanvas().coverImageSVG.size(null, '100%');//.size('100%', '100%');
            setTimeout(function () {
                DV.activeCanvas().imageSVG.size('100%', '100%');
                DV.activeCanvas().coverImageSVG.size('100%', '100%');
            }, 200);
        });
        ui.imageSVG = ui.canvasSVG.image(settings.path + '/orig/' + settings.currentImage).size(null, '100%');
        ui.coverImageSVG = ui.canvasSVG.image('').size(null, '100%');
        ui.coverImageSVG.node.style.display = 'none';
        ui.imageSVG.draggable().on('dragmove', function (e) {
            if (settings.options.measure || settings.options.search) {
                e.preventDefault();
                DV.controlls.pressedPosition = [e.pageX, e.pageY];
                DV.controlls.pressedCanvas = +$(this.node.parentNode.parentNode).attr('id').substr(-1);

                // if (settings.data.measure) {
                //     settings.data.measure.filter(function(e){ return e.slice === settings.currentSlice }).forEach(function (e) {
                //
                //         element.move(DV.activeCanvas().coverImageSVG.attr('x') + DV.activeCanvas().getImageBrowserSize()[0] * e.x0, DV.activeCanvas().coverImageSVG.attr('y') + DV.activeCanvas().getImageBrowserSize()[1] * e.y0);
                //     });
                // }
            }
        });
        ui.coverImageSVG.draggable().on('dragmove', function (e) {
            if (settings.options.measure || settings.options.search) {
                e.preventDefault();
                DV.controlls.pressedPosition = [e.pageX, e.pageY];
                DV.controlls.pressedCanvas = +$(this.node.parentNode.parentNode).attr('id').substr(-1);
            }
            DV.activeCanvas().imageSVG.move(DV.activeCanvas().coverImageSVG.attr('x'), DV.activeCanvas().coverImageSVG.attr('y'));
        });
        //ui.drawRuler();
        //ui.updateLeftTop();
        //ui.updateRightTop();
        //ui.updateRightBottom();
        //common.loadPart();
        //ui.$classification.removeClass('in').empty().html('<div></div><span>auto classification &nbsp; <i class="fas fa-angle-down"></i></span>');
        $('span', ui.$classification).on('click', function () {
            if (DV.activeCanvas()) {
                DV.activeCanvas().toggleOption('classification');
                DV.activeCanvas().classification()
            }
        });
        ui.drawScroll();
        common.disableMenuTiles('init');
        ui.imageSVG.size(null, '100%');
        ui.coverImageSVG.size(null, '100%');
    }();

    return Object.assign({}, {settings: settings}, ui, menu, common);
}
