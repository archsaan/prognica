<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Prognica Labs</title>
        <link rel="shortcut icon" href="{{ asset('images/favicon.ico')  }}" />

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        {{ HTML::style('/vendors/bootstrap/css/bootstrap.css') }}
        <!-- Font Awesome -->
        {{ HTML::style('/vendors/font-awesome/css/font-awesome.css') }}

        <!-- Cropper Plugin -->
        {{ HTML::style('/vendors/cropper/dist/cropper.min.css') }}

    </head>
    <body>
        <!-- image cropping -->
        <div class="container cropper add-banner-container">
            <div class="row">
                <div class="col-md-9">
                    <div class="img-container">
                        @if(old('image'))
                        {{ HTML::image('images/upload/banner/'.old('image'), old('title'), array('id'=>'image'))}}
                        @else
                        <img id="image" src="#" alt="">
                        <label class="init-upload" for="inputImage" title="Upload image file" style="font-size: 30px; padding-top: 20%;">
                            <input type="file" class="sr-only" id="inputImage" name="file" accept="image/*" data-placeholder="allowed file extensions: jpg,png,gif" data-buttonText="Upload a image" data-iconName="fa fa-upload" data-buttonName="btn-warning" pattern="^.*\.(jpg|jpeg|gif|png)$">
                            <span class="docs-tooltip" data-toggle="tooltip" title="Import image">
                                <span class="fa fa-cloud-upload" style="font-size: 70px;"></span> <br/>
                                Click here to upload Image
                            </span>
                        </label>
                        @endif
                    </div>
                    <div>

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="docs-preview clearfix">
                        <div class="img-preview preview-lg"></div>
                        <div class="img-preview preview-md"></div>
                        <div class="img-preview preview-sm"></div>
                        <div class="img-preview preview-xs"></div>
                    </div>
                    <div class="docs-data">

                    </div>
                    <div class="row">
                        <div class="col-md-12 docs-buttons">
                            <!-- <h3 class="page-header">Toolbar:</h3> -->
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="move" class="docs-tooltip" data-toggle="tooltip" title="Move">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="Move">
                                        <span class="fa fa-arrows"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="crop" title="Crop" class="docs-tooltip" data-toggle="tooltip">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;setDragMode&quot;, &quot;crop&quot;)">
                                        <span class="fa fa-crop"></span>
                                    </span>
                                </button>
                            </div>

                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In" class="docs-tooltip" data-toggle="tooltip">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;zoom&quot;, 0.1)">
                                        <span class="fa fa-search-plus"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out" class="docs-tooltip" data-toggle="tooltip">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;zoom&quot;, -0.1)">
                                        <span class="fa fa-search-minus"></span>
                                    </span>
                                </button>
                            </div>

                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Move Left" class="docs-tooltip" data-toggle="tooltip">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;move&quot;, -10, 0)">
                                        <span class="fa fa-arrow-left"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Move Right" class="docs-tooltip" data-toggle="tooltip">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;move&quot;, 10, 0)">
                                        <span class="fa fa-arrow-right"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Move Up" class="docs-tooltip" data-toggle="tooltip">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;move&quot;, 0, -10)">
                                        <span class="fa fa-arrow-up"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Move Down" class="docs-tooltip" data-toggle="tooltip">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;move&quot;, 0, 10)">
                                        <span class="fa fa-arrow-down"></span>
                                    </span>
                                </button>
                            </div>
                            <br/>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Rotate Left" class="docs-tooltip" data-toggle="tooltip">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;rotate&quot;, -45)">
                                        <span class="fa fa-rotate-left"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Rotate Right" class="docs-tooltip" data-toggle="tooltip">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;rotate&quot;, 45)">
                                        <span class="fa fa-rotate-right"></span>
                                    </span>
                                </button>
                            </div>

                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" data-method="scaleX" data-option="-1" title="Flip Horizontal" class="docs-tooltip" data-toggle="tooltip">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;scaleX&quot;, -1)">
                                        <span class="fa fa-arrows-h"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1" title="Flip Vertical" class="docs-tooltip" data-toggle="tooltip">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;scaleY&quot;, -1)">
                                        <span class="fa fa-arrows-v"></span>
                                    </span>
                                </button>
                            </div>

                            <div class="btn-group">
                                <label class="btn btn-primary btn-upload" for="inputImage" title="Upload image file">
                                    <input type="file" class="sr-only" id="inputImage" name="file" accept="image/*" data-placeholder="allowed file extensions: jpg,png,gif" data-buttonText="Upload a image" data-iconName="fa fa-upload" data-buttonName="btn-warning" pattern="^.*\.(jpg|jpeg|gif|png)$" required="">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="Import image with Blob URLs">
                                        <span class="fa fa-upload"></span> Upload Image
                                    </span>
                                </label>
                                <button type="button" class="btn btn-primary" data-method="reset" title="Reset the canvas" class="docs-tooltip" data-toggle="tooltip">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="Reset">
                                        <span class="fa fa-refresh"></span> Reset
                                    </span>
                                </button>
                            </div>

                            <div class="btn-group btn-group-crop">
                                <button type="button" class="btn btn-primary" data-method="getCroppedCanvas" title="Download Cropped Image" class="docs-tooltip" data-toggle="tooltip">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;getCroppedCanvas&quot;)">
                                        <span class="glyphicon glyphicon-download-alt"></span> Download Cropped Image
                                    </span>
                                </button>
                            </div>

                            <!-- Show the cropped image in modal -->
                            <div class="modal fade docs-cropped" id="getCroppedCanvasModal" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" role="dialog" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="getCroppedCanvasTitle">Cropped</h4>
                                        </div>
                                        <div class="modal-body"></div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <a class="btn btn-primary" id="download" href="javascript:void(0);" download="cropped.png">Download</a>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.modal -->
                            </                                                                                                                                                                                                        div><!-- /.docs-buttons -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /image cropping -->

        </div>
        {{ HTML::script('/vendors/jquery/jquery.js') }}
        {{ HTML::script('/vendors/bootstrap/js/bootstrap.js')}}
        <!-- Cropper -->
        {{ HTML::script("/vendors/cropper/dist/cropper.min.js") }}
        <script>
            /* CROPPER */

            function init_cropper() {


                if (typeof ($.fn.cropper) === 'undefined') {
                    return;
                }
                console.log('init_cropper');

                var $image = $('#image');
                var $download = $('#download');
                var $dataX = $('#dataX');
                var $dataY = $('#dataY');
                var $dataHeight = $('#dataHeight');
                var $dataWidth = $('#dataWidth');
                var $dataRotate = $('#dataRotate');
                var $dataScaleX = $('#dataScaleX');
                var $dataScaleY = $('#dataScaleY');
                var options = {
                    aspectRatio: NaN,
                    preview: '.img-preview',
                    crop: function (e) {
                        //alert('crop')
                        $('.docs-buttons').find('button').removeAttr('disabled');
                        $('.send').removeAttr('disabled');
                        $('.init-upload').hide();
                        $dataX.val(Math.round(e.x));
                        $dataY.val(Math.round(e.y));
                        $dataHeight.val(Math.round(e.height));
                        $dataWidth.val(Math.round(e.width));
                        $dataRotate.val(e.rotate);
                        $dataScaleX.val(e.scaleX);
                        $dataScaleY.val(e.scaleY);
                    }
                };


                // Tooltip
                $('[data-toggle="tooltip"]').tooltip();


                // Cropper
                $image.on({
                    'build.cropper': function (e) {
                        console.log(e.type);
                    },
                    'built.cropper': function (e) {
                        console.log(e.type);
                    },
                    'cropstart.cropper': function (e) {
                        console.log(e.type, e.action);
                    },
                    'cropmove.cropper': function (e) {
                        console.log(e.type, e.action);
                    },
                    'cropend.cropper': function (e) {
                        console.log(e.type, e.action);
                    },
                    'crop.cropper': function (e) {
                        console.log(e.type, e.x, e.y, e.width, e.height, e.rotate, e.scaleX, e.scaleY);
                    },
                    'zoom.cropper': function (e) {
                        console.log(e.type, e.ratio);
                    }
                }).cropper(options);

                // Initiate cropper
                $image.one('built.cropper', function () {
                    var CanvasData = $image.cropper("getCanvasData");
                    $image.cropper("setCropBoxData", CanvasData);
                }).cropper(options);

                // Buttons
                if (!$.isFunction(document.createElement('canvas').getContext)) {
                    $('button[data-method="getCroppedCanvas"]').prop('disabled', true);
                }

                if (typeof document.createElement('cropper').style.transition === 'undefined') {
                    $('button[data-method="rotate"]').prop('disabled', true);
                    $('button[data-method="scale"]').prop('disabled', true);
                }


                // Download
                if (typeof $download[0].download === 'undefined') {
                    $download.addClass('disabled');
                }


                // Options
                $('.docs-toggles').on('change', 'input', function () {
                    var $this = $(this);
                    var name = $this.attr('name');
                    var type = $this.prop('type');
                    var cropBoxData;
                    var canvasData;

                    if (!$image.data('cropper')) {
                        return;
                    }

                    if (type === 'checkbox') {
                        options[name] = $this.prop('checked');
                        cropBoxData = $image.cropper('getCropBoxData');
                        canvasData = $image.cropper('getCanvasData');

                        options.built = function () {
                            $image.cropper('setCropBoxData', cropBoxData);
                            $image.cropper('setCanvasData', canvasData);
                        };
                    } else if (type === 'radio') {
                        options[name] = $this.val();
                    }

                    $image.cropper('destroy').cropper(options);
                });


                // Methods
                $('.docs-buttons').on('click', '[data-method]', function () {
                    var $this = $(this);
                    var data = $this.data();
                    var $target;
                    var result;

                    if ($this.prop('disabled') || $this.hasClass('disabled')) {
                        return;
                    }

                    if ($image.data('cropper') && data.method) {
                        data = $.extend({}, data); // Clone a new one

                        if (typeof data.target !== 'undefined') {
                            $target = $(data.target);

                            if (typeof data.option === 'undefined') {
                                try {
                                    data.option = JSON.parse($target.val());
                                } catch (e) {
                                    console.log(e.message);
                                }
                            }
                        }

                        result = $image.cropper(data.method, data.option, data.secondOption);

                        switch (data.method) {
                            case 'scaleX':
                            case 'scaleY':
                                $(this).data('option', -data.option);
                                break;

                            case 'getCroppedCanvas':
                                if (result) {
                                    //alert(result);
                                    // Bootstrap's Modal
                                    $('#getCroppedCanvasModal').modal().find('.modal-body').html(result);

                                    if (!$download.hasClass('disabled')) {
                                        $download.attr('href', result.toDataURL());
                                    }
                                }

                                break;
                        }

                        if ($.isPlainObject(result) && $target) {
                            try {
                                $target.val(JSON.stringify(result));
                            } catch (e) {
                                console.log(e.message);
                            }
                        }

                    }
                });

                // Keyboard
                $(document.body).on('keydown', function (e) {
                    if (!$image.data('cropper') || this.scrollTop > 300) {
                        return;
                    }

                    switch (e.which) {
                        case 37:
                            e.preventDefault();
                            $image.cropper('move', -1, 0);
                            break;

                        case 38:
                            e.preventDefault();
                            $image.cropper('move', 0, -1);
                            break;

                        case 39:
                            e.preventDefault();
                            $image.cropper('move', 1, 0);
                            break;

                        case 40:
                            e.preventDefault();
                            $image.cropper('move', 0, 1);
                            break;
                    }
                });

                // Import image
                var $inputImage = $('#inputImage');
                var URL = window.URL || window.webkitURL;
                var blobURL;

                if (URL) {
                    $inputImage.change(function () {
                        var files = this.files;
                        var file;

                        if (!$image.data('cropper')) {
                            return;
                        }

                        if (files && files.length) {
                            file = files[0];

                            if (/^image\/\w+$/.test(file.type)) {
                                blobURL = URL.createObjectURL(file);
                                $image.one('built.cropper', function () {
                                    var CanvasData = $image.cropper("getCanvasData");
                                    $image.cropper("setCropBoxData", CanvasData);
                                    // Revoke when load complete
                                    URL.revokeObjectURL(blobURL);
                                }).cropper('reset').cropper('replace', blobURL);
                                //$inputImage.val('');
                            } else {
                                window.alert('Please choose an image file.');
                            }
                        }
                    });
                } else {
                    $inputImage.prop('disabled', true).parent().addClass('disabled');
                }


            }
            ;

            /* CROPPER --- end */
            $(document).ready(function(){
                init_cropper();
            });
        </script>
    </body>
</html>
